<?php

namespace app\api\model\sharing;

use app\common\model\sharing\Goods as GoodsModel;

/**
 * 拼团商品模型
 * Class Goods
 * @package app\api\model\sharing
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

    /**
     * 根据商品id集获取商品列表
     * @param $goodsIds
     * @param null $status
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByIds($goodsIds, $status = null)
    {
        // 筛选条件
        $filter = ['goods_id' => ['in', $goodsIds]];
        $status > 0 && $filter['goods_status'] = $status;
        if (!empty($goodsIds)) {
            $this->orderRaw('field(goods_id, ' . implode(',', $goodsIds) . ')');
        }
        /*
        // 超过结束时间不显示,不超过但未开始的商品可以展示给用户
        $this->where('end_time', '>', time());
        */
        // 获取商品列表数据
        return $this->with(['category', 'image.file', 'sku', 'spec_rel.spec', 'delivery.rule'])
            ->where($filter)
            ->select();
    }

    public function getGoodsDetail($goodsId){
        $model = new static;
        return $model->where(['goods_id'=>$goodsId])->find()->toArray();
    }

    public function getList(
        $status = null,
        $category_id = 0,
        $search = '',
        $sortType = 'all',
        $sortPrice = false,
        $listRows = 15
    ){
        /*
        // 超过结束时间不显示,不超过但未开始的商品可以展示给用户
        $this->where('end_time', '>', time());
        */
        return parent::getList(
            $status,
            $category_id,
            $search,
            $sortType,
            $sortPrice,
            $listRows
        );
    }

}
