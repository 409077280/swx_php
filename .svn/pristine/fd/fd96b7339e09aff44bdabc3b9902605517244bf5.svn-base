<?php

namespace app\store\model;

use app\common\model\OrderGoods as OrderGoodsModel;

/**
 * 订单商品模型
 * Class OrderGoods
 * @package app\store\model
 */
class OrderGoods extends OrderGoodsModel
{

    /**
     * 获取当前订单商品信息
     * @param $orderId
     */
    public function getOrderGoodsInfo($orderId){
        $model = new static;
        $data = $model->where(['order_id' => $orderId])->select()->toArray();
        if (count($data) > 0){
            return $data;
        }
        return false;
    }
}
