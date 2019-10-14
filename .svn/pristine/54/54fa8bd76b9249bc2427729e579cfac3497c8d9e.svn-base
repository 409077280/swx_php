<?php

namespace app\common\model;

/**
 * 分享模型
 * Class Share
 * @package app\common\model
 */
class Share extends BaseModel {
    protected $name = 'share';
    protected $updateTime = false;

    /**
     * 获取所有数据
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
     public static function getAll() {
         return (new static)->where('is_expires', '=', 1)->select();
     }
}
