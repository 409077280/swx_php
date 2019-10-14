<?php

namespace app\common\model;

use think\Exception;

class CategoryDisplay extends BaseModel
{
    protected $name = 'category_display';

    /** 根据条件获取
     * @param $where
     * @return array
     */
    public static function getAllByGoodsParams($where){
        $model = new static;
        $data = $model->where($where)->select()->toArray();
        return $data;
    }

    /**
     *  删除商品的展示分类
     * @param $goodsId
     * @return int
     */
    public static function deleteRecord($goodsId){
        $model = new static;
        $model->where(['goods_id' => $goodsId])->delete();
    }

    /**
     *  批量插入
     * @param $data
     * @throws Exception
     */
    public static function insertData($data){
        $model = new static;
        $result = $model->insertAll($data);
        if (!is_int($result) ){
            throw new Exception($result);
        }
    }

}