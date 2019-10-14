<?php
namespace app\common\model;

use app\common\library\dbcenter\Util;
use app\common\model\Setting as SettingModel;
use think\Cache;
use think\Config;

class UserCheckIn extends BaseModel{

    protected $name = 'user_check_in';

    /**
     * 获取用户签到列表
     * @param $userId
     * @param $page
     * @param $limit
     * @return bool|false|\PDOStatement|string|\think\Collection
     */
    public function getList($userIds, $page, $limit){
        if (!empty($userIds)){
            $this->whereIn('check.user_id', $userIds);
        }
        try{
            $data = $this->alias('check')
                ->join('yoshop_user user','check.user_id = user.user_id', 'LEFT')
                ->field('check.*, user.nickName, user.avatarUrl')
                ->order('check.id', 'desc')
                ->paginate($limit, false, [
                    'page' => $page,
                ]);
            return $data;
        }catch (\Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * 获取随机贡献或分红
     */
    protected static function _random($nums) {
        $nums = str_replace('，', ',', $nums);
        $arr  = explode(',', $nums);
        if($arr) {
            $len = count($arr);
            $idx = rand(0, $len - 1);
            return $arr[$idx];
        } else {
            return 1;
        }
    }
}