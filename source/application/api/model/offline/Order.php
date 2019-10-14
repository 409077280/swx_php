<?php

namespace app\api\model\offline;

use app\common\model\offline\Order as OrderModel;
use app\api\model\Setting as SettingModel;
use app\api\service\Payment as PaymentService;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\enum\order\PayType as PayTypeEnum;

use app\common\service\wechat\wow\Order as WowService;
use app\common\service\delivery\Express as ExpressService;
use app\common\exception\BaseException;

/**
 * 订单模型
 * Class Order
 * @package app\api\model
 */
class Order extends OrderModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'update_time'
    ];

    /**
     * 订单确认-立即购买
     * @param User $user
     * @return array
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBuyNow($user, $order_amount, $pay_type)
    {
        return [
            'order_total_price' => $order_amount,
            'pay_type'          => $pay_type,
            'order_pay_price'   => $order_amount,
            'has_error'   => $this->hasError(),
            'error_msg'   => $this->getError(),
        ];
    }

    /**
     * 创建新订单
     * @param \app\api\model\User $user
     * @param array $order 订单信息
     * @param string $remark
     * @return bool
     * @throws \Exception
     */
    public function createOrder($user, $order, $remark = '')
    {
        // 表单验证
        if (!$this->validateOrderForm($user, $order)) {
            return false;
        }
        // 创建新的订单
        $status = $this->transaction(function () use ($order, $user, $remark) {
            // 记录订单信息
            $status = $this->add($user['user_id'], $order, $remark);
            // 获取订单详情
            $detail = self::getUserOrderDetail($this['order_id'], $user['user_id']);

            return $status;
        });
        // 余额支付标记订单已支付
        if ($status && $order['pay_type'] == PayTypeEnum::BALANCE) {
            $this->paymentByBalance($this['order_no']);
        }
        return $status;
    }

    /**
     * 构建微信支付请求
     * @param \app\api\model\User $user
     * @return array
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public function paymentByWechat($user)
    {
        return PaymentService::wechat(
            $user,
            $this['order_id'],
            $this['order_no'],
            $this['pay_price'],
            OrderTypeEnum::OFFLINE
        );
    }

    /**
     * 余额支付标记订单已支付
     * @param string $orderNo 订单号
     * @return bool
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function paymentByBalance($orderNo)
    {
        // 获取订单详情
        $model = new \app\task\model\offline\Order;
        $order = $model->payDetail($orderNo);
        // 发起余额支付
        $status = $order->paySuccess(PayTypeEnum::BALANCE);
        if (!$status) {
            $this->error = $order->error;
        }
        return $status;
    }

    /**
     * 表单验证 (订单提交)
     * @param \app\api\model\User $user 用户信息
     * @param array $order 订单信息
     * @param string $linkman 联系人
     * @param string $phone 联系电话
     * @return bool
     */
    private function validateOrderForm($user, &$order)
    {
        // 余额支付时判断用户余额是否足够
        if ($order['pay_type'] == PayTypeEnum::BALANCE) {
            if ($user['balance'] < $order['order_pay_price']) {
                $this->error = '用户余额不足，无法使用余额支付';
                return false;
            }
        }
        return true;
    }

    /**
     * 新增订单记录
     * @param $user_id
     * @param $order
     * @param string $remark
     * @return false|int
     */
    private function add($user_id, &$order, $remark = '')
    {
        $data = [
            'user_id' => $user_id,
            'order_no' => $this->orderNo(),
            'total_price' => $order['order_total_price'],
            'pay_price' => $order['order_pay_price'],
            'pay_type' => $order['pay_type'],
            'buyer_remark' => trim($remark),
            'wxapp_id' => self::$wxapp_id,
        ];

        return $this->save($data);
    }

    /**
     * 用户中心订单列表
     * @param $user_id
     * @param string $type
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = PayStatusEnum::PENDING;
                $filter['order_status'] = 10;
                break;
            case 'delivery';
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = 10;
                $filter['order_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                $filter['order_status'] = 10;
                break;
            case 'comment';
                $filter['is_comment'] = 0;
                $filter['order_status'] = 30;
                break;
        }
        return $this->with(['goods.image'])
            ->where('user_id', '=', $user_id)
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }


    /**
     * 获取订单总数
     * @param $user_id
     * @param string $type
     * @return int|string
     * @throws \think\Exception
     */
    public function getCount($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = PayStatusEnum::PENDING;
                break;
            case 'received';
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'comment';
                $filter['order_status'] = 30;
                $filter['is_comment'] = 0;
                break;
            case 'delivery';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 10;
                $filter['order_status'] = ['in', [10, 21]];
                break;
        }
        return $this->where('user_id', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->count();
    }

    /**
     * 当前订单是否允许申请售后
     * @return bool
     */
    public function isAllowRefund()
    {
        // 允许申请售后期限
        $refund_days = SettingModel::getItem('trade')['order']['refund_days'];
        if ($refund_days == 0) {
            return false;
        }
        if (time() > $this['receipt_time'] + ((int)$refund_days * 86400)) {
            return false;
        }
        if ($this['receipt_status']['value'] != 20) {
            return false;
        }
        return true;
    }

    /**
     * 设置错误信息
     * @param $error
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }

    /**
     * 删除订单
     * @return bool|false|int
     * @throws \Exception
     */
    public function delete() {
        if ($this['order_status']['value'] != 20) {
            $this->error = '只有已取消订单才能删除';
            return false;
        }
        try {
            return $this->save(['is_delete' => 1]);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 检查是否首单
     * @param $user_id
     * @param string $type
     * @return int|string
     * @throws \think\Exception
     */
    public function isFirstOrder($user_id)
    {
        $count = $this->where('user_id', '=', $user_id)
            ->where('pay_status', '=', 20)
            ->count();

        return $count >= 1 ? false : true;
    }

    /**
     * 订单详情
     * @param $order_id
     * @param null $user_id
     * @return null|static
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public static function getUserOrderDetail($order_id, $user_id)
    {
        if (!$order = self::get([
            'order_id' => $order_id,
            'user_id' => $user_id,
        ])
        ) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        return $order;
    }
}
