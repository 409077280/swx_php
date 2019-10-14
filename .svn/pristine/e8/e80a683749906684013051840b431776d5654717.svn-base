<?php
/**
 * 数据中心订单接口(内部)
 *
 * @package app\api\controller\dbcenter
 * @author Jerry <tanping@dedeshijie.com>
 * @version 1.0
 * @created 2019-03-01
 */

namespace app\common\library\dbcenter\sharing;

use app\api\model\sharing\Order as OrderModel;
use app\api\model\Setting as SettingModel;
use app\common\library\dbcenter\Util;
use think\Config;

class Order {
    /**
     * 上报订单
     * @return boolean
     * @param integer $order_id 订单ID
     * @param integer $usser_id 用户ID
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function report($order_id, $user_id) {
        $setting   = SettingModel::getItem('newbie');
        $orderInfo = OrderModel::getUserOrderDetail($order_id, $user_id);
        if($orderInfo) {
            $goodsItems = [];
            if($orderInfo->goods) {
                foreach($orderInfo->goods as $orderGoods) {
                    $temp                     = [];
                    $temp['goodsId']          = $orderGoods->goods_id;
                    $temp['goodsName']        = $orderGoods->goods_name;
                    $temp['goodsNum']         = $orderGoods->total_num;
                    $temp['goodsSkuId']       = $orderGoods->goods_sku_id ? $orderGoods->goods_sku_id : 0;
                    $temp['goodsPrice']       = intval(strval(($orderGoods->total_price / $orderGoods->total_num) * 100));
                    $temp['contributionRate'] = intval($orderGoods->goods->contribution_rate) / 100;
                    $temp['goodsType']        = $orderGoods->goods->to_store == 0 ? 10 : 20;
                    $temp['canRefund']        = 40;
                    array_push($goodsItems, $temp);
                }
            }
            $attach  = 'sharing|' . $order_id . '|' . $user_id;
            $isFirst = (new OrderModel)->isFirstOrder($user_id);
            if($isFirst)
                $attach .= '|isOpen:' . $setting['first_order']['is_open'] . '|firstOrderContributionRate:' . ($setting['first_order']['referee']/100);

            $data = [
                'merchantCode'      => Config::get('dbcenter.merchantCode'),
                'userCode'          => $orderInfo['user_id'],
                'orderId'           => $orderInfo['order_no'],
                // 'orderAmount'       => (floatval($orderInfo['pay_price']) + floatval($orderInfo['coupon_price']) - floatval($orderInfo['express_price'])) * 100,
                'orderAmount'       => intval(strval((floatval($orderInfo['total_price'])) * 100)),
                // 'remark'            => '',
                'callbackUrl'       => Config::get('dbcenter.callbackUrl'),
                'goodsItems'        => $goodsItems,
                'attach'            => $attach,
                'isFirst'           => (int)$isFirst,
                'orderType'         => 30
            ];
            $sign = (new Util)->makePaySign($data);
            $data['sign'] = $sign;
            Util::request(Config::get('dbcenter.apiUrl') . 'dc/order/trade/report', $data);
        } else {
            return $this->renderError('订单不存在');
        }
    }

    /**
     * 退货退款
     */
    public function refund($order_id, $user_id) {
        $orderInfo = OrderModel::getUserOrderDetail($order_id, $user_id);
        if($orderInfo) {
            $data = [
                'merchantCode'      => Config::get('dbcenter.merchantCode'),
                'userCode'          => $orderInfo['user_id'],
                'orderId'           => $orderInfo['order_no'],
                'orderAmount'       => (floatval($orderInfo['pay_price']) + floatval($orderInfo['coupon_price']) - floatval($orderInfo['express_price'])) * 100,
                'remark'            => '',
                'callbackUrl'       => Config::get('dbcenter.callbackUrl'),
                'goodsItems'        => $goodsItems,
                'attach'            => 'refund|' . $order_id . '|' . $user_id
            ];
            $sign = (new Util)->makePaySign($data);
            $data['sign'] = $sign;
            Util::request(Config::get('dbcenter.apiUrl') . 'dc/refund/order/report', $data);
        } else {
            return $this->renderError('订单不存在');
        }
    }

    /**
     * 取消订单
     */
    public function cancel($order_id, $user_id) {
        $orderInfo = OrderModel::getUserOrderDetail($order_id, $user_id);
        if($orderInfo) {
            $goodsItems = [];
            if($orderInfo->goods) {
                foreach($orderInfo->goods as $orderGoods) {
                    $temp['goodsId']          = $orderGoods->goods_id;
                    $temp['goodsSkuId']       = $orderGoods->goods_sku_id ? $orderGoods->goods_sku_id : 0;
                    array_push($goodsItems, $temp);
                }
            }

            $data = [
                'merchantCode'      => Config::get('dbcenter.merchantCode'),
                'userCode'          => $orderInfo['user_id'],
                'orderId'           => $orderInfo['order_no'],
                'refundId'          => $orderInfo['order_no'],
                'goodsItems'        => $goodsItems,
                'type'              => 2,
                'callbackUrl'       => Config::get('dbcenter.callbackUrl'),
                'attach'            => 'cancel|' . $order_id . '|' . $user_id
            ];
            $sign = (new Util)->makePaySign($data);
            $data['sign'] = $sign;
            Util::request(Config::get('dbcenter.apiUrl') . '/dc/order/refund', $data);
        } else {
            return $this->renderError('订单不存在');
        }
    }
}
