<?php

namespace app\api\model;

use app\common\model\Goods as GoodsModel;

/**
 * 商品模型
 * Class Goods
 * @package app\api\model
 */
class Goods extends GoodsModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'sales_initial',
        'sales_actual',
        'is_delete',
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * 商品详情：HTML实体转换回普通字符
     * @param $value
     * @return string
     */
    public function getContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    public function getList($status = null, $category_id = 0, $search = '', $sortType = 'all', $sortPrice = false, $listRows = 15, $customSort = '')
    {
        // 上架判断规则改变，不再直接判断goods_status
        $status = null;
        $currentTime = time();
        $where = "(goods_status = 10 AND start_time < {$currentTime}) OR (goods_status = 20 AND end_time > {$currentTime})";
        $this->where($where);
        return parent::getList($status, $category_id, $search, $sortType, $sortPrice, $listRows, $customSort);
    }

    public function getListByIds($goodsIds, $status = null, $customSort = ''){
        $status = null;
        $currentTime = time();
        $where = "(goods_status = 10 AND start_time < {$currentTime}) OR (goods_status = 20 AND end_time > {$currentTime})";
        $this->where($where);
        return parent::getListByIds($goodsIds, $status, $customSort);
    }

}
