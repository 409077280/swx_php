<?php

namespace app\task\behavior;

use think\Cache;
use app\task\model\Share as ShareModel;
use app\task\model\Setting as SettingModel;

/**
 * 分享行为管理
 * Class Share
 * @package app\task\behavior
 */
class Share {
    /* @var \app\task\model\Share $model */
    private $model;

    /**
     * 执行函数
     * @param $model
     * @return bool
     */
    public function run($model) {
        if (!$model instanceof ShareModel) {
            return new ShareModel and false;
        }
        $this->model = $model;
        if (!$model::$wxapp_id) {
            return false;
        }
        if (!Cache::has('__task_space__share__' . $model::$wxapp_id)) {
            $this->model->transaction(function () {
                // 处理分享有效期
                $this->dealGoodsShareExpires();
            });
            Cache::set('__task_space__share__' . $model::$wxapp_id, time(), 3600);
        }
        return true;
    }


    /**
     * 记录日志
     * @param $method
     * @param array $params
     * @return bool|int
     */
    private function dologs($method, $params = []) {
        $value = 'behavior Share --' . $method;
        foreach ($params as $key => $val)
            $value .= ' --' . $key . ' ' . $val;
        return log_write($value);
    }

    /**
     * 处理分享有效期
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function dealGoodsShareExpires() {
        $dealIds = [];
        $items   = ShareModel::getAll();
        if($items) {
            $data    = [];
            $expires = (int) SettingModel::getItem('goods_share')['expires'];
            foreach($items as $key => $item) {
                if(strtotime($item->create_time) + $expires * 86400 <= time()) {
                    $data[$key] = [
                        'id' => $item->id,
                        'is_expires' => 0
                    ];
                    array_push($dealIds, $item->id);
                }
            }
            if($data) {
                $result = (new ShareModel)->setShareExpires($data);
                if($result) {
                    // 记录日志
                    $this->dologs('dealGoodsShareExpires', [
                        'ShareIds' => json_encode($dealIds),
                    ]);
                    return true;
                }
            }
        }
    }
}
