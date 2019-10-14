<?php
namespace app\task\model;

use app\common\model\UserCheckIn as UserCheckInModel;

class UserCheckIn  extends UserCheckInModel{
    /**
     * 改变回调状态
     * @param $id
     */
    public function changeCallBackStatus($id){
        $model = self::get($id);
        $result = $model->allowField(true)->save([ 'callback_status' => 1]);
        if (!$result){
            echo json_encode(['code' => 1, 'msg' => 'The Data save faild.']);
        } else{
            echo json_encode(['code' => 0, 'msg' => 'The Data save success!']);
        }
    }
}