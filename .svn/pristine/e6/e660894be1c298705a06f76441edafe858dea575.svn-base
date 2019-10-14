<?php

namespace app\api\controller;

use app\api\model\Wxapp as WxappModel;
use app\api\model\WxappHelp;
use app\api\model\Setting as SettingModel;
use app\api\model\Goods as GoodsModel;
use app\api\model\Coupon as CouponModel;
use app\api\model\UserCoupon as UserCouponModel;
use think\Cache;

/**
 * 微信小程序
 * Class Wxapp
 * @package app\api\controller
 */
class Wxapp extends Controller
{
    /**
     * 小程序基础信息
     * @return array
     */
    public function base()
    {
//        $wxapp = WxappModel::getWxappCache();
        // 获取新人礼包配置
        $setting   = $settingCache = SettingModel::getItem('newbie');
        $goodsId   = isset($setting['goods_id']) ? $setting['goods_id'][0] : 16; // 新人推荐商品
        $setting['first_order']['referee'] = $setting['first_order']['referee'];

        $goodsInfo = GoodsModel::detail($goodsId);

        $coupon = CouponModel::get(['is_newbie' => 10, 'is_delete' => 0]);

        $user = $this->getUser(false);
        if($user) {
            $userId  = $user['user_id'];
            $setting = Cache::get('register_setting_' . $userId);
            if(!$setting) {
                $setting['register']['is_open']     = $settingCache['register']['is_open'];
                $setting['register']['first_order'] = $settingCache['first_order']['is_open'];
                // $setting['first_order']['referee']  = $settingCache['first_order']['referee'] / 100;
                $setting['first_order']['referee']  = $settingCache['first_order']['referee'];
                $setting['first_order']['howlong']  = $settingCache['first_order']['howlong'];
            }

            if($coupon) {
                $couponId = $coupon['coupon_id'];
                $exist = UserCouponModel::get(['user_id' => $userId, 'coupon_id' => $couponId]);
                if($exist) {
                    $coupon['is_receive'] = 1;
                } else {
                    $coupon['is_receive'] = 0;
                }
                $coupon['reduce_price'] = intval($coupon['reduce_price']);
            } else {
                $coupon['is_receive']   = 0;
                $coupon['reduce_price'] = 0;
            }
        } else {
            $setting['register']['self']['contribution']    = 0;
            $coupon['is_receive']                           = 0;
        }

        $setting['goods_info']['goods_id']      = $goodsInfo['goods_id'];
        $setting['goods_info']['goods_name']    = $goodsInfo['goods_name'];
        $setting['goods_info']['selling_point'] = $goodsInfo['selling_point'];
        $setting['goods_info']['goods_price']   = $goodsInfo['sku'][0]['goods_price'];
        $setting['goods_info']['line_price']    = $goodsInfo['sku'][0]['line_price'];
        $setting['goods_info']['image']         = $goodsInfo['image'][0]['file_path'];

        $setting['coupon_info'] = $coupon;

        $goodsShareSetting = SettingModel::getItem('goods_share');
        $goodsShareSetting['referee'] = floatval($goodsShareSetting['referee']);
        $setting = [
            'newbie'   => $setting,
            'goods_share' => $goodsShareSetting
        ];
        return $this->renderSuccess($setting);
    }

    /**
     * 帮助中心
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function help()
    {
        $model = new WxappHelp;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }
}
