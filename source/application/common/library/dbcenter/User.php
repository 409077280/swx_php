<?php
/**
 * 数据中心用户接口(内部)
 *
 * @package app\api\controller\dbcenter
 * @author Jerry <tanping@dedeshijie.com>
 * @version 1.0
 * @created 2019-03-01
 */

namespace app\common\library\dbcenter;

use app\common\library\dbcenter\Util;
use app\common\model\Setting as SettingModel;
use app\common\model\Wxapp as WxappModel;
use think\Config;
use think\Cache;

class User {
    /**
     * 获取用户信息
     * @param mixed $user_id 用户id
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function getUserInfo($user_id) {
        $data['merchantCode'] = Config::get('dbcenter.merchantCode');
        $apiUrl = Config::get('dbcenter.apiUrl') . 'dc/account/query/user';
        if(is_array($user_id)) {
            $data['userCodes'] = implode(',', $user_id);
            $apiUrl = Config::get('dbcenter.apiUrl') . 'dc/account/query/userList';
        } else {
            $data['userCode']  = $user_id;
        }

        $sign = (new Util)->makePaySign($data);
        $data['sign'] = $sign;
        $return = Util::request($apiUrl, $data);
        $item   = json_decode($return, true);

        if($item['code'] == '0000') {
            if(isset($item['accountQueryList']))
                return $item['accountQueryList'];
            else
                return $item;
        } else {
            return false;
        }
    }

    /**
     * 新用户注册
     */
    public function register($userId, $refereeId) {
        $setting = SettingModel::getItem('newbie');
        if($setting['register']['is_open']) {
            // 处理随机贡献和红包
            $setting = self::_randomContribution($setting);
        }

        // 保存缓存
        Cache::set('register_setting_' . $userId, $setting);

        $registerSetting = $setting['register'];

        $attach = "isOpen:{$registerSetting['is_open']}|registerContribution:{$registerSetting['self']['contribution']}|inviteContribution:{$registerSetting['referee']['contribution']}|registerDividend:{$registerSetting['self']['bonus']}|inviteDividend:{$registerSetting['referee']['bonus']}";
        $data = [
            'merchantCode' => Config::get('dbcenter.merchantCode'),
            'userCode'     => $userId,
            'invitedBy'    => $refereeId,
            'attach'       => $attach
        ];

        $sign         = (new Util)->makePaySign($data);
        $data['sign'] = $sign;
        $return       = Util::request(Config::get('dbcenter.apiUrl') . 'dc/register/user', $data);
        $item         = json_decode($return, true);

        if($item['code'] == '0000') {
            return json_encode(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
        } else {
            return json_encode(['return_code' => 'FAIL', 'return_msg' => $item['msg']]);
        }
    }

    /**
     * 处理随机贡献和红包
     */
    private static function _randomContribution($setting) {
        $selfContribution    = isset($setting['register']['self']['contribution']) ? trim($setting['register']['self']['contribution']) : null;
        $refereeContribution = isset($setting['register']['referee']['contribution']) ? trim($setting['register']['referee']['contribution']) : null;
        $bigbonus            = isset($setting['register']['bigbonus']) ? trim($setting['register']['bigbonus']) : null;

        if($selfContribution) {
            $random = self::_random($selfContribution, $bigbonus);
            $setting['register']['self']['contribution'] = $random;
            $setting['register']['self']['bonus']        = $random;
            $setting['register']['referee']['contribution'] = $random;
            $setting['register']['referee']['bonus']        = $random;
        }

        return $setting;
    }

    /**
     * 获取随机贡献
     */
    private static function _random($nums, $bigbonus) {
        $big  = self::_getBigbonus($bigbonus);
        if($big)
            return $big;

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

    /**
     * 获取大红包贡献
     */
    private static function _getBigbonus($bigbonus) {
        $wxappConfig = WxappModel::getWxappCache();
        $wxappId     = ($wxappConfig && isset($wxappConfig['wxapp_id']) && $wxappConfig['wxapp_id']) ? $wxappConfig['wxapp_id'] : '10001';
        $userCache   = Cache::get('currentUserIndex_' . $wxappId);
        $currentUserIndex = $userCache ? (intval($userCache) + 1) : 1;
        Cache::set('currentUserIndex_' . $wxappId, $currentUserIndex);

        $bigbonusItems = [];
        if($bigbonus) {
            $bigbonus = str_replace('，', ',', $bigbonus);
            $bigbonusArr = explode(',', $bigbonus);
            if($bigbonusArr) {
                foreach($bigbonusArr as $bigbonusItem) {
                    $bonusArr = explode('|', $bigbonusItem);
                    if($bonusArr) {
                        $bonus= $bonusArr[0];
                        $rate = $bonusArr[1];
                        list($rateNum, $total) = self::_dealRate($rate);
                        // 生成指定区间、指定数量的随机用户索引
                        $randoms = self::_makeRandom($rateNum, $total, $currentUserIndex, $wxappId, $bonus);
                        if(in_array($currentUserIndex, $randoms)) {
                            array_push($bigbonusItems, $bonus);
                        }
                    }
                }
            }
        }

        if($bigbonusItems)
            return max($bigbonusItems);

        return 0;
    }

    /**
     * 产生指定数量、指定区间的随机数
     */
    private static function _makeRandom($num, $step, $index, $wxappId, $bonus) {
        $randoms = [];
        $cacheIndex   = 'bigbonus_' . $wxappId . '_' . $bonus;
        $randomsCache = Cache::get($cacheIndex) ? Cache::get($cacheIndex) : [];

        $min = ($index / $step) > 1 ? intval($index / $step) * $step : 1;
        $max = $min == 1 ? $step : ($min + $step);
        $start = Cache::get('numStart_' . $wxappId . '_' . $bonus) ? Cache::get('numStart_' . $wxappId . '_' . $bonus) : $step;
        if($index > $start) {
            for($i = 0; $i < $num; $i++) {
                array_push($randoms, rand($min, $max));
            }
            sort($randoms);
            Cache::set($cacheIndex, $randoms);
            Cache::set('numStart_' . $wxappId . '_' . $bonus, $max);
            return $randoms;
        } else {
            if(!$randomsCache) {
                for($i = 0; $i < $num; $i++) {
                    array_push($randoms, rand($min, $max));
                }
                sort($randoms);
                Cache::set($cacheIndex, $randoms);
                Cache::set('numStart_' . $wxappId . '_' . $bonus, $max);
                return $randoms;
            }

            return $randomsCache;
        }
    }

    private static function _dealRate($rate) {
        for($i = 0; $i <= 10; $i++) {
            if($rate * pow(10, $i) >= 1) {
                return [$rate * pow(10, $i), pow(10, $i)];
            }
        }
    }

    public function test() {
        // self::clear();
        $setting = SettingModel::getItem('newbie');
        // 处理随机贡献和红包
        $setting = self::_randomContribution($setting);
        echo '当前用户ID：' . Cache::get('currentUserIndex_10001');
        echo '碰撞贡献8.88';
        print_r(Cache::get('bigbonus_10001_8.88'));
        echo '碰撞贡献88.88';
        print_r(Cache::get('bigbonus_10001_88.88'));
        print_r($setting);exit;
    }

    private static function clear() {
        $wxappId = 10001;
        Cache::set('currentUserIndex_' . $wxappId, 0);
        Cache::set('numStart_' . $wxappId . '_8.88', 0);
        Cache::set('numStart_' . $wxappId . '_88.88', 0);
        Cache::set('bigbonus_' . $wxappId . '_8.88', '');
        Cache::set('bigbonus_' . $wxappId . '_88.88', '');
    }

    /**
     * 获取用户拆线数据
     */
    public static function getChartData($userId) {
        $params = [
            'merchantCode' => Config::get('dbcenter.merchantCode'),
            'userCode' => $userId
        ];
        $sign = (new Util)->makePaySign($params);
        $params['sign'] = $sign;

        $response = Util::request(Config::get('dbcenter.apiUrl') . 'dc/account/chartData', $params);
        $data     = json_decode($response, true);
        if(isset($data['code']) && ($data['code'] == '0000')) {
            $contributionData = $bonusData = $xAxis = [];
            $timeUnit = (isset($data['timeUnit']) && $data['timeUnit']) ? $data['timeUnit'] : 'd';
            foreach($data['contribution'] as $contribution) {
                $time = $contribution['time'];
                if($timeUnit == 'm')
                    $time = date('H:i', $time);
                elseif($timeUnit == 'h')
                    $time = date('H:i', $time);
                else
                    $time = date('m-d', $time);

                array_push($xAxis, $time);
                array_push($contributionData, $contribution['value']);
            }
            foreach($data['dividend'] as $bonus) {
                array_push($bonusData, $bonus['value']);
            }
            return [$contributionData, $bonusData, $data['personNum'], $data['dividendNum'], $data['totalContribution'], $data['totalHistoryDividend'], $xAxis];
        } else {
            return false;
        }
    }
}
