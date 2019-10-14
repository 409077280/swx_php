<?php

namespace app\api\controller\sharing;

use app\api\controller\Controller;
use app\api\model\sharing\Active as ActiveModel;
use app\api\model\sharing\Goods as GoodsModel;
use app\api\model\User as UserModel;
use think\Exception;

/**
 * 拼团拼单控制器
 * Class Active
 * @package app\api\controller\sharing
 */
class Active extends Controller
{
    /**
     * 拼单详情
     * @param $active_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function detail($active_id)
    {
        // 拼单详情
        try{
            $list = ActiveModel::detail($active_id);
            if (!$list) {
                return $this->renderError('很抱歉，拼单不存在');
            }
            $detail = $list->toArray();
        } catch (Exception $e){
            return $this->renderError($e);
        }
        if ($detail['status']['value'] == 20 && (count($detail['users']) > 0 && count($detail['users']) < $detail['people'])){
            //剩余拼团人数
            $detail['surplus_people'] = 0;
            $num = $detail['people'] - count($detail['users']);
            for ($i = 0; $i < $num; $i++){
                $userInfo = UserModel::random();
                $item['id'] = 0;
                $item['order_id'] = 0;
                $item['is_creator'] = 0;
                $item['sharing_order'] = [];
                $item['user'] = [
                    'nickName' => $userInfo['nickName'],
                    'avatarUrl' => 'https://picsum.photos/id/'. $detail['users'][0]['user_id'] .'/132/132',
                ];
                $key = count($detail['users']) + $i;
                $detail['users'][$key] = $item;
            }
        }
        $detail = collection($detail);
        // 拼团商品详情
        $goods = GoodsModel::detail($detail['goods_id']);
        // 多规格商品sku信息
        $specData = $goods['spec_type'] == 20 ? $goods->getManySpecData($goods['spec_rel'], $goods['sku']) : null;
        // 更多拼团商品
        $model = new GoodsModel;
        $goodsList = $model->getList(10, 0, '', 'all', false, 5);
        return $this->renderSuccess(compact('detail', 'goods', 'goodsList', 'specData'));
    }

}
