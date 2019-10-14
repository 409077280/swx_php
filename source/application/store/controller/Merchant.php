<?php

namespace app\store\controller;
use app\store\model\MerchantJoin as MerchantModel;
use think\exception\DbException;

class Merchant extends Controller{

    public function index(){
        return $this->fetch('joinApply');
    }

    public function lists($filteType = "all", $filteValue = "", $page = 1, $limit = 10){
        $model = new MerchantModel();
        try{
            $data = $model->scan($filteType, $filteValue, $page, $limit);
            return json($this->renderJson(0, "", "", $data));
        } catch (DbException $e) {
            $message = $e->getMessage();
            return json($this->renderJson(1, $message, '', []));
        }
    }

}
