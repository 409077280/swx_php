<?php

namespace app\api\controller;

use app\api\model\WxappPage;
use app\api\model\Goods as GoodsModel;

/**
 * 页面控制器
 * Class Index
 * @package app\api\controller
 */
class Page extends Controller
{
    /**
     * 页面数据
     * @param null $page_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index($page_id = null)
    {
        // 页面元素
        $data = WxappPage::getPageData($this->getUser(false), $page_id);
        return $this->renderSuccess($data);
    }

    /**
     * 首页diy数据 (即将废弃)
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function home()
    {
        // 页面元素
        $data = WxappPage::getPageData($this->getUser(false));
        return $this->renderSuccess($data);
    }

    /**
     * 首页商品排序
     */
     public function goods($sort = '') {
         $model  = new GoodsModel;
         $detail = WxappPage::getHomePage();
         $items  = $detail['page_data']['items'];
         foreach($items as $item) {
             if ($item['type'] === 'goods') {
                 if($item['params']['source'] == 'choice') {
                     $goodsIds  = array_column($item['data'], 'goods_id');
                     $goodsList = $model->getListByIds($goodsIds, 10, $sort);
                 } elseif($item['params']['source'] == 'auto') {
                     $goodsList = $model->getList(10, 0, '', 'custom', false, $item['params']['auto']['showNum'], $sort);
                 }
             }
         }

         if ($goodsList->isEmpty()) return [];

         // 格式化商品列表
         $data = [];
         foreach ($goodsList as $goods) {
             $data[] = [
                 'goods_id' => $goods['goods_id'],
                 'goods_name' => $goods['goods_name'],
                 'contribution_rate' => $goods['contribution_rate'],
 		         'selling_point' => $goods['selling_point'],
                 'image' => format_image($goods['image'][0]['file_path']),
                 'goods_price' => $goods['sku'][0]['goods_price'],
                 'line_price' => $goods['sku'][0]['line_price'],
                 'goods_sales' => $goods['goods_sales'],
             ];
         }

         return $this->renderSuccess($data);
     }

    /**
     * 自定义页数据 (即将废弃)
     * @param $page_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function custom($page_id)
    {
        // 页面元素
        $data = WxappPage::getPageData($this->getUser(false), $page_id);
        return $this->renderSuccess($data);
    }

}
