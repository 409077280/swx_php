<?php


namespace app\common\model;

/**
 * 订单商品取消模型
 * Class OrderCancel
 * @package app\common\model\wxapp
 */
class OrderCancel extends BaseModel
{
    protected $name = 'order_cancel';

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 关联订单主表
     * @return \think\model\relation\BelongsTo
     */
    public function orderMaster()
    {
        return $this->belongsTo('Order');
    }

    /**
     * 关联订单商品表
     * @return \think\model\relation\BelongsTo
     */
    public function orderGoods()
    {
        return $this->belongsTo('OrderGoods');
    }

}
