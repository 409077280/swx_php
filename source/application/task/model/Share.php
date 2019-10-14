<?php

namespace app\task\model;

use app\common\model\Share as ShareModel;

/**
 * 分享模型
 * Class ShareModel
 * @package app\api\model
 */
class Share extends ShareModel {
    public function setShareExpires($data){
        return $this->isUpdate(true)->saveAll($data);
    }
}
