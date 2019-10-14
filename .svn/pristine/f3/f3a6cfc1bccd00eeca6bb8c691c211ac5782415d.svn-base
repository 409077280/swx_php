<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">提现设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 提现方式 </label>
                                <div class="am-u-sm-9">
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="withdraw[pay_type][]" value="10"
                                               data-am-ucheck
                                            <?= in_array('10', $values['pay_type']) ? 'checked' : '' ?>>
                                        微信支付
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="withdraw[pay_type][]" value="20"
                                               data-am-ucheck
                                            <?= in_array('20', $values['pay_type']) ? 'checked' : '' ?>>
                                        支付宝
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="withdraw[pay_type][]" value="30"
                                               data-am-ucheck
                                            <?= in_array('30', $values['pay_type']) ? 'checked' : '' ?>>
                                        银行卡
                                    </label>
                                    <div class="help-block">
                                        <small>注：如使用微信支付，则需申请微信支付企业付款到零钱功能</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">
                                    最低提现额度
                                </label>
                                <div class="am-u-sm-9">
                                    <input type="number" min="0" class="tpl-form-input"
                                           name="withdraw[min_money]"
                                           value="<?= $values['min_money'] ?>"
                                           required>
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
<script>
    $(function () {

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
