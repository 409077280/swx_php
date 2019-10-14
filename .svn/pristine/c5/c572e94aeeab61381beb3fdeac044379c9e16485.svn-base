<?php

namespace app\store\controller;
use app\store\model\OfflineOrder as OfflineOrderModel;
use app\store\model\User as UserModel;

class Offlineorder extends Controller
{
    public function index(){
        $list = [
            'title' => '所有订单',
            'pay_status' => '0',
        ];
        return $this->fetch('index', $list);
    }

    public function list_nopay(){
        $list = [
            'title' => '待付款',
            'pay_status' => '10',
        ];
        return $this->fetch('index', $list);
    }

    public function list_paid(){
        $list = [
            'title' => '已付款',
            'pay_status' => '20',
        ];
        return $this->fetch('index', $list);
    }


    /**
     * 获取对应条件的线下交易记录
     * @param $pay_status
     * @param $start_time
     * @param $end_time
     * @param $search_value
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function get_list($pay_status, $start_time, $end_time, $search_type, $search_value, $page, $limit){
        $model = new OfflineOrderModel;
        if (empty($search_type)){
            $list = $model->getListByOrderNumber($pay_status, $start_time, $end_time, '', $page, $limit);
        } else{
            switch ($search_type){
                // 若搜索订单
                case 'order':
                    $list = $model->getListByOrderNumber($pay_status, $start_time, $end_time, $search_value, $page, $limit);
                    break;
                // 若搜索用户
                case 'user' :
                    $userModel = new UserModel;
                    $userIds = $userModel->getUserIdsByNickName($search_value);
                    $errMsg = $userModel->getError();
                    if ($userIds == false && !empty($errMsg)){
                        return json($this->renderJson(1, $errMsg, "", []));
                    }
                    $list = $model->getListByUserIds($pay_status, $start_time, $end_time, $userIds, $page, $limit);
                    break;
                default:
                    return json($this->renderJson(1, "搜索类型错误", "", []));
            }
        }
        $errMsg = $model->getError();
        if ($list == false && !empty($errMsg)){
            return json($this->renderJson(1, $errMsg, "", []));
        }
        return json($this->renderJson(0, "", "", $list));
    }
}