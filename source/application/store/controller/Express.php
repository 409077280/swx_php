<?php


namespace app\store\controller;

use app\store\model\Express as ExpressModel;
use think\exception\DbException;

class Express extends Controller
{
    public function get_all(){
        try{
            $data = ExpressModel::getAll();
            return json($this->renderJson(0, "", "", $data));
        } catch (DbException $e){
            return json($this->renderJson(1, $e->getMessage(), '', []));
        }

    }
}