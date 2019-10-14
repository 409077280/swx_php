<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div id="app" class="widget am-cf" v-cloak>
                    <form id="my-form" class="am-form tpl-form-line-form" method="post">
                        <div class="widget-body">
                            <fieldset>
                                <div class="widget-head am-cf">
                                    <div class="widget-title am-fl">合伙人设置</div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3  am-u-lg-2 am-form-label"> 是否开启加盟 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" name="partner[is_open]" value="1" data-am-ucheck
                                                <?= $values['is_open'] ? 'checked' : '' ?>> 开启
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="partner[is_open]" value="0" data-am-ucheck
                                                <?= $values['is_open'] ? '' : 'checked' ?>> 关闭
                                        </label>
                                    </div>
                                </div>


                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">合伙人加盟条件</label>
                                    <div class="am-u-md-3">
                                        <div class="widget-become-goods am-form-file am-margin-top-xs">
                                            <button type="button" @click.stop="onSelectRecharge"
                                                    class="j-selectRecharge upload-file am-btn am-btn-secondary am-radius">
                                                选择充值套餐
                                            </button>
                                            <div class="widget-goods-list uploader-list am-cf">
                                                <div v-for="(item, index) in planList" class="file-item" style="margin-top:5px;">
                                                    <span>{{item.plan_name}}</span>
                                                    <input type="hidden" name="partner[condition][recharge_id]" :value="item.plan_id">
                                                    <i class="iconfont icon-shanchu file-item-delete"
                                                       data-no-click="true" @click.stop="onDeleteRecharge(index)"></i>
                                                </div>
                                            </div>
                                            <small>条件1：充值金额</small>
                                        </div>
                                    </div>
                                    <div style="width: 50px;float: left;margin-top: 35px;">
                                        <span>或</span>
                                    </div>
                                    <div style="margin-top: 10px;">
                                        <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                            <div class="am-input-group">
                                                <input type="number" name="partner[condition][referrals]"
                                                       value="<?= $values['condition']['referrals'] ?>"
                                                       class="am-form-field am-field-valid" required>
                                                <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">人</span>
                                            </div>
                                            <small>条件2：达到最少邀请用户数</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget-head am-cf">
                                    <div class="widget-title am-fl">权益设置</div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">邀请新人</label>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                        <div class="am-input-group">
                                            <input type="number" name="partner[rights][newbie]"
                                                   value="<?= $values['rights']['newbie'] ?>"
                                                   class="am-form-field am-field-valid" required>
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <small>获邀请用户购物交易额的分红比例</small>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">推荐合伙人</label>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                        <div class="am-input-group">
                                            <input type="number" name="partner[rights][partner][recharge]"
                                                   value="<?= $values['rights']['partner']['recharge'] ?>"
                                                   class="am-form-field am-field-valid" required>
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <small>获被邀合伙人充值金额比例</small>
                                    </div>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-5 am-u-end">
                                        <div class="am-input-group">
                                            <input type="number" name="partner[rights][partner][bonus]"
                                                   value="<?= $values['rights']['partner']['bonus'] ?>"
                                                   class="am-form-field am-field-valid" required>
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <small>获被邀合伙人分红（邀请用户分红+溢出共享分红）比例</small>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">溢出共享分红</label>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                        <div class="am-input-group">
                                            <input type="number" name="partner[rights][platform][bonus]"
                                                   value="<?= $values['rights']['platform']['bonus'] ?>"
                                                   class="am-form-field am-field-valid" required>
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <small>获溢出共享分红比例</small>
                                    </div>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                        <div class="am-input-group">
                                            <input type="number" name="partner[rights][platform][weight][users]"
                                                   value="<?= $values['rights']['platform']['weight']['users'] ?>"
                                                   class="am-form-field am-field-valid" required>
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <small>权重：邀请用户数占比</small>
                                    </div>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                        <div class="am-input-group">
                                            <input type="number" name="partner[rights][platform][weight][contribution]"
                                                   value="<?= $values['rights']['platform']['weight']['contribution'] ?>"
                                                   class="am-form-field am-field-valid" required>
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                        </div>
                                        <small>权重：邀请用户贡献值占比</small>
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">获得溢出分红条件</label>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                        <div class="am-input-group">
                                            <input type="number" name="partner[rights][platform][condition][recharge_amount]"
                                                   value="<?= $values['rights']['platform']['condition']['recharge_amount'] ?>"
                                                   class="am-form-field am-field-valid" required>
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">元</span>
                                        </div>
                                        <small>条件1：充值总金额</small>
                                    </div>
                                    <div style="width: 18px;float: left;margin-top: 5px;">
                                        <span>或</span>
                                    </div>
                                    <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                        <div class="am-input-group">
                                            <input type="number" name="partner[rights][platform][condition][referrals]"
                                                   value="<?= $values['rights']['platform']['condition']['referrals'] ?>"
                                                   class="am-form-field am-field-valid" required>
                                            <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">人</span>
                                        </div>
                                        <small>条件2：邀请用户总数</small>
                                    </div>
                                    <!-- <div class="am-u-md-3">
                                        <div class="widget-become-goods am-form-file am-margin-top-xs">
                                            <button type="button" @click.stop="onSelectRecharge('platform')"
                                                    class="j-selectRecharge upload-file am-btn am-btn-secondary am-radius">
                                                选择充值套餐
                                            </button>
                                            <div class="widget-goods-list uploader-list am-cf">
                                                <div v-for="(item, index) in planListPlatform" class="file-item" style="margin-top:5px;">
                                                    <span>{{item.plan_name}}</span>
                                                    <input type="hidden" name="partner[rights][platform][condition][recharge_id]" :value="item.plan_id">
                                                    <i class="iconfont icon-shanchu file-item-delete"
                                                       data-no-click="true" @click.stop="onDeleteRecharge(index, 'platform')"></i>
                                                </div>
                                            </div>
                                            <small>条件1：充值金额</small>
                                        </div>
                                    </div>

                                    <div style="margin-top: 10px;">
                                        <div class="am-u-sm-9 am-u-md-6 am-u-lg-3 am-u-end">
                                            <div class="am-input-group">
                                                <input type="number" name="partner[rights][platform][condition][referrals]"
                                                       value="<?= $values['rights']['platform']['condition']['referrals'] ?>"
                                                       class="am-form-field am-field-valid" required>
                                                <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">人</span>
                                            </div>
                                            <small>条件2：邀请用户数</small>
                                        </div>
                                    </div> -->
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
        var app = new Vue({
            el: '#app',
            data: {
                planList: <?= json_encode($planList) ?>,
                planListPlatform: <?= json_encode($planListPlatform) ?>,
            },
            methods: {
                // 选择商品
                onSelectRecharge: function (tag = 'platform') {
                    var app = this;
                    $.selectData({
                        title: '选择充值套餐',
                        uri: 'rechargeplan/lists&is_delete=0',
                        duplicate: false,
                        dataIndex: 'plan_id',
                        done: function (data) {
                            data.forEach(function (item) {
                                if(tag == 'platform')
                                    app.planListPlatform = [item];
                                else
                                    app.planList = [item];
                            });
                        },
                        getExistData: function () {
                            var planIds = [];
                            app.planList.forEach(function (item) {
                                planIds.push(item.plan_id);
                            });
                            return planIds;
                        }
                    });
                },

                // 删除
                onDeleteRecharge: function (index, tag = 'platform') {
                    var app = this;
                    if(tag == 'platform')
                        return app.planList.splice(index, 1);
                    else
                        return app.planListPlatform.splice(index, 1);
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
