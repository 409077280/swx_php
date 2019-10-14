<?php

namespace app\api\controller\partner;

use app\api\controller\Controller;
use app\api\model\User as UserModel;
use app\api\model\recharge\Order as OrderModel;

/**
 * 合伙人主页
 * Class Index
 * @package app\api\controller\partner
 */
class Index extends Controller {
    private $user;

    public function _initialize() {
        parent::_initialize();
        $this->user = $this->getUser();
    }
    /**
     * 获取当前合伙人信息
     */
     public function detail() {
         // 当前用户
         $userId = (int) $this->user['user_id'];
         // 邀请用户数
         $inviteNum      = (new UserModel)->getInviteCount($userId);
         // 充值总金额
         $rechargeAmount = (new OrderModel)->getTotalAmount($userId);

         return $this->renderSuccess([
             'inviteUserNum'  => $inviteNum,
             'rechargeAmount' => number_format($rechargeAmount, 2)
         ]);
     }

     /**
      * 邀请用户列表
      */
      public function user_lists() {
          $userId  = (int) $this->user['user_id'];
          $list    = (new UserModel)->getInviteList($userId);

          return $this->renderSuccess(compact('list'));
      }
}
