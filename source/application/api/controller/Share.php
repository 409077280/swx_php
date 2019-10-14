<?php

namespace app\api\controller;

use app\api\model\Share as ShareModel;

/**
 * 分享控制器
 * Class Goods
 * @package app\api\controller
 */
class Share extends Controller {
    /**
     * 分享回调
     * @param string $type 分享类型
     * @param int $user_id 用户ID
     * @param int $target_id 分享类型ID
     * @return array
     * @throws \think\exception\DbException
     */
    public function callback($type = 'goods', $user_id = 0, $target_id = 0, $tag = '') {
        if($user_id) {
            $model = new ShareModel;
            $data = [
                'stype'     => $type,
                'user_id'   => (int) $user_id,
                'target_id' => (int) $target_id,
                'tag'       => $tag
            ];
            if($model->add($data)) {
                return $this->renderSuccess();
            }
        }
    }
}
