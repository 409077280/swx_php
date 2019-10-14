<?php

namespace app\api\model;

use app\common\model\Share as ShareModel;
use app\api\model\Setting as SettingModel;

/**
 * 分享模型
 * Class ShareModel
 * @package app\api\model
 */
class Share extends ShareModel {
    /**
     * 添加商品推荐关系
     */
    public function add($data) {
        return $this->save([
            'stype'     => $data['stype'],
            'user_id'   => $data['user_id'],
            'target_id' => $data['target_id'],
            'tag'       => $data['tag'],
            'wxapp_id'  => self::$wxapp_id
        ]);
    }

    /**
     * 检查商品分享关系是否在有效期内
     */
     public function checkGoodsShareExpires($stype = 'goods', $userId, $targetId, $tag) {
         $item = self::get(['stype' => $stype, 'user_id' => $userId, 'target_id' => $targetId, 'tag' => $tag, 'is_expires' => 1]);
         if($item) {
             $expires = (int) SettingModel::getItem('goods_share')['expires'];
             if(strtotime($item->create_time) + $expires * 86400 > time())
                return true;
         }

         return false;
     }
}
