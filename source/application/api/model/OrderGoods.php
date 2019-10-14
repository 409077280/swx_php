<?php

namespace app\api\model;

use app\api\model\Setting as SettingModel;
use app\common\model\OrderGoods as OrderGoodsModel;
use app\common\model\Order as OrderModel;

/**
 * 订单商品模型
 * Class OrderGoods
 * @package app\api\model
 */
class OrderGoods extends OrderGoodsModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'content',
        'wxapp_id',
        'create_time',
    ];

    /**
     * 获取未评价的商品
     * @param $order_id
     * @return OrderGoods[]|false
     * @throws \think\exception\DbException
     */
    public static function getNotCommentGoodsList($order_id)
    {
        return self::all(['order_id' => $order_id, 'is_comment' => 0], ['orderM', 'image']);
    }

    /**
     * 获取当前用户某商品的购买次数,按已付款的订单作参考
     * @param $user_id
     * @param $good_id
     * @return bool|int
     */
    public function getGoodBuyTimes($user_id, $good_id){
        $model = new static;
        $orderModel = new OrderModel;
        try{
            $orderGoods = $model->field('order_id')->where(['user_id' => $user_id, 'goods_id' => $good_id])->group('order_id')->select();
            $number = 0;
            foreach ($orderGoods as &$item){
                $exists = $orderModel->where(['order_id' => $item->order_id, 'pay_status' => 20])->find();
                if ($exists){
                    $number++;
                }
            }
            return $number;
        }catch (\Exception $e){
            $this->error = $e;
            return false;
        }
    }

    /**
     * 当前订单是否允许申请售后
     * @return bool
     */
     /**
      * 当前订单是否允许申请售后
      * @return bool
      */
     public function isAllowRefund($order, $goods)
     {
         if($goods['refund_status'] == '20')
             return false;

         // 允许申请售后期限
         $refund_days = SettingModel::getItem('trade')['order']['refund_days'];
         if ($refund_days == 0) {
             return false;
         }
         if (time() < $this['receipt_time'] + ((int)$refund_days * 86400)) {
             return false;
         }

         // 已发货
         if($this['delivery_status'] == '20') {
             return false;
         }

         // 已取消或待取消
         if($this['status'] != '1') {
             return false;
         }

         // 待付款订单
         if($order['pay_status']['value'] != '20') {
             return false;
         }

         return true;
     }
}
