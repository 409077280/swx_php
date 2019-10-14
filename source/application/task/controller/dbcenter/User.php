<?php

namespace app\task\controller\dbcenter;

use app\task\model\UserCheckIn as UserCheckInModel;

/**
 * 上传订单成功异步通知接口
 * Class Notify
 * @package app\api\controller
 */
class User {
    /**
     * 上传用户信息到数据中心成功异步通知
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function notify() {
        $responseData = json_decode((file_get_contents("php://input")), true);
        if(isset($responseData['data']['attach']) && $responseData['data']['attach']) {
            list($action, $recordId, $userId) = explode('|', $responseData['data']['attach']);
            if($action == 'userCheckIn') {
                $model = new UserCheckInModel;
                $model->changeCallBackStatus($recordId);
            } else if($action == 'cancel' || $action == 'refund') {
                //TODO:
            }
        }
    }
}
