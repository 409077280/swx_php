<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div id="app" class="widget am-cf" v-cloak>
                    <form id="my-form" class="am-form tpl-form-line-form" method="post">
                        <div class="widget-body">
                            <fieldset>
                                <div class="widget-head am-cf">
                                    <div class="widget-title am-fl">新用户注册</div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label form-require"> 是否开启奖励 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" name="newbie[register][is_open]" value="1" data-am-ucheck
                                                <?= $values['register']['is_open'] ? 'checked' : '' ?>> 开启
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="newbie[register][is_open]" value="0" data-am-ucheck
                                                <?= $values['register']['is_open'] ? '' : 'checked' ?>> 关闭
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 新用户奖励贡献 </label>
                                    <div class="am-u-sm-9  am-u-end">
                                        <input type="text" min="0" class="tpl-form-input"
                                               name="newbie[register][self][contribution]"
                                               value="<?= $values['register']['self']['contribution'] ?>">
                                       <div class="help-block">
                                           <small>注：随机贡献，请用逗号分隔开</small>
                                       </div>
                                    </div>
                                </div>

                                <!-- <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 新用户奖励分红 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" min="0" class="tpl-form-input"
                                               name="newbie[register][self][bonus]"
                                               value="<?= $values['register']['self']['bonus'] ?>">
                                    </div>
                                </div> -->

                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 推荐人奖励贡献 </label>
                                    <div class="am-u-sm-9  am-u-end">
                                        <input type="text" min="0" class="tpl-form-input"
                                               name="newbie[register][referee][contribution]"
                                               value="<?= $values['register']['referee']['contribution'] ?>">
                                       <div class="help-block">
                                           <small>注：随机贡献，请用逗号分隔开</small>
                                       </div>
                                    </div>
                                </div>

                                <!-- <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 推荐人奖励分红 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" min="0" class="tpl-form-input"
                                               name="newbie[register][referee][bonus]"
                                               value="<?= $values['register']['referee']['bonus'] ?>">
                                    </div>
                                </div> -->

                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 大红包贡献奖励 </label>
                                    <div class="am-u-sm-9  am-u-end">
                                        <input type="text" min="0" class="tpl-form-input"
                                               name="newbie[register][bigbonus]"
                                               value="<?= isset($values['register']['bigbonus']) ? $values['register']['bigbonus'] : '' ?>">
                                       <div class="help-block">
                                           <small>注：大红包贡献奖励填写格式：大红包1|概率，大红包2|概率（参考：8.88|0.05，88.88|0.005）</small>
                                       </div>
                                    </div>
                                </div>

                                <div class="widget-head am-cf">
                                    <div class="widget-title am-fl">新用户首单</div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label form-require"> 是否开启奖励 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" name="newbie[first_order][is_open]" value="1" data-am-ucheck
                                                <?= $values['first_order']['is_open'] ? 'checked' : '' ?>> 开启
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="newbie[first_order][is_open]" value="0" data-am-ucheck
                                                <?= $values['first_order']['is_open'] ? '' : 'checked' ?>> 关闭
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 推荐人奖励贡献（%） </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" min="0" class="tpl-form-input"
                                               name="newbie[first_order][referee]"
                                               value="<?= $values['first_order']['referee'] ?>">
                                       <div class="help-block">
                                           <small>注：奖励被推荐人首单贡献的比例，只需填写数字</small>
                                       </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 推荐奖励有效期（天） </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input"
                                               name="newbie[first_order][howlong]"
                                               value="<?= $values['first_order']['howlong'] ?>">
                                       <div class="help-block">
                                           <small>注：被推荐人多少天内首次下单，推荐人将获得贡献，为0表示不限制，一直有效</small>
                                       </div>
                                    </div>
                                </div>

                                <div class="widget-head am-cf">
                                    <div class="widget-title am-fl">爆品设置</div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 新用户爆品 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <div class="widget-become-goods am-form-file am-margin-top-xs">
                                            <button type="button" @click.stop="onSelectGoods"
                                                    class="j-selectGoods upload-file am-btn am-btn-secondary am-radius">
                                                <i class="am-icon-cloud-upload"></i> 选择商品
                                            </button>
                                            <div class="help-block">
                                                <small>注：小程序端只支持展示一个爆款商品</small>
                                            </div>
                                            <div class="widget-goods-list uploader-list am-cf">
                                                <div v-for="(item, index) in goodsList" class="file-item">
                                                    <a :href="item.goods_image" :title="item.goods_name" target="_blank">
                                                        <img :src="item.goods_image">
                                                    </a>
                                                    <input type="hidden" name="newbie[goods_id][]" :value="item.goods_id">
                                                    <i class="iconfont icon-shanchu file-item-delete"
                                                       data-no-click="true" @click.stop="onDeleteGoods(index)"></i>
                                                </div>
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
</div>

<script src="assets/common/js/vue.min.js?v=<?= $version ?>"></script>
<script src="assets/store/js/select.data.js?v=<?= $version ?>"></script>
<script>
    $(function () {
        // 不参与包邮的地区：选择地区
        var app = new Vue({
            el: '#app',
            data: {
                // 商品列表
                goodsList: <?= json_encode($goodsList) ?>,
            },
            methods: {
                // 选择商品
                onSelectGoods: function () {
                    var app = this;
                    $.selectData({
                        title: '选择商品',
                        uri: 'goods/lists&status=10',
                        duplicate: false,
                        dataIndex: 'goods_id',
                        done: function (data) {
                            data.forEach(function (item) {
                                // app.goodsList.push(item);
                                app.goodsList = [item];
                            });
                        },
                        getExistData: function () {
                            var goodsIds = [];
                            app.goodsList.forEach(function (item) {
                                goodsIds.push(item.goods_id);
                            });
                            return goodsIds;
                        }
                    });
                },

                // 删除商品
                onDeleteGoods: function (index) {
                    var app = this;
                    return app.goodsList.splice(index, 1);
                }
            }
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
