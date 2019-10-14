<?php

namespace app\store\controller;

use app\store\model\User as UserModel;
use app\common\library\dbcenter\User as DBCenterUser;
use app\store\model\UserAddress;

/**
 * 用户管理
 * Class User
 * @package app\store\controller
 */
class User extends Controller
{
    /**
     * 用户列表
     * @param string $nickName
     * @param null $gender
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        return $this->fetch('index');
    }

    /**
     * 删除用户
     * @param $user_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($user_id)
    {
        // 用户详情
        $model = UserModel::detail($user_id, true);
        if ($model->setDelete()) {
            return json($this->renderJson(0, '删除成功', '', []));
        }
        return json($this->renderJson(1, "删除失败", '', []));
    }

    /**
     * 恢复用户
     * @param $user_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function recovery($user_id)
    {
        // 用户详情
        $model = UserModel::detail($user_id, true);
        if ($model->setRecovery()) {
            return json($this->renderJson(0, '恢复成功', '', []));
        }
        return json($this->renderJson(1, "恢复失败", '', []));
    }

    /**
     * 用户充值
     * @param $user_id
     * @return array|bool
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function recharge($user_id)
    {
        // 用户详情
        $model = UserModel::detail($user_id);
        if ($model->recharge($this->store['user']['user_name'], $this->postData('recharge'))) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     *  用户充值（新）
     */
    public function recharged($user_id)
    {
        // 用户详情
        $model = UserModel::detail($user_id);
        if ($model->recharge($this->store['user']['user_name'], $this->request->put())) {
            return json($this->renderJson(0, '操作成功', '', []));
        }
        return json($this->renderJson(1, "操作失败", '', []));
    }

    /**
     * 获取用户id
     */
    private static function _getUserIds($items) {
        $userIds = [];
        if($items) {
            foreach($items as $item) {
                array_push($userIds, $item['user_id']);
            }
        }

        return $userIds;
    }


    /**
     * 获取用户列表
     * @param $provinceId
     * @param $cityId
     * @param $gender
     * @param $keywords
     * @param $limit
     * @param $page
     * @return \think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function get_user_list($provinceId, $cityId, $gender, $keywords, $limit, $page){
        $model = new UserModel;
        $list = $model->getUserJonAddress($provinceId, $cityId, $gender, $keywords, $limit, $page);
        $totalIds = self::_getUserIds($list);
        $items = (new DBCenterUser)->getUserInfo($totalIds);
        foreach($list as &$li) {
            if($items) {
                foreach($items as $item) {
                    if($item['userCode'] == $li['user_id']) {
                        $li['bonus']        = isset($item['totalHistoryDividend']) ? $item['totalHistoryDividend'] : 0;
                        $li['contribution'] = $item['totalContribution'];
                        $li['totalContribution'] = $item['totalHistoryContribution'];
                        $li['totalDividend'] = $item['totalDividend'];
                        $li['withdraw']          = isset($item['cash']) ? $item['cash'] : 0;
                    }
                }
            }
        }
        return json($this->renderJson(0, "", '', $list));
    }
}
