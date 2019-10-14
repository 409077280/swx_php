<?php

namespace app\store\controller\market;

use app\store\controller\Controller;
use app\store\model\Setting as SettingModel;
use app\store\model\UserCheckIn as UserCheckInModel;
use app\store\model\User as UserModel;

/**
 * 营销设置-新人礼包
 * Class Basic
 * @package app\store\controller
 */
class Usercheckin extends Controller {
    /**
     * 新人礼包设置
     * @return array|bool|mixed
     * @throws \think\exception\DbException
     */
    public function setting() {
        return $this->fetch('setting', ['title' => '用户签到设置']);
    }

    public function record(){
        return $this->fetch('record', ['title' => '用户签到记录']);
    }

    /**
     *  获取设置信息
     * @return \think\response\Json
     */
    public function get_setting(){
        $values = SettingModel::getItemFromDb('checkin');
        return json($this->renderJson(0, "", '', $values));
    }

    /**
     * 更改签到设置
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function save_setting(){
        if (!$this->request->isPut()){
            return json($this->renderJson(1, "请求错误", '', []));
        }
        $setting = $this->request->put();
        if (isset($setting)){
            $setting['is_open'] = (int)$setting['is_open'];
        }
        $model = new SettingModel;
        if ($model->edit('checkin', $setting)) {
            return json($this->renderJson(0, "更新设置成功", '', []));
        }
        return json($this->renderJson(1, "更新失败", '', []));
    }


    /**
     * 获取用户签到列表信息
     * @param $user_name
     * @param $page
     * @param $limit
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_checkIn_list($user_name, $page, $limit){
        if (!empty($user_name)){
            $userIds = (new UserModel)->getUserIdsByNickName($user_name);
            if (count($userIds) == 0){
                return json($this->renderJson(1, '用户不存在', '', []));
            }
        } else{
            $userIds = null;
        }

        $model = new UserCheckInModel;
        $result = $model->getList($userIds, $page, $limit);
        if ($result){
            return json($this->renderJson(0, '', '', $result));
        }
        return json($this->renderJson(1, $model->getError(), '', []));
    }
}
