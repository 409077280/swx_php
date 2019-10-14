<?php

namespace app\store\controller\user;

use app\store\controller\Controller;
use app\store\model\Withdraw as WithdrawModel;
use app\store\model\User as UserModel;

/**
 * 分销商提现申请
 * Class Setting
 * @package app\store\controller\apps\dealer
 */
class Withdraw extends Controller {
    /**
     * 提现记录列表
     * @param int $user_id
     * @param int $apply_status
     * @param int $pay_type
     * @param string $search
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($user_id = null, $apply_status = -1, $pay_type = -1, $search = '')
    {
        $model = new WithdrawModel;
        return $this->fetch('index', [
            'list' => $model->getList($user_id, $apply_status, $pay_type, $search)
        ]);
    }

    public function withdrawfinishpage($user_id = ''){
        $model = new WithdrawModel;
        return $this->fetch('withdrawFinishPage', [
            'list' => $model->getFinishList($user_id),
        ]);
    }

    /**
     * 提现审核
     * @param $id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function submit($id)
    {
        $model = WithdrawModel::detail($id);
        if ($model->submit($this->postData('withdraw'))) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 确认打款
     * @param $id
     * @return array
     * @throws \think\exception\DbException
     */
    public function money($id)
    {
        $model = WithdrawModel::detail($id);
        if ($model->money()) {
            // 上报数据中心
            $model->report($model->user_id, $model->sn, $model->money);

            // 修改用户提现金额
            $userModel = UserModel::detail($model->user_id);
            $userModel->setInc('withdraw', $model->money);

            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}
