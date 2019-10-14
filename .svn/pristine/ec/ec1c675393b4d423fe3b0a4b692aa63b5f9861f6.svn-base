<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">商品分享购买</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3  am-u-lg-2 am-form-label form-require"> 是否开启奖励 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods_share[is_open]" value="1" data-am-ucheck
                                            <?= $values['is_open'] ? 'checked' : '' ?>> 开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods_share[is_open]" value="0" data-am-ucheck
                                            <?= $values['is_open'] ? '' : 'checked' ?>> 关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">奖励有效期</label>
                                <div class="am-u-sm-9 am-u-md-6 am-u-lg-5 am-u-end">
                                    <div class="am-input-group">
                                        <input type="number" name="goods_share[expires]"
                                               value="<?= $values['expires'] ?>"
                                               class="am-form-field am-field-valid" required>
                                        <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">天</span>
                                    </div>
                                    <small>分享后被推荐人多少天之内购买，推荐人才能获得奖励</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3  am-u-lg-2 am-form-label form-require"> 推荐人奖励贡献</label>
                                <div class="am-u-sm-9 am-u-md-6 am-u-lg-5 am-u-end">
                                    <div class="am-input-group">
                                        <input type="number" min="0" class="am-form-field am-field-valid"
                                               name="goods_share[referee]"
                                               value="<?= $values['referee'] ?>" required>
                                        <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">%</span>
                                    </div>
                                   <div class="help-block">
                                       <small>奖励被推荐人此单贡献的比例，只需填写数字</small>
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

<script>
    $(function () {
        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
