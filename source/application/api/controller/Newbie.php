<?php

namespace app\api\controller;

use app\api\model\Goods as GoodsModel;
use app\common\service\qrcode\Newbie as NewbiePoster;
use app\api\model\Setting as SettingModel;
use app\api\model\Coupon as CouponModel;

/**
 * 新人
 * Class Newbie
 * @package app\api\controller
 */
class Newbie extends Controller {
    /**
     * 获取新人礼包图片
     * @param $goods_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function poster() {
        // 新人推荐商品
        $setting   = $settingCache = SettingModel::getItem('newbie');
        $goodsId   = isset($setting['goods_id']) ? $setting['goods_id'][0] : 16;

        // 新人优惠券
        $coupon    = CouponModel::get(['is_newbie' => 10, 'is_delete' => 0]);

        // 新人商品详情
        $detail = GoodsModel::detail($goodsId);
        $Qrcode = new NewbiePoster($detail, $this->getUser(false), $coupon);
        return $this->renderSuccess([
            'qrcode' => $Qrcode->getImage(),
        ]);
    }
}
