<?php

namespace app\task\model\offline;

use app\common\model\offline\Order as OrderModel;
use app\task\model\User as UserModel;
use app\task\model\user\BalanceLog as BalanceLogModel;
use app\task\model\WxappPrepayId as WxappPrepayIdModel;
use app\common\service\Message as MessageService;
use app\common\service\order\Printer as Printerservice;
use app\common\service\order\Refund as RefundService;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\OrderStatus as OrderStatusEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\library\dbcenter\offline\Order as DBCenterOfflineOrderModel;

/**
 * 拼团订单模型
 * Class Order
 * @package app\common\model\sharing
 */
class Order extends OrderModel
{
    /**
     * 获取订单列表
     * @param array $filter
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($filter = [])
    {
        return $this->where($filter)
            ->where('is_delete', '=', 0)
            ->select();
    }

    /**
     * 待支付订单详情
     * @param $order_no
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function payDetail($order_no)
    {
        return self::get(['order_no' => $order_no, 'pay_status' => 10, 'is_delete' => 0]);
    }

    /**
     * 订单支付成功业务处理
     * @param int $payType 支付方式
     * @param array $payData 支付回调数据
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function paySuccess($payType, $payData = [])
    {
        // 更新付款状态
        $status = $this->updatePayStatus($payType, $payData);
        if ($status == true) {
            // 发送消息通知
            (new MessageService)->payment($this, OrderTypeEnum::OFFLINE);
            // 小票打印
            // (new Printerservice)->printTicket($this, OrderStatusEnum::ORDER_PAYMENT);
        }
        return $status;
    }

    /**
     * 更新付款状态
     * @param $payType
     * @param $payData
     * @return bool
     * @throws \think\exception\DbException
     */
    private function updatePayStatus($payType, $payData)
    {
        // 获取用户信息
        $user = UserModel::detail($this['user_id']);
        // 验证余额支付时用户余额是否满足
        if ($payType == PayTypeEnum::BALANCE) {
            if ($user['balance'] < $this['pay_price']) {
                $this->error = '用户余额不足，无法使用余额支付';
                return false;
            }
        }
        $this->transaction(function () use ($user, $payType, $payData) {
            // 更新订单状态
            $order = ['pay_type' => $payType, 'pay_status' => 20, 'pay_time' => time()];
            if ($payType == PayTypeEnum::WECHAT) {
                $order['transaction_id'] = $payData['transaction_id'];
            }
            $this->save($order);
            // 累积用户总消费金额
            $user->cumulateMoney($this['pay_price']);
            // 余额支付
            if ($payType == PayTypeEnum::BALANCE) {
                // 更新用户余额
                $user->setDec('balance', $this['pay_price']);
                BalanceLogModel::add(SceneEnum::CONSUME, [
                    'user_id' => $user['user_id'],
                    'money' => -$this['pay_price'],
                ], ['order_no' => $this['order_no']]);
            }
            // 微信支付
            if ($payType == PayTypeEnum::WECHAT) {
                // 更新prepay_id记录
                WxappPrepayIdModel::updatePayStatus($this['order_id'], OrderTypeEnum::OFFLINE);
            }

            // 上传订单数据到数据中心 Added by Jerry @ 2019-03-10
            (new DBCenterOfflineOrderModel)->report($this['order_id'], $this['user_id']);
        });
        return true;
    }
}
