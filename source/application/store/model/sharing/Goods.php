<?php

namespace app\store\model\sharing;

use app\common\model\sharing\Goods as GoodsModel;

/**
 * 拼团商品模型
 * Class Goods
 * @package app\store\model\sharing
 */
class Goods extends GoodsModel
{
    /**
     * 添加商品
     * @param array $data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function add(array $data)
    {
        if (!isset($data['images']) || empty($data['images'])) {
            $this->error = '请上传商品图片';
            return false;
        }
        $data['content'] = isset($data['content']) ? $data['content'] : '';
        $data['wxapp_id'] = $data['sku']['wxapp_id'] = self::$wxapp_id;
        $data['start_time'] = (isset($data['start_time']) && $data['start_time']) ? strtotime($data['start_time']) : time();
        // 若结束时间为空结束时间默认为3天
        $data['end_time'] = (isset($data['end_time']) && $data['end_time']) ? strtotime($data['end_time']) : time() + 3600  * 72;
        if ($data['start_time'] >= $data['end_time']){
            $this->error = '开始时间不能大于等于结束时间';
            return false;
        }

        // 开启事务
        $this->startTrans();
        try {
            // 添加商品
            $this->allowField(true)->save($data);
            // 商品规格
            $this->addGoodsSpec($data);
            // 商品图片
            $this->addGoodsImages($data['images']);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 添加商品图片
     * @param $images
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function addGoodsImages($images)
    {
        $this->image()->delete();
        $data = array_map(function ($image_id) {
            return [
                'image_id' => $image_id,
                'wxapp_id' => self::$wxapp_id
            ];
        }, $images);
        return $this->image()->saveAll($data);
    }

    /**
     * 编辑商品
     * @param $data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function edit($data)
    {
        if (!isset($data['images']) || empty($data['images'])) {
            $this->error = '请上传商品图片';
            return false;
        }
        $data['content'] = isset($data['content']) ? $data['content'] : '';
        $data['wxapp_id'] = $data['sku']['wxapp_id'] = self::$wxapp_id;
        $data['start_time'] = (isset($data['start_time']) && $data['start_time']) ? strtotime($data['start_time']) : time();
        // 若结束时间为空结束时间默认为3天
        $data['end_time'] = (isset($data['end_time']) && $data['end_time']) ? strtotime($data['end_time']) : time() + 3600  * 72;
        if ($data['start_time'] >= $data['end_time']){
            $this->error = '开始时间不能大于等于结束时间';
            return false;
        }
        // 开启事务
        $this->startTrans();
        try {
            // 保存商品
            $this->allowField(true)->save($data);
            // 商品规格
            $this->addGoodsSpec($data, true);
            // 商品图片
            $this->addGoodsImages($data['images']);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 添加商品规格
     * @param $data
     * @param $isUpdate
     * @throws \Exception
     */
    private function addGoodsSpec(&$data, $isUpdate = false)
    {
        // 更新模式: 先删除所有规格
        $model = new GoodsSku;
        $isUpdate && $model->removeAll($this['goods_id']);
        // 添加规格数据
        if ($data['spec_type'] == '10') {
            // 单规格
            $this->sku()->save($data['sku']);
        } else if ($data['spec_type'] == '20') {
            // 添加商品与规格关系记录
            $model->addGoodsSpecRel($this['goods_id'], $data['spec_many']['spec_attr']);
            // 添加商品sku
            $model->addSkuList($this['goods_id'], $data['spec_many']['spec_list']);
        }
    }

    /**
     * 修改商品状态
     * @param $state
     * @return false|int
     */
    public function setStatus($state)
    {
        return $this->save(['goods_status' => $state ? 10 : 20]) !== false;
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 获取当前商品总数
     * @param array $where
     * @return int|string
     */
    public function getGoodsTotal($where = [])
    {
        $this->where('is_delete', '=', 0);
        !empty($where) && $this->where($where);
        return $this->count();
    }


}
