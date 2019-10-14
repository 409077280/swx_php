<?php

namespace app\store\model;

use app\common\model\OrderAddress as OrderAddressModel;

/**
 * 订单收货地址模型
 * Class OrderAddress
 * @package app\store\model
 */
class OrderAddress extends OrderAddressModel
{
    /**
     *  修改收货人信息
     * @param $order_id
     * @param $data
     * @return OrderAddress
     */
    public function resetOrderAddress($order_id, $data){
        $model = new static;
        return $model->where(['order_id' => $order_id])->update($data);
    }
}
