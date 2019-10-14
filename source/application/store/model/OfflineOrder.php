<?php
namespace app\store\model;

use app\common\model\OfflineOrder as OfflineOrderModel;
use think\Exception;

/**
 * 线下支付记录
 * Class OfflineOrder
 * @package app\store\model
 */
class OfflineOrder extends OfflineOrderModel
{
    /**
     * 通过订单号获取支付记录
     * @param $pay_status
     * @param $start_time
     * @param $end_time
     * @param $search_value
     * @param $page
     * @param $limit
     * @return bool|\think\Paginator
     */
    public function getListByOrderNumber($pay_status, $start_time, $end_time, $search_value, $page, $limit){
        $this->alias('offline');
        if (!empty($pay_status) || (int)$pay_status != 0){
            $this->where('offline.pay_status', '=', (int)$pay_status);
        }
        if (!empty($start_time) && !empty($end_time)){
            if ((int)$start_time <= (int)$end_time){
                $this->where('offline.create_time', 'between', [(int)$start_time, (int)$end_time + 3600 * 24]);
            }else{
                $this->error = '开始时间不能大于结束时间';
                return false;
            }
        }
        $search_value = trim($search_value);
        if (!empty($search_value)){
            $this->where('offline.order_no', 'like', '%'. $search_value. '%');
        }
        try{
            return $this->join('yoshop_user user','offline.user_id = user.user_id', 'LEFT')
                ->field('offline.*, user.nickName, user.avatarUrl')
                ->order('offline.order_id', 'desc')
                ->paginate($limit, false, [
                    'page' => $page,
                ]);

        }catch (\Exception $exception){
            $this->error = $exception->getMessage();
            return false;
        }
    }


    /**
     * 通过用户id搜索线下交易记录
     * @param $pay_status
     * @param $start_time
     * @param $end_time
     * @param $userIds
     * @param $page
     * @param $limit
     * @return bool|\think\Paginator
     */

    public function getListByUserIds($pay_status, $start_time, $end_time, $userIds, $page, $limit){
        $this->alias('offline');
        if (!empty($pay_status) || (int)$pay_status != 0){
            $this->where('offline.pay_status', '=', (int)$pay_status);
        }
        if (!empty($start_time) && !empty($end_time)){
            if ((int)$start_time <= (int)$end_time){
                $this->where('offline.create_time', 'between', [(int)$start_time, (int)$end_time + 3600 * 24]);
            }else{
                $this->error = '开始时间不能大于结束时间';
                return false;
            }
        }
        $this->whereIn('offline.user_id', $userIds);
        try{
            return $this->join('yoshop_user user','offline.user_id = user.user_id', 'LEFT')
                ->field('offline.*, user.nickName, user.avatarUrl')
                ->order('offline.order_id', 'desc')
                ->paginate($limit, false, [
                    'page' => $page,
                ]);
        }catch (\Exception $exception){
            var_dump($this->getLastSql());
            $this->error = $exception->getMessage();
            return false;
        }
    }
}