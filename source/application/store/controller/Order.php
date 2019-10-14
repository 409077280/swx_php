<?php

namespace app\store\controller;

use app\store\model\Order as OrderModel;
use app\store\model\Express as ExpressModel;
use app\store\model\store\shop\Clerk as ShopClerkModel;
use app\store\model\store\Shop as ShopModel;
use app\store\model\OrderAddress as OrderAddressModel;
use app\store\model\OrderGoods as OrderGoodsModel;
use think\exception\PDOException;

/**
 * 订单管理
 * Class Order
 * @package app\store\controller
 */
class Order extends Controller
{
    /**
     * 待发货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function delivery_list()
    {
        return $this->getList('待发货订单列表', 'delivery');
    }

    /**
     * 待收货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function receipt_list()
    {
        return $this->getList('待收货订单列表', 'receipt');
    }

    /**
     * 待付款订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function pay_list()
    {
        return $this->getList('待付款订单列表', 'pay');
    }

    /**
     * 已完成订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function complete_list()
    {
        return $this->getList('已完成订单列表', 'complete');
    }

    /**
     * 已取消订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function cancel_list()
    {
        return $this->getList('已取消订单列表', 'cancel');
    }

    /**
     * 全部订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function all_list()
    {
        return $this->getList('全部订单列表', 'all');
    }

    /**
     * 订单详情
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        // 订单详情
        $detail = OrderModel::detail($order_id);

        //TODO:目前做"一物流公司多订单"的情况处理，后续需要对“多物流公司多订单”的情况做改动
        $expressIdCount = count(explode(',', $detail['express_id']));
        if ( $expressIdCount <= 1) {
            $copy = $detail["express_id"];
            $more = count(explode(',', $detail['express_no'])) - $expressIdCount;
            for ($i = 0; $i < $more; $i++){
                $detail["express_id"] .= ','. $copy;
            }
        }

        // 物流公司列表
        $expressList = ExpressModel::getAll();
        // 门店店员列表
        $shopClerkList = (new ShopClerkModel)->getList(true);
        return $this->fetch('detail', compact(
            'detail',
            'expressList',
            'shopClerkList'
        ));
    }

    /**
     * 确认发货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function delivery($order_id)
    {
        $model = OrderModel::detail($order_id);

        if ($model->delivery($this->postData('order'))) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError($model->getError() ?: '发货失败');
    }

    /**
     * 修改订单价格
     * @param $order_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function updatePrice($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->updatePrice($this->postData('order'))) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 订单列表
     * @param string $title
     * @param string $dataType
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getList($title, $dataType)
    {
        // 订单列表
        $model = new OrderModel;
        $list = $model->getList($dataType, $this->request->param());
        // 自提门店列表
        $shopList = ShopModel::getAllList();
        return $this->fetch('index', compact('title', 'dataType', 'list', 'shopList'));
    }

    /**
     *  修改订单收货人信息
     * @return \think\response\Json
     */
    public function reset_order_address(){
        if ($this->request->isPut() == false){
            return json($this->renderJson(1, "请求类型错误.", '', []));
        }
        $putData = $this->request->put();
        $order_id = $putData['order_id'];
        unset($putData['order_id']);
        $model = new OrderModel();
        $result = $model->checkDeliveryStatus($order_id);
        if ($result == false){
            return json($this->renderJson(1, "当前状态无法修改信息.", '', []));
        }
        $adressModel = new OrderAddressModel();
        $reset = $adressModel->resetOrderAddress($order_id, $putData);
        if ($reset == false){
            return json($this->renderJson(1, "修改失败.", '', []));
        }
        return json($this->renderJson(0, "修改成功！", '',[]));
    }

    /**
     *  获取当前物流信息
     */
    public function getOrderExpressInfo($order_id){
        $orderModel = new OrderModel();
        $data = $orderModel->getOrderExpressInfo($order_id);
        if ($data == false){
            return json($this->renderJson(1, "暂无物流信息", '', []));
        }
        return json($this->renderJson(0, '', '', $data));
    }

    /**
     * 获取当前订单商品信息
     * @param $orderId
     */
    public function get_order_goods_info($order_id){
        $ogModel = new OrderGoodsModel();
        $data = $ogModel->getOrderGoodsInfo($order_id);
        if ($data == false){
            return json($this->renderJson(1, "暂无商品信息", '', []));
        }
        return json($this->renderJson(0, '', '', $data));
    }

    /**
     *  发货后修改运单信息
     * @return \think\response\Json
     */
    public function reset_order_express() {
        if ($this->request->isPut() == false){
            return json($this->renderJson(1, "请求类型错误.", '', []));
        }
        $putData = $this->request->put();
        //return json($this->renderJson(0, "修改成功！", '',$putData));
        if (empty($putData['orderGoods']) || empty($putData['order_id'])){
            return json($this->renderJson(1, "参数错误", '', []));
        }
        $model = new OrderModel();
        // 检查当前订单状态是否可以修改
        $result = $model->checkReceiptStatus($putData['order_id']);
        if ($result == false){
            return json($this->renderJson(1, "当前状态无法修改信息.", '', []));
        }
        try{
            $reset = $model->resetOrderExpress($putData);
            return json($this->renderJson(0, "修改成功！", '',[]));
        } catch (PDOException $e){
            return json($this->renderJson(1, "修改失败.", '', []));
        }

    }

}
