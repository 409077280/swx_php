<?php

namespace app\api\controller;

use app\api\model\Bonus as BonusModel;
use app\common\exception\BaseException;

/**
 * 分红控制器
 * Class Bonus
 * @package app\api\controller
 */
class Bonus extends Controller {
    /**
     * 分红列表
     * @param $category_id
     * @param $search
     * @param $sortType
     * @param $sortPrice
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($user_id, $dataType = '', $page, $limit = 15, $home = 0) {
        if(!$user_id)
            throw new BaseException(['code' => -1, 'msg' => '缺少必要的参数：user_id']);

        $model = new BonusModel;
        $list = $model->getList($user_id, $dataType, $page, $limit, $home);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取分红详情
     */
    public function detail($user_id, $order_id = 0, $goods_id = 0, $goods_sku_id = 0) {
        if(!$user_id)
            throw new BaseException(['code' => -1, 'msg' => '缺少必要的参数：user_id']);

        $detail = BonusModel::detail($user_id, $order_id, $goods_id, $goods_sku_id);

        return $this->renderSuccess(compact('detail'));
    }
}
