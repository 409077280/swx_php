<?php

namespace app\common\service;

use app\common\library\helper;
use app\common\model\Goods as GoodsModel;

/**
 * 商品服务类
 * Class Goods
 * @package app\store\service
 */
class Goods
{
    /**
     * 设置商品数据
     * @param $data
     * @param bool $isMultiple
     * @param string $goodsIndex
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function setGoodsData(&$data, $isMultiple = true, $goodsIndex = 'goods_id')
    {
        if (!$isMultiple) $dataSource = [&$data]; else $dataSource = &$data;
        // 获取商品列表
        $model = new GoodsModel;
        $goodsData = $model->getListByIds(helper::getArrayColumn($dataSource, $goodsIndex));
        $goodsList = helper::arrayColumn2Key($goodsData, 'goods_id');
        // 整理列表数据
        foreach ($dataSource as &$item) {
            $item['goods'] = isset($goodsList[$item[$goodsIndex]]) ? $goodsList[$item[$goodsIndex]] : null;
        }
        return $data;
    }

}