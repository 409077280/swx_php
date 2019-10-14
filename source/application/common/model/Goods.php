<?php

namespace app\common\model;

use app\api\model\CategoryDisplay as CategoryDisplayModel;
use think\Hook;

/**
 * 商品模型
 * Class Goods
 * @package app\common\model
 */
class Goods extends BaseModel
{
    protected $name = 'goods';
    protected $append = ['goods_sales'];

    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        // 挂载商品钩子
        Hook::listen('goods_active', $static);
    }

    /**
     * 计算显示销量 (初始销量 + 实际销量)
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getGoodsSalesAttr($value, $data)
    {
        return $data['sales_initial'] + $data['sales_actual'];
    }

    /**
     * 关联商品分类表
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('Category');
    }

    /**
     * 关联商品规格表
     * @return \think\model\relation\HasMany
     */
    public function sku()
    {
        return $this->hasMany('GoodsSku')->order(['goods_sku_id' => 'asc']);
    }

    /**
     * 关联商品规格关系表
     * @return \think\model\relation\BelongsToMany
     */
    public function specRel()
    {
        return $this->belongsToMany('SpecValue', 'GoodsSpecRel');
    }

    /**
     * 关联商品图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('GoodsImage')->order(['id' => 'asc']);
    }

    /**
     * 关联运费模板表
     * @return \think\model\relation\BelongsTo
     */
    public function delivery()
    {
        return $this->BelongsTo('Delivery');
    }

    /**
     * 关联订单评价表
     * @return \think\model\relation\HasMany
     */
    public function commentData()
    {
        return $this->hasMany('Comment');
    }

    /**
     * 计费方式
     * @param $value
     * @return mixed
     */
    public function getGoodsStatusAttr($value)
    {
        $status = [10 => '上架', 20 => '下架'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 获取商品列表
     * @param int $status
     * @param int $category_id
     * @param string $search
     * @param string $sortType
     * @param bool $sortPrice
     * @param int $listRows
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList(
        $status = null,
        $category_id = 0,
        $search = '',
        $sortType = 'all',
        $sortPrice = false,
        $listRows = 15,
        $customSort = ''
    )
    {
        // 筛选条件
        $filter = [];
        $category_id > 0 && $filter['goods_id'] = ['IN', CategoryDisplayModel::getGoodsId($category_id)];
        //$category_id > 0 && $filter['category_id'] = ['IN', Category::getSubCategoryId($category_id)];
        $status > 0 && $filter['goods_status'] = $status;
        !empty($search) && $filter['goods_name'] = ['like', '%' . trim($search) . '%'];
        // 排序规则
        $sort = [];
        if ($sortType === 'all') {
            $sort = ['goods_sort', 'goods_id' => 'desc'];
        } elseif ($sortType === 'new') {
            $sort = ['goods_id' => 'desc'];
        } elseif ($sortType === 'sales') {
            $sort = ['goods_sales' => 'desc'];
        } elseif ($sortType === 'price') {
            $sort = $sortPrice ? ['goods_max_price' => 'desc'] : ['goods_min_price'];
        } elseif($sortType == 'custom') {
            $field      = 'goods_id';
            $customSort = $customSort ? $customSort : 'goods_id,desc';
            $sortField  = explode(',', $customSort);
            if($sortField[0] == 'new')
                $field = 'goods_id';
            elseif($sortField[0] == 'sales')
                $field = 'goods_sales';
            elseif($sortField[0] == 'price')
                $field = 'goods_default_price';

            $sort = [$field => $sortField[1]];
        }
        // 商品表名称
        $tableName = $this->getTable();
        // 多规格商品 最高价与最低价
        $GoodsSku = new GoodsSku;
        $minPriceSql = $GoodsSku->field(['MIN(goods_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        $maxPriceSql = $GoodsSku->field(['MAX(goods_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        $defaultPriceSql = $GoodsSku->field(['goods_price'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")
            ->order(['goods_sku_id' => 'asc'])
            ->limit(1)
            ->buildSql();
        // 执行查询
        $list = $this
            ->field(['*', '(sales_initial + sales_actual) as goods_sales',
                "$minPriceSql AS goods_min_price",
                "$maxPriceSql AS goods_max_price",
                "$defaultPriceSql AS goods_default_price"
            ])
            ->with(['category', 'image.file', 'sku'])
            ->where('is_delete', '=', 0)
            ->where($filter)
            ->order($sort)
            ->paginate($listRows, false, [
                'query' => \request()->request()
            ]);
        return $list;
    }

    /**
     * 根据商品id集获取商品列表
     * @param array $goodsIds
     * @param null $status
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByIds($goodsIds, $status = null, $customSort = '')
    {
        // 筛选条件
        $filter = ['goods_id' => ['in', $goodsIds]];
        $status > 0 && $filter['goods_status'] = $status;
        if (!empty($goodsIds) && !$customSort) {
            $this->orderRaw('field(goods_id, ' . implode(',', $goodsIds) . ')');
        }
        // 排序
        $sort = [];
        if($customSort) {
            $sortField = explode(',', $customSort);
            if($sortField[0] == 'new')
                $field = 'goods_id';
            elseif($sortField[0] == 'sales')
                $field = 'goods_sales';
            elseif($sortField[0] == 'price')
                $field = 'goods_default_price';

            $sort = [$field => $sortField[1]];
        }

        // 商品表名称
        $tableName = $this->getTable();
        // 多规格商品 最高价与最低价
        $GoodsSku = new GoodsSku;
        $minPriceSql = $GoodsSku->field(['MIN(goods_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        $maxPriceSql = $GoodsSku->field(['MAX(goods_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        $defaultPriceSql = $GoodsSku->field(['goods_price'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")
            ->order(['goods_sku_id' => 'asc'])
            ->limit(1)
            ->buildSql();
        // 执行查询
        $query = $this
            ->field(['*', '(sales_initial + sales_actual) as goods_sales',
                "$minPriceSql AS goods_min_price",
                "$maxPriceSql AS goods_max_price",
                "$defaultPriceSql AS goods_default_price"
            ])
            ->with(['category', 'image.file', 'sku', 'spec_rel.spec', 'delivery.rule'])
            ->where('is_delete', '=', 0)
            ->where($filter);

        if($sort)
            $query->order($sort);

        $data = $query->select();

        if ($data->isEmpty()) return $data;
        // 格式化数据
        foreach ($data as &$item) {
            $item['goods_image'] = $item['image'][0]['file_path'];
        }
        return $data;
    }

    /**
     * 商品多规格信息
     * @param \think\Collection $spec_rel
     * @param \think\Collection $skuData
     * @return array
     */
    public function getManySpecData($spec_rel, $skuData)
    {
        // spec_attr
        $specAttrData = [];
        foreach ($spec_rel->toArray() as $item) {
            if (!isset($specAttrData[$item['spec_id']])) {
                $specAttrData[$item['spec_id']] = [
                    'group_id' => $item['spec']['spec_id'],
                    'group_name' => $item['spec']['spec_name'],
                    'spec_items' => [],
                ];
            }
            $specAttrData[$item['spec_id']]['spec_items'][] = [
                'item_id' => $item['spec_value_id'],
                'spec_value' => $item['spec_value'],
            ];
        }
        // spec_list
        $specListData = [];
        foreach ($skuData->toArray() as $item) {
            $image = (isset($item['image']) && !empty($item['image'])) ? $item['image'] : ['file_id' => 0, 'file_path' => ''];
            $specListData[] = [
                'goods_sku_id' => $item['goods_sku_id'],
                'spec_sku_id' => $item['spec_sku_id'],
                'rows' => [],
                'form' => [
                    'image_id' => $image['file_id'],
                    'image_path' => $image['file_path'],
                    'goods_no' => $item['goods_no'],
                    'goods_price' => $item['goods_price'],
                    'goods_weight' => $item['goods_weight'],
                    'line_price' => $item['line_price'],
                    'cost_price' => $item['cost_price'],
                    'stock_num' => $item['stock_num'],
                ],
            ];
        }
        return ['spec_attr' => array_values($specAttrData), 'spec_list' => $specListData];
    }

    /**
     * 多规格表格数据
     * @param $goods
     * @return array
     */
    public function getManySpecTable(&$goods)
    {
        $specData = $this->getManySpecData($goods['spec_rel'], $goods['sku']);
        $totalRow = count($specData['spec_list']);
        foreach ($specData['spec_list'] as $i => &$sku) {
            $rowData = [];
            $rowCount = 1;
            foreach ($specData['spec_attr'] as $attr) {
                $skuValues = $attr['spec_items'];
                $rowCount *= count($skuValues);
                $anInterBankNum = ($totalRow / $rowCount);
                $point = (($i / $anInterBankNum) % count($skuValues));
                if (0 === ($i % $anInterBankNum)) {
                    $rowData[] = [
                        'rowspan' => $anInterBankNum,
                        'item_id' => $skuValues[$point]['item_id'],
                        'spec_value' => $skuValues[$point]['spec_value']
                    ];
                }
            }
            $sku['rows'] = $rowData;
        }
        return $specData;
    }

     /**
      * 获取商品详情
      * @param $goodsId
      * @return static
      */
     public static function detail($goodsId)
     {
         /* @var $model self */
         $model = (new static)->with([
             'category',
             'image.file',
             'sku.image',
             'spec_rel.spec',
             'delivery.rule',
         ])->where('goods_id', '=', $goodsId)
             ->find();
         if (empty($model)) {
             return $model;
         }
         // 整理列表数据并返回
         return $model->setGoodsListData($model, false);
     }

    /**
     * 商品多规格信息
     * @param $goods_sku_id
     * @return array|bool
     */
    public function getGoodsSku($goods_sku_id)
    {
        $goodsSkuData = array_column($this['sku']->toArray(), null, 'spec_sku_id');
        if (!isset($goodsSkuData[$goods_sku_id])) {
            return false;
        }
        $goods_sku = $goodsSkuData[$goods_sku_id];
        // 多规格文字内容
        $goods_sku['goods_attr'] = '';
        if ($this['spec_type'] == 20) {
            $attrs = explode('_', $goods_sku['spec_sku_id']);
            $spec_rel = array_column($this['spec_rel']->toArray(), null, 'spec_value_id');
            foreach ($attrs as $specValueId) {
                $goods_sku['goods_attr'] .= $spec_rel[$specValueId]['spec']['spec_name'] . ':'
                    . $spec_rel[$specValueId]['spec_value'] . '; ';
            }
        }
        return $goods_sku;
    }

    /**
     * 设置商品展示的数据
     * @param $data
     * @param bool $isMultiple
     * @param callable $callback
     * @return mixed
     */
    protected function setGoodsListData(&$data, $isMultiple = true, callable $callback = null)
    {
        if (!$isMultiple) $dataSource = [&$data]; else $dataSource = &$data;
        // 整理商品列表数据
        foreach ($dataSource as &$goods) {
            // 商品默认规格
            $goodsSku = $goods['sku'][0];
            // 处理商品图片
            $goods['image'] = format_image($goods['image']);
            // 商品默认数据
            $goods['goods_image'] = $goods['image'][0]['file_path'];
            $goods['goods_sku']   = $goodsSku;
            // 处理商品内容里面的图片
            $goods['content']     = $goods['content'];
            // 回调函数
            is_callable($callback) && call_user_func($callback, $goods);
        }
        return $data;
    }
}
