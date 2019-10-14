<?php
namespace app\api\model;

use app\common\model\CategoryDisplay as CategoryDisplayModel;
use app\common\model\Category as CategoryModel;

class CategoryDisplay extends CategoryDisplayModel
{
    /**
     * 通过分类ID获取当前分类ID下所有商品Id
     * @param $category_id
     * @return array
     */
    public static function getGoodsId($category_id){
        $list = self::getAllByGoodsParams(['category_id' => $category_id]);
        $data = array();
        foreach ($list as $value){
            array_push($data, $value['goods_id']);
        }
        return $data;
    }

    /**
     * 获取当前商品的所属分类
     * @param $goodsId
     * @return bool|false|\PDOStatement|string|\think\Collection
     */
    public function getCategoryByGoodsId($goodsId){
        try{
            $list = $this->where(['goods_id' => $goodsId])->select();
            if (!$list){
                $this->error = '查询失败或暂无分类';
                return false;
            }
            foreach ($list as &$item){
                $categoryName = CategoryModel::get($item['category_id']);
                $item['name'] = $categoryName['name'];
            }
            return $list;
        }catch (\Exception $e){
            $this->error = $e;
            return false;
        }
    }
}