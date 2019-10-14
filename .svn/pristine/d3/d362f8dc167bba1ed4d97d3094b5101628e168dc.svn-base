<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\Withdraw as WithdrawModel;
use app\common\library\dbcenter\Util;
use app\api\model\UserCoupon as UserCouponModel;
use app\api\model\User as UserModel;
use think\Config;
use app\api\model\Setting as SettingModel;
use app\common\library\dbcenter\User as DBCenterUser;
use app\common\service\qrcode\ChartTrendBg as ChartTrendBgPoster;

/**
 * 数据中心图表
 * Class Index
 * @package app\api\controller\user
 */
class Chart extends Controller
{
    /**
     * 数据趋势
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function trend() {
        $xAxis = ['1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00',
                  '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '24:00'];
        // 当前用户信息
        $user = $this->getUser();
        list($contributionsData, $bonusData, $personNum, $bonusNum, $totalContribution, $totalBonus) = DBCenterUser::getChartData($user['user_id']);
        return $this->renderSuccess([
            'chartData' => [
                'xAxis' => $xAxis,
                'series' => [
                    'bonus' => [
                        'name' => '分红',
                        'data' => $contributionsData
                    ],
                    'contribution' => [
                        'name' => '贡献',
                        'data' => $bonusData
                    ]
                ],
                'totalContribution' => $totalContribution,
                'totalBonus' => $totalBonus,
                'personNum'  => $personNum,
                'bonusNum'   => $bonusNum
            ]
        ]);
    }

    /**
     * 生成图表分享背景
     */
     public function bg() {
         header('Content-Type: image/png');

         $user = $this->getUser();
         $Qrcode = new ChartTrendBgPoster($user);
         return response($this->showImg($Qrcode->getImage()))->contentType("image/png");
     }

     public function showImg($img) {
         $imgInfo = imagecreatefrompng($img);
         $quality = 9;
         imagepng($imgInfo, null, $quality);
         imagedestroy($imgInfo);
     }
}
