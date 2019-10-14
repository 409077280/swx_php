<?php

namespace app\store\controller\order;

use app\store\controller\Controller;
use app\store\model\Order as OrderModel;
use app\store\model\OrderGoods as OrderGoodsModel;
use app\store\model\OrderCancel as OrderCancelModel;
use app\store\model\Express as ExpressModel;
use app\common\library\dbcenter\Order as DBCenterOrderModel;

/**
 * 订单操作控制器
 * Class Operate
 * @package app\store\controller\order
 */
class Operate extends Controller
{
    /* @var OrderModel $model */
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
        $this->model = new OrderModel;
    }

    /**
     * 订单导出
     * @param string $dataType
     * @throws \think\exception\DbException
     */
    public function export($dataType)
    {
        return $this->model->exportList($dataType, $this->request->param());
    }

    /**
     * 批量发货
     * @return array|bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function batchDelivery()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('batchDelivery', [
                'express_list' => ExpressModel::getAll()
            ]);
        }
        if ($this->model->batchDelivery($this->postData('order'))) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError($this->model->getError() ?: '发货失败');
    }

    /**
     * 批量发货模板
     */
    public function deliveryTpl()
    {
        return $this->model->deliveryTpl();
    }

    /**
     * 审核：用户取消订单
     * @param $order_id
     * @param $goods_id
     * @param $goods_sku_id 如果传了$goods_id和$goods_sku_id则表示取消单个商品
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function confirmCancel($order_id, $goods_id = 0, $goods_sku_id = 0) {
        if($order_id && $goods_id && $goods_sku_id) {
            $model = OrderModel::detail($order_id);
            $payStatus = $model['pay_status']['value'];
            if($payStatus != 20) {
                return $this->renderError('订单不合法');
            } else {
                // 同意
                if(isset($this->postData('cancel')['is_agree']) && $this->postData('cancel')['is_agree'] == 10) {
                    // 上报数据中心处理单商品取消请求
                    (new DBCenterOrderModel)->cancel($order_id, $model->user_id, 1, $goods_id, $goods_sku_id);

                    return $this->renderSuccess('操作成功');
                // 拒绝
                } else {
                    $model->startTrans();
                    try {
                        $model->save(['order_status' => 10]);
                        (new OrderGoodsModel)->save(['status' => 1], ['order_id' => $order_id, 'goods_id' => $goods_id, 'goods_sku_id' => $goods_sku_id]);
                        // 删除记录
                        // (new OrderCancelModel)->save(['is_agree' => 20], ['order_id' => $order_id, 'goods_id' => $goods_id, 'goods_sku_id' => $goods_sku_id]);
                        OrderCancelModel::get(['order_id' => $order_id, 'goods_id' => $goods_id, 'goods_sku_id' => $goods_sku_id])->delete();

                        $model->commit();

                        return $this->renderSuccess('操作成功');
                    } catch (\Exception $e) {
                        $model->rollback();
                        return $this->renderError($e->getMessage());
                    }
                }
            }
        } else {
            return $this->renderError('操作失败');
        }
    }

    /**
     * 门店自提核销
     * @param $order_id
     * @return array|bool
     * @throws \think\exception\DbException
     */
    public function extract($order_id)
    {
        $model = OrderModel::detail($order_id);
        $data = $this->postData('order');
        if ($model->verificationOrder($data['extract_clerk_id'])) {
            return $this->renderSuccess('核销成功');
        }
        return $this->renderError($model->getError() ?: '核销失败');
    }

}
