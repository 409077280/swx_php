<link rel="stylesheet" href="assets/store/css/goods.css?v=<?= $version ?>">
<link rel="stylesheet" href="assets/common/plugins/umeditor/themes/default/css/umeditor.css">
<link rel="stylesheet" href="assets/common/plugins/datetimepicker/amazeui.datetimepicker.css">
<style media="screen">
    .label_contribution {margin-bottom: 0;}
    .am-table tbody tr td {vertical-align: middle;}
    .data_result {text-align: left !important;}
</style>
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">基本信息</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="goods[goods_name]"
                                           value="" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品分类 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="goods[category_id]" required
                                            data-am-selected="{searchBox: 1, btnSize: 'sm',
                                             placeholder:'请选择商品分类', maxHeight: 400}">
                                        <option value=""></option>
                                        <?php if (isset($catgory)): foreach ($catgory as $first): ?>
                                            <option value="<?= $first['category_id'] ?>"><?= $first['name'] ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <?php if (checkPrivilege('apps.sharing.category/add')): ?>
                                        <small class="am-margin-left-xs">
                                            <a href="<?= url('apps.sharing.category/add') ?>">去添加</a>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <div class="am-form-file">
                                            <button type="button"
                                                    class="upload-file am-btn am-btn-secondary am-radius">
                                                <i class="am-icon-cloud-upload"></i> 选择图片
                                            </button>
                                            <div class="uploader-list am-cf">
                                            </div>
                                        </div>
                                        <div class="help-block am-margin-top-sm">
                                            <small>尺寸750x750像素以上，大小2M以下 (可拖拽图片调整显示顺序 )</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品卖点 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="goods[selling_point]" value="">
                                    <small>选填，商品卖点简述，例如：此款商品美观大方 性价比较高 不容错过</small>
                                </div>
                            </div>

                             <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品贡献率（%） </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="goods[contribution_rate]" id="txt_contribution_rate">
                                    <small>选填，商品贡献率，默认10%（只需填写数字10，不需填写%）</small>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">拼团设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">是否允许单买 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[is_alone]" value="0" data-am-ucheck checked>
                                        允许
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[is_alone]" value="1" data-am-ucheck>
                                        不允许
                                    </label>
                                    <div class="help-block">
                                        <small>是否允许用户选择不拼团单独购买，如果允许单买，请务必设置好单买价</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 成团人数 </label>
                                <div class="am-u-sm-10">
                                    <input type="number" min="2" class="tpl-form-input" name="goods[people]" value="2"
                                           required>
                                    <small>拼团成员的总人数，最低2人</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 成团有效时长 </label>
                                <div class="am-u-sm-10">
                                    <input type="number" min="1" class="tpl-form-input" name="goods[group_time]"
                                           value="24" required>
                                    <small>注：开团后的有效时间，单位：小时，超过时长则拼团失败</small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 开团时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" id="goods-start_time" name="goods[start_time]"
                                           value="<?= date('Y-m-d H:i') ?>" required>
                                    <small>注：拼团开始时间，默认为当前时间</small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 结束时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" id="goods-end_time" name="goods[end_time]"
                                           value="" autocomplete="off" required>
                                    <small>注：拼团结束时间，需手动选择</small>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">规格/库存</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品规格 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[spec_type]" value="10" data-am-ucheck checked>
                                        单规格
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[spec_type]" value="20" data-am-ucheck>
                                        多规格
                                    </label>
                                </div>
                            </div>

                            <!-- 商品多规格 -->
                            <div id="many-app" v-cloak class="goods-spec-many am-form-group">
                                <div class="goods-spec-box am-u-sm-9 am-u-sm-push-2 am-u-end">
                                    <!-- 规格属性 -->
                                    <div class="spec-attr">
                                        <div v-for="(item, index) in spec_attr" class="spec-group-item">
                                            <div class="spec-group-name">
                                                <span>{{ item.group_name }}</span>
                                                <i @click="onDeleteGroup(index)"
                                                   class="spec-group-delete iconfont icon-shanchu1" title="点击删除"></i>
                                            </div>
                                            <div class="spec-list am-cf">
                                                <div v-for="(val, i) in item.spec_items" class="spec-item am-fl">
                                                    <span>{{ val.spec_value }}</span>
                                                    <i @click="onDeleteValue(index, i)"
                                                       class="spec-item-delete iconfont icon-shanchu1" title="点击删除"></i>
                                                </div>
                                                <div class="spec-item-add am-cf am-fl">
                                                    <input type="text" v-model="item.tempValue"
                                                           class="ipt-specItem am-fl am-field-valid">
                                                    <button @click="onSubmitAddValue(index)" type="button"
                                                            class="am-btn am-fl">添加
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 添加规格组：按钮 -->
                                    <div v-if="showAddGroupBtn" class="spec-group-button">
                                        <button @click="onToggleAddGroupForm" type="button"
                                                class="am-btn">添加规格
                                        </button>
                                    </div>

                                    <!-- 添加规格：表单 -->
                                    <div v-if="showAddGroupForm" class="spec-group-add">
                                        <div class="spec-group-add-item am-form-group">
                                            <label class="am-form-label form-require">规格名 </label>
                                            <input type="text" class="input-specName tpl-form-input"
                                                   v-model="addGroupFrom.specName"
                                                   placeholder="请输入规格名称">
                                        </div>
                                        <div class="spec-group-add-item am-form-group">
                                            <label class="am-form-label form-require">规格值 </label>
                                            <input type="text" class="input-specValue tpl-form-input"
                                                   v-model="addGroupFrom.specValue"
                                                   placeholder="请输入规格值">
                                        </div>
                                        <div class="spec-group-add-item am-margin-top">
                                            <button @click="onSubmitAddGroup" type="button"
                                                    class="am-btn am-btn-xs am-btn-secondary"> 确定
                                            </button>
                                            <button @click="onToggleAddGroupForm" type="button"
                                                    class="am-btn am-btn-xs am-btn-default"> 取消
                                            </button>
                                        </div>
                                    </div>

                                    <!-- 商品多规格sku信息 -->
                                    <div v-if="spec_list.length > 0" class="goods-sku am-scrollable-horizontal">
                                        <!-- 分割线 -->
                                        <div class="goods-spec-line am-margin-top-lg am-margin-bottom-lg"></div>
                                        <!-- sku 批量设置 -->
                                        <div class="spec-batch am-form-inline">
                                            <div class="am-form-group">
                                                <label class="am-form-label">批量设置</label>
                                            </div>
                                            <div class="am-form-group">
                                                <input type="text" v-model="batchData.goods_no" placeholder="商家编码">
                                            </div>
                                            <div class="am-form-group">
                                                <input type="number" v-model="batchData.goods_price"
                                                       placeholder="单买价">
                                            </div>
                                            <div class="am-form-group">
                                                <input type="number" v-model="batchData.sharing_price"
                                                       placeholder="拼团价">
                                            </div>
                                            <div class="am-form-group">
                                                <input type="number" v-model="batchData.line_price"
                                                       placeholder="划线价">
                                            </div>
                                            <div class="am-form-group">
                                                <input type="number" v-model="batchData.cost_price"
                                                        placeholder="成本价">
                                            </div>
                                            <div class="am-form-group">
                                                <input type="number" min="0" v-model="batchData.stock_num"
                                                       placeholder="库存数量">
                                            </div>
                                            <div class="am-form-group">
                                                <input type="number" min="0" v-model="batchData.goods_weight"
                                                       placeholder="重量">
                                            </div>
                                            <div class="am-form-group">
                                                <button @click="onSubmitBatchData" type="button"
                                                        class="am-btn am-btn-sm am-btn-secondaryam-radius">确定
                                                </button>
                                            </div>
                                        </div>
                                        <!-- sku table -->
                                        <table class="spec-sku-tabel am-table am-table-bordered am-table-centered
                                     am-margin-bottom-xs am-text-nowrap">
                                            <tbody>
                                            <tr>
                                                <th v-for="item in spec_attr">{{ item.group_name }}</th>
                                                <th>规格图片</th>
                                                <th>商家编码</th>
                                                <th class="form-require">拼团价</th>
                                                <th class="">单买价</th>
                                                <th>划线价</th>
                                                <th>成本价</th>
                                                <th class="form-require">库存</th>
                                                <th class="form-require">重量(kg)</th>
                                                <th></th>
                                            </tr>
                                            <tr v-for="(item, index) in spec_list">
                                                <td v-for="td in item.rows" class="td-spec-value am-text-middle"
                                                    :rowspan="td.rowspan">
                                                    {{ td.spec_value }}
                                                </td>
                                                <td class="am-text-middle spec-image">
                                                    <div v-if="item.form.image_id" class="j-selectImg data-image"
                                                         v-bind:data-index="index">
                                                        <img :src="item.form.image_path" alt="">
                                                        <i class="iconfont icon-shanchu image-delete"
                                                           @click.stop="onDeleteSkuImage(index)"></i>
                                                    </div>
                                                    <div v-else class="j-selectImg upload-image"
                                                         v-bind:data-index="index">
                                                        <i class="iconfont icon-add"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="ipt-goods-no" name="goods_no"
                                                           v-model="item.form.goods_no">
                                                </td>
                                                <td class="sharing_price">
                                                    <input type="number" min="0.01" class="ipt-w80" name="sharing_price"
                                                           v-model="item.form.sharing_price" required onblur="setValue(this)">
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="ipt-w80" name="goods_price"
                                                           v-model="item.form.goods_price">
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="ipt-w80" name="line_price"
                                                           v-model="item.form.line_price">
                                                </td>
                                                <td class="cost_price">
                                                    <input type="number" min="0" class="ipt-w80" name="cost_price"
                                                           v-model="item.form.cost_price" onblur="setValue(this)">
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="ipt-w80" name="stock_num"
                                                           v-model="item.form.stock_num" required>
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="ipt-w80" name="goods_weight"
                                                           v-model="item.form.goods_weight" required>
                                                </td>
                                                <td class="data_result">
                                                    <div class="contribution">贡献：<label class="label_contribution">-</label></div>
                                                    <div class="profit">利润：<label class="label_contribution">-</label></div>
                                                    <div class="profit_rate">利润率：<label class="label_contribution">-</label></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="help-block">
                                            <small>注：如不允许单买，单买价设置为0即可</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 商品单规格 -->
                            <div class="goods-spec-single">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品编码 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input" name="goods[sku][goods_no]"
                                               value="">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">拼团价格 </label>
                                    <div class="am-u-sm-6 am-u-end">
                                        <input type="number" min="0.01" class="tpl-form-input"
                                               name="goods[sku][sharing_price]"
                                               required id="txt_goods_price">
                                    </div>
                                    <label class="am-u-sm-2 am-u-end am-form-label" style="text-align: left;">贡献：
                                        <span id="txt_contribution">-</span>
                                    </label>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">单买价格 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" min="0" placeholder="不能为0" class="tpl-form-input"
                                               name="goods[sku][goods_price]" value="" required>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品划线价 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" min="0" class="tpl-form-input"
                                               name="goods[sku][line_price]">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品成本价 </label>
                                    <div class="am-u-sm-6 am-u-end">
                                        <input type="number" class="tpl-form-input" name="goods[sku][cost_price]" id="txt_cost_price">
                                    </div>
                                    <label class="am-u-sm-2 am-u-end am-form-label" style="text-align: left;">
                                        利润：
                                        <span id="txt_profit">-</span>，
                                        利润率：
                                        <span id="txt_profit_rate">-</span>
                                    </label>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">当前库存数量 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" min="0" class="tpl-form-input" name="goods[sku][stock_num]"
                                               required>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品重量(Kg) </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" min="0" class="tpl-form-input"
                                               name="goods[sku][goods_weight]"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">库存计算方式 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[deduct_stock_type]" value="10" data-am-ucheck>
                                        下单减库存
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[deduct_stock_type]" value="20" data-am-ucheck
                                               checked>
                                        付款减库存
                                    </label>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">商品详情</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品视频 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="goods[vid]">
                                    <small>选填，商品视频VID</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品详情 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <!-- 加载编辑器的容器 -->
                                    <textarea id="container" name="goods[content]" type="text/plain"></textarea>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">其他设置</div>
                            </div>
                            <!-- <div class="am-form-group" id="support-store">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">门店自提 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[to_store]" value="1" data-am-ucheck>
                                        是
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[to_store]" value="0" data-am-ucheck checked>
                                        否
                                    </label>
                                </div>
                            </div> -->
                            <div class="am-form-group" id="express-templat">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">运费模板 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="goods[delivery_id]"
                                            data-am-selected="{searchBox: 1, btnSize: 'sm',  placeholder:'请选择运费模板'}" required>
                                        <option value="">请选择运费模板</option>
                                        <?php foreach ($delivery as $item): ?>
                                            <option value="<?= $item['delivery_id'] ?>">
                                                <?= $item['name'] ?> (<?= $item['method']['text'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="am-margin-left-xs">
                                        <a href="<?= url('setting.delivery/add') ?>">去添加</a>
                                    </small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品状态 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[goods_status]" value="10" data-am-ucheck
                                               checked>
                                        上架
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[goods_status]" value="20" data-am-ucheck>
                                        下架
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">初始销量</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" min="0" class="tpl-form-input" name="goods[sales_initial]"
                                           value="0">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品排序 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" min="0" class="tpl-form-input" name="goods[goods_sort]"
                                           value="100" required>
                                    <small>数字越小越靠前</small>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">分销设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">是否开启单独分销 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[is_ind_dealer]" value="0" data-am-ucheck
                                               checked>
                                        关闭
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[is_ind_dealer]" value="1" data-am-ucheck>
                                        开启
                                    </label>
                                </div>
                            </div>
                            <div class="widget-dealer__content hide">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">分销佣金类型 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" name="goods[dealer_money_type]" value="10" data-am-ucheck
                                                checked>
                                            百分比
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="goods[dealer_money_type]" value="20" data-am-ucheck>
                                            固定金额
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group am-padding-top-sm">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">单独分销设置 </label>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-5 am-u-end">
                                        <div class="am-input-group am-margin-bottom">
                                            <span class="am-input-group-label am-input-group-label__left">一级佣金：</span>
                                            <input type="text" name="goods[first_money]" value=""
                                                class="am-form-field">
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <div class="am-input-group am-margin-bottom">
                                            <span class="am-input-group-label am-input-group-label__left">二级佣金：</span>
                                            <input type="text" name="goods[second_money]" value=""
                                                class="am-form-field">
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <div class="am-input-group am-margin-bottom">
                                            <span class="am-input-group-label am-input-group-label__left">三级佣金：</span>
                                            <input type="text" name="goods[third_money]" value=""
                                                class="am-form-field">
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <div class="help-blockx">
                                            <p>
                                                <small>注：如需使用分销功能必须在 [分销中心 - 分销设置] 中开启</small>
                                            </p>
                                            <p>
                                                <small>注：如不开启单独分销则默认使用全局分销比例</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 图片文件列表模板 -->
{{include file="layouts/_template/tpl_file_item" /}}

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<script src="assets/common/js/vue.min.js"></script>
<script src="assets/common/js/ddsort.js"></script>
<script src="assets/common/plugins/umeditor/umeditor.config.js?v=<?= $version ?>"></script>
<script src="assets/common/plugins/umeditor/umeditor.min.js"></script>
<script src="assets/store/js/goods.spec.js?v=<?= $version ?>"></script>
<script src="assets/common/plugins/datetimepicker/amazeui.datetimepicker.js"></script>
<script src="assets/common/plugins/datetimepicker/zh-CN.js"></script>
<script>

    $(function () {

        // 富文本编辑器
        UM.getEditor('container', {
            initialFrameWidth: 375 + 15,
            initialFrameHeight: 600
        });

        // 选择图片
        $('.upload-file').selectImages({
            name: 'goods[images][]'
            , multiple: true
        });

        // 图片列表拖动
        $('.uploader-list').DDSort({
            target: '.file-item',
            delay: 100, // 延时处理，默认为 50 ms，防止手抖点击 A 链接无效
            floatStyle: {
                'border': '1px solid #ccc',
                'background-color': '#fff'
            }
        });

        // 切换单/多规格
        $('input:radio[name="goods[spec_type]"]').change(function (e) {
            var $goodsSpecMany = $('.goods-spec-many')
                , $goodsSpecSingle = $('.goods-spec-single');
            if (e.currentTarget.value === '10') {
                $goodsSpecMany.hide() && $goodsSpecSingle.show();
            } else {
                $goodsSpecMany.show() && $goodsSpecSingle.hide();
            }
        });

        // 注册商品多规格组件
        var specMany = new GoodsSpec({
            el: '#many-app'
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm({
            // 获取多规格sku数据
            buildData: function () {
                var specData = specMany.appVue.getData();
                return {
                    goods: {
                        spec_many: {
                            spec_attr: specData.spec_attr,
                            spec_list: specData.spec_list
                        }
                    }
                };
            },
            // 自定义验证
            validation: function () {
                var specType = $('input:radio[name="goods[spec_type]"]:checked').val();
                if (specType === '20') {
                    var isEmpty = specMany.appVue.isEmptySkuList();
                    isEmpty === true && layer.msg('商品规格不能为空');
                    return !isEmpty;
                }
                return true;
            }
        });

        // 是否开启单独分销
        $("input:radio[name='goods[is_ind_dealer]']").change(function(e) {
            var $content = $('.widget-dealer__content');
            e.currentTarget.value == '0' ? $content.hide() : $content.show();
        });

        // 选中百分比 后面显示% 选中固定金额 后面显示元
        $("input:radio[name='goods[dealer_money_type]']").change(function(e) {
            $('.widget-dealer__unit').text(e.currentTarget.value == '10' ? '%' : '元');
        });

        $("#goods-start_time").datetimepicker({
            language:  'zh-CN',
            format: 'yyyy-mm-dd hh:ii'
        });

        $("#goods-end_time").datetimepicker({
            language:  'zh-CN',
            format: 'yyyy-mm-dd hh:ii'
        });

        $("#support-store").find("input[type='radio']").on("click", function() {
            if($(this).is(":checked")) {
                var toStore = $(this).val();
                if(toStore == 1)
                  $("#express-templat").hide();
                else
                  $("#express-templat").show();
            }
        });

        // 计算贡献
        $("#txt_goods_price").on("blur", function() {
            var contributionRate = parseFloat($.trim($("#txt_contribution_rate").val())) / 100;
            var goodsPrice = parseFloat($.trim($("#txt_goods_price").val()));
            $("#txt_contribution").html(parseFloat(goodsPrice * contributionRate).toFixed(2));
        });

        // 计算利润和利润率
        $("#txt_cost_price").on("blur", function() {
            var contribution = parseFloat($("#txt_contribution").text());
            var costPrice  = parseFloat($.trim($("#txt_cost_price").val()));
            var price      = parseFloat($.trim($("#txt_goods_price").val()));
            var profit     = parseFloat($.trim($("#txt_goods_price").val())) - costPrice - contribution;
            var profitRate = ((profit / price) * 100).toFixed(2) + '%';
            $("#txt_profit").html(profit.toFixed(2));
            $("#txt_profit_rate").html(profitRate);
        });
    });

    function setValue(obj) {
        var profitRate = profit = contribution = 0;
        var trbox = $(obj).parent().parent();
        console.log(trbox.html());
        var contributionRate = parseFloat($("#txt_contribution_rate").val());
        var price     = parseFloat(trbox.children("td.sharing_price").children("input").val());
        var costPrice = parseFloat(trbox.children("td.cost_price").children("input").val());
        if(price && contributionRate)
            contribution = price * (contributionRate / 100);
        if(price && costPrice)
            profitRate = ((price - costPrice - contribution) / price) * 100;
        if(price && costPrice)
            profit = price - costPrice - contribution;

        trbox.children("td.data_result").children("div.contribution").children("label").text(contribution.toFixed(2));
        trbox.children("td.data_result").children("div.profit").children("label").text(profit.toFixed(2));
        trbox.children("td.data_result").children("div.profit_rate").children("label").text(profitRate.toFixed(2) + '%');
    }
</script>
