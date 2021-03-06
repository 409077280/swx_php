<?php

namespace app\api\controller;

use app\api\model\Goods as GoodsModel;
use app\api\model\Cart as CartModel;
use app\common\service\qrcode\Goods as GoodsPoster;
use app\common\service\qrcode\Cover as CoverPoster;
use app\api\model\Setting as SettingModel;
use app\api\model\Coupon as CouponModel;

/**
 * 商品控制器
 * Class Goods
 * @package app\api\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表
     * @param $category_id
     * @param $search
     * @param $sortType
     * @param $sortPrice
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($category_id, $search, $sortType, $sortPrice)
    {
        $model = new GoodsModel;
        $list = $model->getList(10, $category_id, $search, $sortType, $sortPrice);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取商品详情
     * @param $goods_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($goods_id)
    {
        // 商品详情
        $detail = GoodsModel::detail($goods_id);
        $currentTime = time();
        $goodsStatus = ($detail['goods_status']['value'] == 10 && $detail['start_time'] < $currentTime) || ($detail['goods_status']['value'] == 20 && $detail['end_time'] > $currentTime);
        if (!$detail || $detail['is_delete'] || !$goodsStatus) {
            return $this->renderError('很抱歉，商品信息不存在或已下架');
        }
        // 处理商品内容里面的图片
        $detail['content'] = format_content($detail['content']);
        // 多规格商品sku信息
        $specData = $detail['spec_type'] == 20 ? $detail->getManySpecData($detail['spec_rel'], $detail['sku']) : null;
        // 购物车商品总数量
        $cart_total_num = 0;
        if ($user = $this->getUser(false)) {
            $cart_total_num = (new CartModel($user))->getGoodsNum();
        }
        return $this->renderSuccess(compact('detail', 'cart_total_num', 'specData'));
    }

    /**
     * 获取推广二维码
     * @param $goods_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function poster($goods_id)
    {
        // 商品详情
        $detail = GoodsModel::detail($goods_id);
        $Qrcode = new GoodsPoster($detail, $this->getUser(false));
        return $this->renderSuccess([
            'qrcode' => $Qrcode->getImage(),
        ]);
    }

    /**
     * 获取推广封面
     */
     public function cover($goods_id)
     {
         header('Content-Type: image/png');
         // 商品详情
         $detail = GoodsModel::detail($goods_id);

         // 新人优惠券
         $coupon = CouponModel::get(['is_newbie' => 10, 'is_delete' => 0]);

         $Qrcode = new CoverPoster($detail, $this->getUser(false), $coupon);

         return response($this->showImg($Qrcode->getImage()))->contentType("image/png");
     }

     public function showImg($img) {
         $imgInfo = imagecreatefrompng($img);
         $quality = 9;
         imagepng($imgInfo, null, $quality);
         imagedestroy($imgInfo);
     }
}
