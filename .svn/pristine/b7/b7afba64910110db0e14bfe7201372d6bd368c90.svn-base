<?php

namespace app\store\model;

use app\common\model\User as UserModel;
use app\store\model\user\BalanceLog as BalanceLogModel;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\store\model\UserAddress as UserAddressModel;
use think\Exception;

/**
 * 用户模型
 * Class User
 * @package app\store\model
 */
class User extends UserModel
{
    /**
     * 获取当前用户总数
     * @param null $day
     * @return int|string
     * @throws \think\Exception
     */
    public function getUserTotal($day = null)
    {
        if (!is_null($day)) {
            $startTime = strtotime($day);
            $this->where('create_time', '>=', $startTime)
                ->where('create_time', '<', $startTime + 86400);
        }
        return $this->where('is_delete', '=', '0')->count();
    }

    /**
     * 获取用户列表
     * @param string $nickName
     * @param int $gender
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($keywords = '', $gender = -1, $all = false)
    {
        // 检索条件：微信昵称
        !empty($keywords) && $this->where("nickName like '%{$keywords}%' or mobile like '%{$keywords}%'");
        // 检索条件：性别
        if ($gender !== '' && $gender > -1) {
            $this->where('gender', '=', (int)$gender);
        }
        if(!$all)
            $this->where('is_delete', '=', '0');

        return $this->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     *恢复
     * @return false|int
     */
    public function setRecovery()
    {
        return $this->save(['is_delete' => 0]);
    }

    /**
     * 用户充值
     * @param string $storeUserName 当前操作人用户名
     * @param $data
     * @return bool
     */
    public function recharge($storeUserName, $data)
    {
        if (!isset($data['money']) || $data['money'] === '' || $data['money'] < 0) {
            $this->error = '请输入正确的金额';
            return false;
        }
        // 判断充值方式，计算最终金额
        if ($data['mode'] === 'inc') {
            $diffMoney = $data['money'];
        } elseif ($data['mode'] === 'dec') {
            $diffMoney = -$data['money'];
        } else {
            $diffMoney = $data['money'] - $this['balance'];
        }
        // 更新记录
        $this->transaction(function () use ($storeUserName, $data, $diffMoney) {
            // 更新账户余额
            $this->setInc('balance', $diffMoney);
            // 新增余额变动记录
            BalanceLogModel::add(SceneEnum::ADMIN, [
                'user_id' => $this['user_id'],
                'money' => $diffMoney,
                'remark' => $data['remark'],
            ], [$storeUserName]);
        });
        return true;
    }

    /**
     * 获取用户地址信息
     */
    public function getAddress($addressId) {
        $addressInfo = UserAddressModel::get($addressId);

        if($addressInfo) {
            return [
                'province' => Region::getNameById($addressInfo['province_id']),
                'city' => Region::getNameById($addressInfo['city_id']),
                'region' => $addressInfo['region_id'] == 0 ? $addressInfo['district']
                    : Region::getNameById($addressInfo['region_id']),
                'address' => $addressInfo['detail'],
                'name'    => $addressInfo['name'],
                'mobile'  => $addressInfo['phone'],
            ];
        }

        return [];
    }


    /**
     * 根据用户名获取用户 Id
     * @param $nickName
     * @return array|bool
     */
    public function getUserIdsByNickName($nickName){
        $nickName = trim($nickName);
        if (!empty($nickName)){
            $this->where('nickName', 'like', '%'.$nickName.'%');
        }else{
            $this->error = '用户名不能为空';
            return false;
        }
        try{
            $data = $this->select()->toArray();
            $list = [];
            if (!empty($data)){
                foreach ($data as $value){
                    array_push($list, $value['user_id']);
                }
            }
            return $list;
        }catch (\Exception $exception){
            $this->error = $exception->getMessage();
            return false;
        }
    }

    /**
     * 后台用户列表
     * @param $provinceId
     * @param $cityId
     * @param $gender
     * @param $keywords
     * @param $limit
     * @param $page
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getUserJonAddress($provinceId, $cityId, $gender, $keywords, $limit, $page){
        // 检查省份
        if (!empty($provinceId)){
            $provinceName = Region::getNameById((int)$provinceId);
            $provinceName = mb_substr($provinceName, 0, 2, 'utf-8');
            $this->where("user.province like '{$provinceName}%' OR user.location_province like '{$provinceName}%' OR addr.province_id = {$provinceId}");
        }
        // 检查城市
        if (!empty($provinceName) && !empty($cityId)){
            $cityName = Region::getNameById((int)$cityId);
            $cityName = mb_substr($cityName, 0, 2, 'utf-8');
            $this->where("user.city like '{$cityName}%' OR user.location_city like '{$cityName}%' OR addr.city_id = {$cityId}");
        }
        // 检索条件：性别
        if ($gender !== '' && (int)$gender > -1) {
            $this->where('user.gender', '=', (int)$gender);
        }
        // 检索条件：昵称、收货人、绑定手机号、收货电话
        if (!empty($keywords)){
            $this->where('user.nickName|addr.name|user.mobile|addr.phone', 'like', '%' . trim($keywords) . '%');
        }
        $data = $this->alias('user')
            ->field([
                'user.*',
                'addr.name',
                'addr.phone',
                'addr.detail',
                '(SELECT name from yoshop_region WHERE id = addr.province_id) as receiveProvince',
                '(SELECT name from yoshop_region WHERE id = addr.city_id) as receiveCity',
                '(SELECT name from yoshop_region WHERE id = addr.region_id) as receiveRegion',
                ])
            ->join('yoshop_user_address addr', 'user.address_id = addr.address_id AND user.user_id = addr.user_id', 'LEFT')
            ->order(['user.user_id' => 'desc'])
            ->paginate($limit, false, ['page' => $page]);
        //var_dump($this->getLastSql());
        return $data;
    }
}
