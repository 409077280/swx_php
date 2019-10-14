<?php
namespace app\api\controller;

use app\api\model\Setting as SettingModel;
use app\api\model\UserCheckIn as UserCheckInModel;

class Usercheckin extends Controller{


    /**
     * 用户每日签到
     * @return \think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function checkIn(){
        $contribution = '0';
        if ($this->request->isPost() == false){
            return json($this->renderJson(1, "请求类型错误.", [
                'status' => 0,
                'contribution' => $contribution,
            ]));
        }
        $postData = $this->request->post();
        $userId = $this->getUser()->user_id;
        $wxAppId = $postData['wxapp_id'];
        // 检查能否签到
        $check = $this->getCheckInAble($userId, $wxAppId);
        if($check['code'] == 1){
            return json($this->renderJson(1, $check['msg'], [
                'status' => 0,
                'contribution' => $contribution,
            ]));
        }
        $model = new UserCheckInModel;

        $signIn = $model->checkIn($userId, $wxAppId,$contribution);
        if (!$signIn){
            return json($this->renderJson(1, $model->getError(), [
                'status' => 0,
                'contribution' => $contribution,
            ]));
        }
        return json($this->renderJson(1, "签到成功！", [
            'status' => 1,
            'contribution' => $contribution,
        ]));
    }

    /**
     * 小程序签到UI展示状态
     * @return \think\response\Json
     */
    public function checkInAble(){
        if ($this->request->isPost() == false){
            return json($this->renderJson(1, "请求类型错误.", 0));
        }
        $postData = $this->request->post();
        try{
            $userId = $this->getUser()->user_id;
            $wxAppId = $postData['wxapp_id'];
        }catch (\Exception $e){
            // 小程序的锅，先返回1，保证签到按钮正常显示
            return json($this->renderJson(1, $e->getMessage(), 1));
        }
        // 检查能否签到
        $check = $this->getCheckInAble($userId, $wxAppId);
        if($check['code'] == 1){
            return json($this->renderJson(1, $check['msg'], 0));
        }
        return json($this->renderJson(1, $check['msg'], 1));
    }

    /**
     * 检查能否签到
     * @param $userId
     * @param $wxAppId
     * @return array
     */
    private function getCheckInAble($userId, $wxAppId){
        // 检查是否开启签到功能
        //Cache::rm('setting_' . $wxAppId);
        $setting = SettingModel::getItem('checkin', $wxAppId);
        if ($setting['is_open'] == 0){
            return ['code' => 1,'msg' => '签到功能已关闭'];
        }
        // 检查是否已经签到
        $model = new UserCheckInModel;
        $able = $model->verifyCheckInAble($userId);
        if (!$able){
            return ['code' => 1,'msg' => $model->getError()];
        }
        return ['code' => 0,'msg' => '未签到'];
    }
}