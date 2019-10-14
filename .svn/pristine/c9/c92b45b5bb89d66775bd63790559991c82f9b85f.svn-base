<?php

namespace app\task\model\dbcenter\sharing;

use app\api\model\sharing\Order as OrderModel;
use app\api\model\Contribution as ContributionModel;
use app\api\model\Bonus as BonusModel;
use app\api\model\User as UserModel;


/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class Order extends OrderModel {
    public function addContributionAndBonus($orderId, $userId, $returnData) {
        $orderInfo = OrderModel::getUserOrderDetail($orderId, $userId);
        $orderGoodsNameStr = '';
        if($orderInfo['goods']) {
            foreach($orderInfo['goods'] as $goods) {
                $orderGoodsNameStr .= $goods['goods_name'] . ',';
            }
        }

        $this->startTrans();
        try {
            // 插入贡献记录表
            $cdata = [
                'user_id'  => $userId,
                'order_id' => $orderInfo['order_id'],
                'order_amount' => $orderInfo['pay_price'],
                'contribution' => $returnData['orderContribution'],
                'wxapp_id'     => $orderInfo['wxapp_id'],
                'short_desc'   => substr($orderGoodsNameStr, 0, -1),
                'type'         => 'sharing'
            ];
            self::_addContribution($cdata);

            // 插入分红记录表
            $bdata = [
                'user_id'  => $userId,
                'order_id' => $orderInfo['order_id'],
                'order_amount' => $orderInfo['pay_price'],
                'bonus'        => $returnData['orderDividend'],
                'wxapp_id'     => $orderInfo['wxapp_id'],
                'type'         => 'sharing'
            ];
            self::_addBonus($bdata);

            // 更新用户表贡献及分红数据
            self::_updateWaitingBonus($userId, $returnData);

            $this->commit();

            echo json_encode(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
        } catch (\Exception $e) {
            $this->rollback();
            echo json_encode(['return_code' => 'FAIL', 'return_msg' => $e->getMessage()]);
        }
    }

    /**
     * 插入贡献记录
     */
    private static function _addContribution($data) {
        $model = new ContributionModel;
        $model->save($data);
    }

    /**
     * 删除贡献记录
     */
    private static function _deleteContribution($userId, $orderId) {
        return ContributionModel::get(['user_id' => $userId, 'order_id' => $orderId])->delete();
    }

    /**
     * 插入分红记录
     */
    private static function _addBonus($data) {
        $model = new BonusModel;
        $model->save($data);
    }

    /**
     * 删除分红记录
     */
    private static function _deleteBonus($userId, $orderId) {
        return BonusModel::get(['user_id' => $userId, 'order_id' => $orderId])->delete();;
    }

    /**
     * 更新用户表待提分红
     */
    private static function _updateWaitingBonus($userId, $returnData) {
        $model = new UserModel;
        $model->save([
            'waiting_bonus' => $returnData['frozenDividend'],
            'bonus'         => $returnData['totalDividend'],
            'can_bonus'     => $returnData['availableDividend'],
            'contribution'  => $returnData['totalContribution'],
            'waiting_contribution' => $returnData['frozenContribution']
        ], ['user_id' => $userId]);
    }

    public function updateContributionAndBonus($orderId, $userId, $returnData) {
        $this->startTrans();
        try {
            // 删除贡献
            self::_deleteContribution($userId, $orderId);

            // 删除分红
            self::_deleteBonus($userId, $orderId);

            // 更新用户表贡献及分红数据
            self::_updateWaitingBonus($userId, $returnData);

            $this->commit();
            echo json_encode(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
        } catch (\Exception $e) {
            $this->rollback();
            echo json_encode(['return_code' => 'FAIL', 'return_msg' => $e->getMessage()]);
        }
    }

    public function updateUsesrContributionAndBonus($orderId, $userId, $returnData) {
        // 更新用户表贡献及分红数据
        self::_updateWaitingBonus($userId, $returnData);
    }

    public function updateOrderUnfrozen($orderId) {
        $this->save([
            'is_unfrozen' => 1
        ], ['order_id' => $orderId]);
    }

    /**
     * 执行解冻操作
     */
     public function doUnfrozen($orderId, $userId, $returnData) {
         $this->startTrans();
         try {
             // 更新用户表贡献及分红数据
             self::_updateWaitingBonus($userId, $returnData);

             // 修改订单状态
             $this->save([
                 'is_unfrozen' => 1
             ], ['order_id' => $orderId]);

             $this->commit();
             echo json_encode(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
         } catch (\Exception $e) {
             $this->rollback();
             echo json_encode(['return_code' => 'FAIL', 'return_msg' => $e->getMessage()]);
         }
     }
}
