<?php

namespace app\common\model;

class MerchantJoin extends BaseModel{
    protected $name = 'merchant_join';
    protected $pk = 'id';

    //取消 update_time字段自动写入
    protected $updateTime = false;

    public function scan($filteType, $filteValue, $page = 1, $limit = 10){
        $model = new static;
        $total = $this->totalData($filteType, $filteValue);
        if ($filteType == "all"){
            $data = $model->page($page, $limit)->order(["id" => "desc"])->select()->toArray();
        }else {
            $data = $model->where($filteType, 'like','%'.$filteValue.'%')->page($page, $limit)->order(["id" => "desc"])->select()->toArray();
        }
        return compact('total', 'data');
    }

    public function totalData($filteType, $filteValue){
        $model = new static;
        if ($filteType == "all"){
            $number = $model->count();
        }else {
            $number = $model->where([$filteType => $filteValue])->count();
        }
        return $number;
    }

    public function add($data){
        $model = new static;
        return $model->save($data);
    }
}