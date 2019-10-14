<?php
namespace app\api\model;

use app\common\library\dbcenter\Util;
use app\common\model\UserCheckIn as UserCheckInModel;
use app\api\model\Setting as SettingModel;
use think\Config;

class UserCheckIn extends UserCheckInModel {

    /**
     * 开始签到
     * @param $userId
     * @param $wxAppId
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function checkIn($userId, $wxAppId, &$contribution){
        // 获取随机贡献
        $contribution = $this->randomContribution($wxAppId);
        $bonus = $contribution;
        $data = [
            'user_id' => $userId,
            'contribution' => $contribution,
            'bonus' => $bonus,
            'callback_status' => 0,
            'wxapp_id' => $wxAppId,
        ];
        $this->startTrans();
        try{
            $this->save($data);
            $response = $this->requestDbCenter($userId, $this->id, $contribution);
            if (!$response){
                $this->rollback();
                return false;
            }else{
                $this->commit();
                return true;
            }
        }catch (\Exception $e){
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 检查能否签到
     * @param $userId
     * @return bool
     */
    public function verifyCheckInAble($userId){
        // 检查今天是否已经签到
        $today = date('Y-m-d', time());
        // 开始时间 今天 00:00:00
        $startTime = strtotime($today);
        // 结束时间 今天 23:59:59
        $endTime = strtotime('tomorrow') - 1;
        try{
            $count = $this->where(['user_id' => $userId])
                ->where('create_time','between', [$startTime, $endTime])
                ->count();
            if ($count == 0){
                return true;
            }
            $this->error = '您已经签到了';
            return false;
        } catch (\Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 获取随机贡献
     * @param $wxAppId
     * @return int
     */
    private function randomContribution($wxAppId){
        $setting = SettingModel::getItem('checkin', $wxAppId);
        $contribution = $setting['contribution'];
        if($contribution) {
            $len = count($contribution);
            $idx = rand(0, $len - 1);
            return $contribution[$idx];
        } else {
            return 1;
        }
    }

    /** 签到所得上报数据中心
     * @param $userId
     * @param $wxAppId
     * @param $contribution
     * @return bool
     */
    private function requestDbCenter($userId, $recordId, $contribution){
        $attach = "userCheckIn|{$recordId}|{$userId}";
        $data = [
            'userCode'     => $userId,
            'contribution' => $contribution,
            'attach'       => $attach,
            'merchantCode' => Config::get('dbcenter.merchantCode'),
            'callbackUrl'  => Config::get('dbcenter.callbackUrl').'?m=user',
        ];
        $sign         = (new Util)->makePaySign($data);
        $data['sign'] = $sign;
        $return       = Util::request(Config::get('dbcenter.apiUrl') . '/dc/market/signIn', $data);
        $item         = json_decode($return, true);
        if($item['code'] == '0000') {
            return true;
        }
        $this->error = $item['msg'];
        return false;
    }

}