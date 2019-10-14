<?php

namespace app\api\controller\sharing;

use app\api\controller\Controller;
use app\api\model\Wxapp as WxappModel;
use app\api\model\sharing\Order as OrderModel;
use app\api\model\WxappPrepayId as WxappPrepayIdModel;
use app\common\library\wechat\WxPay;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\DeliveryType as DeliveryTypeEnum;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\service\qrcode\Extract as ExtractQRcode;
use app\api\model\Setting as SettingModel;
use app\api\model\sharing\Goods as GoodsModel;

/**
 * 拼团订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 订单确认
     * @param int $order_type 订单类型 (10单独购买 20拼团)
     * @param int $goods_id 商品id
     * @param int $goods_num
     * @param int $goods_sku_id
     * @param int $delivery 配送方式
     * @param int $pay_type 支付方式
     * @param int $shop_id 自提门店id
     * @param int $active_id 拼团活动id
     * @param int $coupon_id 优惠券id
     * @param string $remark
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function checkout(
        $order_type,
        $goods_id,
        $goods_num,
        $goods_sku_id,
        $delivery = DeliveryTypeEnum::EXPRESS,
        $pay_type = PayTypeEnum::WECHAT,
        $shop_id = 0,
        $linkman = '',
        $phone = '',
        $active_id = null,
        $coupon_id = null,
        $remark = ''
    )
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getBuyNow(
            $this->user,
            $order_type,
            $goods_id,
            $goods_num,
            $goods_sku_id,
            $delivery,
            $pay_type,
            $shop_id
        );
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if (!$model->createOrder($this->user, $order, $linkman, $phone, $active_id, $coupon_id, $remark)) {
            return $this->renderError($model->getError() ?: '订单创建失败');
        }
        // 构建微信支付请求
        $payment = ($pay_type == PayTypeEnum::WECHAT) ? $model->paymentByWechat($this->user) : [];
        return $this->renderSuccess([
            'order_id' => $model['order_id'],   // 订单id
            'pay_type' => $pay_type,            // 支付方式
            'payment' => $payment               // 微信支付参数
        ]);
    }

    /**
     * 我的拼团订单列表
     * @param $dataType
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 拼团订单详情信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function detail($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 该订单是否允许申请售后
        $order['isAllowRefund'] = $order->isAllowRefund();
        return $this->renderSuccess(compact('order'));
    }

    /**
     * 获取物流信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function express($order_id)
    {
        // 订单信息
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if (!$order['express_no']) {
            return $this->renderError('没有物流信息');
        }
        // 获取物流信息
        /* @var \app\store\model\Express $model */
        $model = $order['express'];
        $express = $model->dynamic($model['express_name'], $model['express_code'], $order['express_no']);
        if ($express === false) {
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess(compact('express'));
    }

    /**
     * 取消订单
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        $goodId = $model['goods'][0]['goods_id'];
        $goodsModel = new GoodsModel();
        $goodDetail = $goodsModel->getGoodsDetail($goodId);

        if($model->active_id) {
            return $this->renderError('发起拼单'.$goodDetail["group_time"].'小时后，若拼单未成功将自动取消订单并退款哦');
        } else {
            if ($model->cancel()) {
                return $this->renderSuccess($model->getError() ?: '订单取消成功');
            }
            return $this->renderError($model->getError() ?: '订单取消失败');
        }
    }

    /**
     * 确认收货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function receipt($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->receipt()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * 立即支付
     * @param int $order_id 订单id
     * @param int $payType 支付方式
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function pay($order_id, $payType = PayTypeEnum::WECHAT)
    {
        // 订单详情
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 判断商品状态、库存
        if (!$model->checkGoodsStatusFromOrder($model['goods'], $model['order_type']['value'])) {
            return $this->renderError($model->getError());
        }
        $render = [
            'order_id' => $model['order_id'],   // 订单id
            'pay_type' => $payType,            // 支付方式
        ];
        if ($payType == PayTypeEnum::WECHAT) {
            $render['payment'] = $model->paymentByWechat($this->user);
        } elseif ($payType == PayTypeEnum::BALANCE) {
            if ($model->paymentByBalance($model['order_no'])) {
                return $this->renderSuccess($render, '支付成功');
            }
            return $this->renderError($model->getError() ?: '支付失败', $render);
        }
        return $this->renderSuccess($render);
    }

    /**
     * 获取订单核销二维码
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function extractQrcode($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 判断是否为待核销订单
        if (!$order->checkExtractOrder($order)) {
            return $this->renderError($order->getError());
        }
        $Qrcode = new ExtractQRcode(
            $this->wxapp_id,
            $this->user,
            $order_id,
            OrderTypeEnum::SHARING
        );
        return $this->renderSuccess([
            'qrcode' => $Qrcode->getImage(),
        ]);
    }

}
