<?php

namespace app\store\controller\market;

use app\store\controller\Controller;
use app\store\model\Setting as SettingModel;
use app\store\model\recharge\Plan as PlanModel;
use think\Cache;

/**
 * 营销设置-自由合伙人
 * Class Basic
 * @package app\store\controller
 */
class Partner extends Controller {
    /**
     * 权益设置
     * @return array|bool|mixed
     * @throws \think\exception\DbException
     */
    public function setting() {
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem('partner');

            $planId   = $values['condition']['recharge_id'];
            $planItem = PlanModel::detail($planId);

            $platformPlanId   = isset($values['rights']['platform']['condition']['recharge_id']) ? $values['rights']['platform']['condition']['recharge_id'] : 1;
            $planItemPlatform = PlanModel::detail($platformPlanId);

            return $this->fetch('setting', [
                'planList'         => [['plan_id' => $planItem->plan_id, 'plan_name' => $planItem->plan_name]],
                'planListPlatform' => [['plan_id' => $planItemPlatform->plan_id, 'plan_name' => $planItemPlatform->plan_name]],
                'values' => $values
            ]);
        }
        $model = new SettingModel;
        if ($model->edit('partner', $this->postData('partner'))) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 模式说明
     */
     public function describe() {
         if (!$this->request->isAjax()) {
             $values = SettingModel::getItem('partner');

             return $this->fetch('describe', [
                 'values' => $values
             ]);
         }
         $model = new SettingModel;
         if ($model->edit('partner', $this->postData('partner'))) {
             return $this->renderSuccess('操作成功');
         }
         return $this->renderError($model->getError() ?: '操作失败');
     }
}
