<?php

namespace app\store\model;

use app\common\model\CategoryDisplay as CategoryDisplayModel;
use think\Exception;

class CategoryDisplay extends CategoryDisplayModel{

    /**
     * 批量插入商品ID和分类ID
     * @param $displayCategoryIds
     * @param $goodsId
     * @throws Exception
     */
    public static function dataInsert($displayCategoryIds, $goodsId, $wxappId){
        $data = [];
        for ($i = 0; $i < count($displayCategoryIds); $i++) {
            $data[$i]['goods_id'] = $goodsId;
            $data[$i]['category_id'] = $displayCategoryIds[$i];
            $data[$i]['create_time'] = time();
            $data[$i]['update_time'] = $data[$i]['create_time'];
            $data[$i]['wxapp_id'] = $wxappId;
        }
        self::insertData($data);
    }

    /**
     *  更新商品展示分类
     * @param $displayCategoryIds
     * @param $goodsId
     * @param $wxappId
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public static function updateRecord($displayCategoryIds, $goodsId, $wxappId)
    {
        $data = [];
        for ($i = 0; $i < count($displayCategoryIds); $i++) {
            $data[$i]['goods_id'] = $goodsId;
            $data[$i]['category_id'] = $displayCategoryIds[$i];
            $data[$i]['create_time'] = time();
            $data[$i]['update_time'] = $data[$i]['create_time'];
            $data[$i]['wxapp_id'] = $wxappId;
        }
        self::deleteRecord($goodsId);
        self::dataInsert($displayCategoryIds, $goodsId, $wxappId);
    }
}