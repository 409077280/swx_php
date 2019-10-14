<?php

namespace app\task\behavior;

use think\Cache;
use app\task\model\Goods as GoodsModel;

/**
 * 订单行为管理
 * Class Order
 * @package app\task\behavior
 */
class Goods
{
    /* @var \app\task\model\Order $model */
    private $model;

    /**
     * 执行函数
     * @param $model
     * @return bool
     */
    public function run($model)
    {
        if (!$model instanceof GoodsModel) {
            return new GoodsModel and false;
        }
        $this->model = $model;
        if (!$model::$wxapp_id) {
            return false;
        }
        if (!Cache::has('__task_space__goods__' . $model::$wxapp_id)) {
            $this->model->transaction(function () {
                // 商品自动下架
                //$this->offShelves();
            });
            Cache::set('__task_space__goods__' . $model::$wxapp_id, time(), 3600);
        }
        return true;
    }


    /**
     * 记录日志
     * @param $method
     * @param array $params
     * @return bool|int
     */
    private function dologs($method, $params = [])
    {
        $value = 'behavior Goods --' . $method;
        foreach ($params as $key => $val)
            $value .= ' --' . $key . ' ' . $val;
        return log_write($value);
    }

    /**
     * 更新待下架商品
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function offShelves() {
        $model = new GoodsModel;
        $model->startTrans();
        try{
            // 获取超过下架时间的商品
            $list = $model->getOverdueGoods();
            // 待下架商品集
            $data = [];
            foreach ($list as $key => $value){
                $data[$key] = [
                    'goods_id' => $value['active_id'],
                    'goods_status' => 20,
                ];
            }
            // 更新已过期状态
            $result = $model->setGoodsStatus($data);
            if (!$result){
                $model->rollback();
            }
            $model->commit();
            // 记录日志
            $this->dologs('offShelves', [
                'GoodsIds' => json_encode($list),
            ]);
            return true;
        }catch (\Exception $e){
            $model->rollback();
            $this->dologs('offShelves', [
                'error: ' => $e->getMessage(),
            ]);
            return false;
        }
    }

}
