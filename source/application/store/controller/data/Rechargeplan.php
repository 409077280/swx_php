<?php

namespace app\store\controller\data;

use app\store\controller\Controller;
use app\store\model\recharge\Plan as PlanModel;

/**
 * 充值套餐数据控制器
 * Class Goods
 * @package app\store\controller\data
 */
class Rechargeplan extends Controller
{
    /* @var \app\store\model\Goods $model */
    private $model;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new PlanModel;
        $this->view->engine->layout(false);
    }

    /**
     * 商品列表
     * @param null $status
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function lists($is_delete = 0)
    {
        $list = $this->model->getList($is_delete);
        return $this->fetch('list', compact('list'));
    }

}
