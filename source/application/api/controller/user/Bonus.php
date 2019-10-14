<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\common\model\User as UserModel;
use app\api\model\Setting as SettingModel;
use app\api\model\Withdraw as WithdrawModel;

/**
 * 分销中心
 * Class Dealer
 * @package app\api\controller\user
 */
class Bonus extends Controller {
    /**
     * 分销商提现信息
     * @return array
     */
    public function withdraw() {
        $user          = $this->getUser();
        $userBonusData = UserModel::getContributionAndBonusFromDbcenter($user['user_id']);
        $canBonus      = floatval($userBonusData['can_bonus']);
        $waitingWithdrawAmount = (new WithdrawModel)->getWaitingAmount($user['user_id']);
        if($waitingWithdrawAmount)
            $canBonus -= $waitingWithdrawAmount;

        $canBonus = $canBonus > 0 ? $canBonus : 0;

        $minMoney      = SettingModel::getItem('withdraw')['min_money'];
        $payType       = SettingModel::getItem('withdraw')['pay_type'];
        return $this->renderSuccess([
            'dealer' => ['money' => $canBonus],
            'settlement' => ['min_money' => $minMoney, 'pay_type' => $payType],
            'words' => [
                'withdraw_apply' => [
                    'words' => [
                        'capital' => ['value' => '可提现分红'],
                        'money'   => ['value' => '提现分红'],
                        'money_placeholder'   => ['value' => '请输入要提取的分红'],
                        'min_money'   => ['value' => '最低提现'],
                        'submit'   => ['value' => '提交申请'],
                    ],
                    'title' => ['value' => '提现申请']
                ]
            ],
        ]);
    }
}
