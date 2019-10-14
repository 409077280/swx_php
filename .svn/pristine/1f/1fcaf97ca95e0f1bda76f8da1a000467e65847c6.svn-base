<?php

namespace app\api\model;


use app\common\library\dbcenter\Util;
use app\common\model\Withdraw as WithdrawModel;
use app\api\model\User as UserModel;
use app\api\model\user\BalanceLog as BalanceLogModel;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use think\Config;
use think\Exception;
use think\exception\HttpResponseException;
use think\exception\PDOException;

/**
 * 提现模型
 * Class Order
 * @package app\api\model
 */
class Withdraw extends WithdrawModel {
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id'
    ];

    /**
     * 新增提现记录
     * @param $user_id
     * @param $order
     * @param string $remark
     * @return false|int
     */
    public function submit($user_id, $data) {
        // 数据验证
        $this->validation($data);
        // 新增申请记录
        if ($data['pay_type'] == '10'){
            try{
                $sn = $this->orderNo();
                $this->save(array_merge($data, [
                    'user_id' => $user_id,
                    'sn'      => $sn,
                    'apply_status' => 20,   //已通过
                    'wxapp_id'  => self::$wxapp_id,
                    'real_name' => '',  //TODO:通过user_id获取用户名称
                ]));
                // 记录日志
                $balanceLogModel = new BalanceLogModel;
                $balanceLogModel->save([
                    'user_id'  => $user_id,
                    'scene'    => SceneEnum::WITHDRAW,
                    'money'    => $data['money'],
                    'describe' => '分红提现：' . $sn,
                    'remark'   => $data['remark'],
                    'wxapp_id' => self::$wxapp_id
                ]);

                if ($this->updateDbCenter($this->id)){
                    return true;
                }else{
                    return false;
                }
            } catch (\Exception $e){
                $this->error = $e;
                return false;
            }
        } else {
            $this->save(array_merge($data, [
                'user_id' => $user_id,
                'sn'      => $this->orderNo(),
                'apply_status' => 10,
                'wxapp_id'  => self::$wxapp_id,
                'real_name' => isset($data['alipay_name']) ? $data['alipay_name'] : (isset($data['bank_account']) ? $data['bank_account'] : '')
            ]));
            return true;
        }
    }

    /**
     * 分红提现至余额
     * @param $id
     * @return bool
     * @throws PDOException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function updateDbCenter($id)
    {
        $model = (new static)->find($id);
        $updateApply = $this->allowField(true)->save([
            'apply_status' => 40,
            'audit_time' => time(),
        ]);
        if ($updateApply != false) {
            $userModel = UserModel::detail($model->user_id);
            $userModel->startTrans();
            try {
                // 上报数据中心
                $ret = json_decode($model->report($model->user_id, $model->sn, $model->money),true);
                if ($ret['code'] != '0000'){
                    return false;
                }
                // 修改用户提现金额
                $userModel->setInc('withdraw', $model->money);
                $userModel->setInc('balance', $model->money);
                $userModel->commit();
                return true;
            } catch (\Exception $e) {
                $this->error = $e;
                $userModel->rollback();
                return false;
            }
        }
        return false;
    }

    /**
     * 上报数据中心
     */
    public function report($userId, $sn, $money) {
        $data = [
            'merchantCode' => Config::get('dbcenter.merchantCode'),
            'userCode'     => !$userId ? '' : $userId,
            'reduceBonus'  => $money * 100,
            'cashId'       => $sn
        ];
        $sign = (new Util)->makePaySign($data);
        $data['sign'] = $sign;
        $ret = Util::request(Config::get('dbcenter.apiUrl') . 'dc/assets/cash', $data);
        return $ret;
    }

    /**
     * 用户中心提现列表
     * @param $user_id
     * @param string $type
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $dataType, $limit) {
        // 筛选条件
        $filter = [];
        $dataType > 0 && $filter['apply_status'] = $dataType;

        return $this->where('user_id', '=', $user_id)
            ->where($filter)
            ->order(['id' => 'desc'])
            ->paginate($limit, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 获取当前用户正在提现的金额
     */
    public function getWaitingAmount($userId, $applyStatus = '10,20') {
        $amount = 0;
        $items = $this->where('user_id', '=', $userId)
            ->where('apply_status', 'in', $applyStatus)
            ->select();
        if($items) {
            foreach($items as $item) {
                $amount += floatval($item['money']);
            }
        }

        return $amount;
    }

    /**
     * 数据验证
     * @param $dealer
     * @param $data
     * @throws BaseException
     */
    // private function validation($dealer, $data)
    private function validation($data)
    {
        // 结算设置
        // $settlement = Setting::getItem('settlement');
        // 最低提现佣金
        if ($data['money'] <= 0) {
            throw new BaseException(['msg' => '提现金额不正确']);
        }
        // if ($dealer['money'] <= 0) {
        //     throw new BaseException(['msg' => '当前用户没有可提现佣金']);
        // }
        // if ($data['money'] > $dealer['money']) {
        //     throw new BaseException(['msg' => '提现金额不能大于可提现佣金']);
        // }
        // if ($data['money'] < $settlement['min_money']) {
        //     throw new BaseException(['msg' => '最低提现金额为' . $settlement['min_money']]);
        // }
        // if (!in_array($data['pay_type'], $settlement['pay_type'])) {
        //     throw new BaseException(['msg' => '提现方式不正确']);
        // }
        if ($data['pay_type'] == '20') {
            if (empty($data['alipay_name']) || empty($data['alipay_account'])) {
                throw new BaseException(['msg' => '请补全提现信息']);
            }
        } elseif ($data['pay_type'] == '30') {
            if (empty($data['bank_name']) || empty($data['bank_account']) || empty($data['bank_card'])) {
                throw new BaseException(['msg' => '请补全提现信息']);
            }
        }
    }
}
