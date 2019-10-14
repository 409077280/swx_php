<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Withdraw as WithdrawModel;
use app\common\model\User as UserModel;

/**
 * 用户提现
 * Class service
 * @package app\api\controller\user\order
 */
class Withdraw extends Controller {
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize() {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }


    public function submit($data) {
        $formData = json_decode(htmlspecialchars_decode($data), true);
        // 获取数据中心用户数据
        $userBonusData = UserModel::getContributionAndBonusFromDbcenter($this->user['user_id']);
        $money = floatval($formData['money']);
        if(self::_getFloatLength($money) > 2) {
            return $this->renderError('金额输入错误');
        }
        // 用户中心可提现金额
        $canBonus = floatval($userBonusData['can_bonus']);
        // 待审核提现
        $waitingMoney = floatval((new WithdrawModel)->getWaitingAmount($this->user['user_id'], '10'));

        if(($money + $waitingMoney) > $canBonus)
        	return $this->renderError($model->getError() ?: '超出可提现额度');

        $model = new WithdrawModel;
        if ($model->submit($this->user['user_id'], $formData)) {
            $userBonusData['can_bonus'] = $canBonus - $money;

            return $this->renderSuccess($userBonusData, '申请提现成功');
        }
        return $this->renderError($model->getError() ?: '提交失败');
    }

    /**
     * 提现列表
     * @param $dataType
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($dataType = -1, $limit = 15) {
        $model = new WithdrawModel;
        $list = $model->getList($this->user['user_id'], $dataType, $limit);
        return $this->renderSuccess(compact('list'));
    }

    private static function _getFloatLength($num) {
        $count = 0;
        $temp = explode ( '.', $num );
        if (sizeof ( $temp ) > 1) {
            $decimal = end ( $temp );
            $count = strlen ( $decimal );
        }
        return $count;
    }
}
