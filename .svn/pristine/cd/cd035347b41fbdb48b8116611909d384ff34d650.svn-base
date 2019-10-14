<?php

namespace app\store\controller\market;

use app\store\controller\Controller;
use app\store\model\Setting as SettingModel;

class Goodsshare extends Controller
{
    /**
     * 商品分享设置
     * @return array|bool|mixed
     * @throws \think\exception\DbException
     */
    public function setting()
    {
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem('goods_share');
            return $this->fetch('setting', ['values' => $values]);
        }
        $model = new SettingModel;
        if ($model->edit('goods_share', $this->postData('goods_share'))) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}
