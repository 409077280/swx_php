<?php

namespace app\store\controller;

use app\store\model\Category;
use app\store\model\CategoryDisplay;
use app\store\model\Delivery;
use app\store\model\Goods as GoodsModel;
use app\store\model\CategoryDisplay as CategoryDisplayModel;
use app\store\model\store\Shop as ShopModel;

/**
 * 商品管理控制器
 * Class Goods
 * @package app\store\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表(出售中)
     * @param null $goods_status
     * @param null $category_id
     * @param string $goods_name
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($goods_status = null, $category_id = null, $goods_name = '')
    {
        // 商品分类
        $catgory = Category::getCacheTree();
        // 商品列表
        $model = new GoodsModel;
        $list = $model->getList($goods_status, $category_id, $goods_name);
        return $this->fetch('index', compact('list', 'catgory'));
    }

    /**
     * 添加商品
     * @return array|bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();
            // 配送模板
            $delivery = Delivery::getAll();
            // 商家列表
            $shops = ShopModel::getAllList();
            return $this->fetch('add', compact('catgory', 'delivery', 'shops'));
        }
        $model = new GoodsModel;
        if ($model->add($this->postData('goods'))) {
            return $this->renderSuccess('添加成功', url('goods/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 一键复制
     * @param $goods_id
     * @return array|bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function copy($goods_id)
    {
        if (!$this->request->isAjax()) {
            // 商品详情
            $model = GoodsModel::detail($goods_id);
            // 商品分类
            $catgory = Category::getCacheTree();
            // 展示商品分类
            $displayCategory = CategoryDisplayModel::getAllByGoodsParams(['goods_id' => $goods_id]);
            foreach ($catgory as &$value) {
                foreach ($displayCategory as $item1){
                    $value['selected'] = false;
                    if ($value['category_id'] == $item1['category_id']){
                        $value['selected'] = true;
                        break;
                    }
                }
                if (isset($value['child'])){
                    foreach ($value['child'] as &$val){
                        foreach ($displayCategory as $item2){
                            $val['selected'] = false;
                            if ($val['category_id'] == $item2['category_id']){
                                $val['selected'] = true;
                                break;
                            }
                        }
                        if (isset($val['child'])){
                            foreach ($val['child'] as &$va){
                                foreach ($displayCategory as $item3){
                                    $va['selected'] = false;
                                    if ($va['category_id'] == $item3['category_id']){
                                        $va['selected'] = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // 配送模板
            $delivery = Delivery::getAll();
            // 商家列表
            $shops = ShopModel::getAllList();
            // 商品sku数据
            $specData = 'null';
            if ($model['spec_type'] == 20) {
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['sku']), JSON_UNESCAPED_SLASHES);
            }
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData', 'shops'));
        }
        $model = new GoodsModel;
        if ($model->add($this->postData('goods'))) {
            return $this->renderSuccess('添加成功', url('goods/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 商品编辑
     * @param $goods_id
     * @return array|bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function edit($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();
            // 展示商品分类
            $displayCategory = CategoryDisplayModel::getAllByGoodsParams(['goods_id' => $goods_id]);
            foreach ($catgory as &$value) {
                foreach ($displayCategory as $item1){
                    $value['selected'] = false;
                    if ($value['category_id'] == $item1['category_id']){
                        $value['selected'] = true;
                        break;
                    }
                }
                if (isset($value['child'])){
                    foreach ($value['child'] as &$val){
                        foreach ($displayCategory as $item2){
                            $val['selected'] = false;
                            if ($val['category_id'] == $item2['category_id']){
                                $val['selected'] = true;
                                break;
                            }
                        }
                        if (isset($val['child'])){
                            foreach ($val['child'] as &$va){
                                foreach ($displayCategory as $item3){
                                    $va['selected'] = false;
                                    if ($va['category_id'] == $item3['category_id']){
                                        $va['selected'] = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // 配送模板
            $delivery = Delivery::getAll();
            // 商家列表
            $shops = ShopModel::getAllList();
            // 商品sku数据
            $specData = 'null';
            if ($model['spec_type'] == 20) {
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['sku']), JSON_UNESCAPED_SLASHES);
            }
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData', 'shops'));
        }
        // 更新记录
        if ($model->edit($this->postData('goods'))) {
            return $this->renderSuccess('更新成功', url('goods/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 修改商品状态
     * @param $goods_id
     * @param boolean $state
     * @return array
     */
    public function state($goods_id, $state)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$model->setStatus($state)) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 删除商品
     * @param $goods_id
     * @return array
     */
    public function delete($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$model->setDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
