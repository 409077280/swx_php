<?php

namespace app\api\controller;

use app\api\model\MerchantJoin as MerchantModel;

class Merchant extends Controller{

    public function joinApplyAdd(){
        if ($this->request->isPost() == false){
            return json($this->renderJson(0, "请求类型错误.", '', []));
        }
        $postData = $this->request->post();
        unset($postData['token']);
        if (empty($postData["real_name"]) || empty($postData["mobile_phone"]) || empty($postData["product_name"])){
            return json($this->renderJson(0, "请求参数错误.", '', []));
        }
        $model = new MerchantModel();
        $result = $model->add($postData);
        if ($result == false){
            return json($this->renderJson(0, "执行过程中发生错误.", '', []));
        }
        return json($this->renderJson(1, "提交成功！", []));
    }
}