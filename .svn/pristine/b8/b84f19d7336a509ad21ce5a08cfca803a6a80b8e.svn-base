<?php

namespace app\task\controller\dbcenter;

use app\task\model\dbcenter\Order as OrderModel;
use app\task\model\dbcenter\sharing\Order as SharingOrderModel;
use app\task\model\dbcenter\offline\Order as OfflineOrderModel;

/**
 * 上传订单成功异步通知接口
 * Class Notify
 * @package app\api\controller
 */
class Order {
    /**
     * 上传订单到数据中心成功异步通知
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function notify() {
        $responseData = json_decode((file_get_contents("php://input")), true);
        // file_put_contents('/www/web/swx_dedeshijie_com/web/temp/callback.html', print_r($responseData, true));
        if(isset($responseData['data']['attach']) && $responseData['data']['attach']) {
            list($action, $orderId, $userId) = explode('|', $responseData['data']['attach']);
            $returnData = isset($responseData['data']) ? $responseData['data'] : [];
            // file_put_contents('/www/web/swx_dedeshijie_com/web/temp/callback.html', print_r($returnData, true));
            if($action == 'create') {
                // 插入贡献、分红
                $model = new OrderModel;
                $model->addContributionAndBonus($orderId, $userId, $returnData);
            } else if($action == 'cancel' || $action == 'refund') {
                // 更新贡献、分红
                $model = new OrderModel;
                $model->updateContributionAndBonus($orderId, $userId, $returnData);
            } else if($action == 'sharing') {
                // 插入贡献、分红
                $model = new SharingOrderModel;
                $model->addContributionAndBonus($orderId, $userId, $returnData);
            } else if($action == 'unfrozen') {
                $model = new OrderModel;
                $model->doUnfrozen($orderId, $userId, $returnData);
            } else if($action == 'cancelGoods') {
                list($action, $orderId, $userId, $goodsId, $goodsSkuId) = explode('|', $responseData['data']['attach']);
                $model = new OrderModel;
                $model->cancelConfirmGoods($orderId, $userId, $returnData, $goodsId, $goodsSkuId);
            } else if($action == 'unfrozenSharing') {
                $model = new SharingOrderModel;
                $model->doUnfrozen($orderId, $userId, $returnData);
            } else if($action == 'offline') {
                $model = new OfflineOrderModel;
                $model->updateUsesrContributionAndBonus($orderId, $userId, $returnData);
                // $model->updateOrderUnfrozen($orderId);
            }
        }
    }
}
