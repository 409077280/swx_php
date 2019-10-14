<?php

namespace app\store\controller\market;

use app\store\controller\Controller;
use app\store\model\Setting as SettingModel;
use app\store\model\Goods;
use think\Cache;

/**
 * 营销设置-新人礼包
 * Class Basic
 * @package app\store\controller
 */
class Newbie extends Controller {
    /**
     * 新人礼包设置
     * @return array|bool|mixed
     * @throws \think\exception\DbException
     */
    public function setting() {
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem('newbie');
            if(!isset($values['goods_id']))
                $values['goods_id'] = 0;

            return $this->fetch('setting', [
                'goodsList' => (new Goods)->getListByIds($values['goods_id']),
                'values' => $values
            ]);
        }
        $model = new SettingModel;
        if ($model->edit('newbie', $this->postData('newbie'))) {
            // 删除用户随机贡献循环缓存
            self::_deleteRandomContribution($this->getWxappId(), $this->postData('newbie'));

            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    private static function _deleteRandomContribution($wxappId, $data) {
        $bigbonus = $data['register']['bigbonus'];
        if($bigbonus) {
            $bigbonus    = str_replace('，', ',', $bigbonus);
            $bigbonusArr = explode(',', $bigbonus);
            if($bigbonusArr) {
                foreach($bigbonusArr as $bigbonusItem) {
                    $bonusArr = explode('|', $bigbonusItem);
                    if($bonusArr) {
                        $bonus= $bonusArr[0];
                        $rate = $bonusArr[1];
                        $cacheIndex   = 'bigbonus_' . $wxappId . '_' . $bonus;
                        Cache::rm($cacheIndex);
                    }
                }
            }
        }
    }
}
