<?php

use app\common\enum\DeliveryType as DeliveryTypeEnum;

?>
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf"><?= $title ?></div>
                </div>
                <div class="widget-body am-fr">
                    <!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom-xs am-cf">
                        <form id="form-search" class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/<?= $request->pathinfo() ?>">
                            <input type="hidden" name="dataType" value="<?= $dataType ?>">
                            <div class="am-u-sm-12 am-u-md-3">
                                <div class="am-form-group">
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            <?php if (checkPrivilege('order.operate/export')): ?>
                                                <a class="j-export am-btn am-btn-success am-radius"
                                                   href="javascript:void(0);">
                                                    <i class="iconfont icon-daochu am-margin-right-xs"></i>订单导出
                                                </a>
                                            <?php endif; ?>
                                            <?php if (checkPrivilege('order.operate/batchdelivery')): ?>
                                                <?php if (in_array($dataType, ['all', 'delivery'])): ?>
                                                    <a class="j-export am-btn am-btn-secondary am-radius"
                                                       href="<?= url('order.operate/batchdelivery') ?>">
                                                        <i class="iconfont icon-daoru am-margin-right-xs"></i>批量发货
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="am-u-sm-12 am-u-md-9">
                                <div class="am fr">
                                    <div class="am-form-group am-fl">
                                        <?php $deliveryType = $request->get('delivery_type'); ?>
                                        <select name="delivery_type"
                                                data-am-selected="{btnSize: 'sm', placeholder: '配送方式'}">
                                            <option value=""></option>
                                            <option value="-1"
                                                <?= $deliveryType === '-1' ? 'selected' : '' ?>>全部
                                            </option>
                                            <?php foreach (DeliveryTypeEnum::data() as $item): ?>
                                                <option value="<?= $item['value'] ?>"
                                                    <?= $item['value'] == $deliveryType ? 'selected' : '' ?>><?= $item['name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="am-form-group am-fl">
                                        <?php $extractShopId = $request->get('extract_shop_id'); ?>
                                        <select name="extract_shop_id"
                                                data-am-selected="{btnSize: 'sm', placeholder: '自提门店名称'}">
                                            <option value=""></option>
                                            <option value="-1"
                                                <?= $extractShopId === '-1' ? 'selected' : '' ?>>全部
                                            </option>
                                            <?php if (isset($shopList)): foreach ($shopList as $item): ?>
                                                <option value="<?= $item['shop_id'] ?>"
                                                    <?= $item['shop_id'] == $extractShopId ? 'selected' : '' ?>><?= $item['shop_name'] ?>
                                                </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                    <div class="am-form-group tpl-form-border-form am-fl">
                                        <input type="text" name="start_time"
                                               class="am-form-field"
                                               value="<?= $request->get('start_time') ?>" placeholder="请选择起始日期"
                                               data-am-datepicker autocomplete="off">
                                    </div>
                                    <div class="am-form-group tpl-form-border-form am-fl">
                                        <input type="text" name="end_time"
                                               class="am-form-field"
                                               value="<?= $request->get('end_time') ?>" placeholder="请选择截止日期"
                                               data-am-datepicker autocomplete="off">
                                    </div>
                                    <div class="am-form-group am-fl" style="max-width: 406px;">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                            <input type="text" class="am-form-field" name="search"
                                                   placeholder="请输入订单号/商品名称/收货人姓名/电话/用户昵称" value="<?= $request->get('search') ?>">
                                            <div class="am-input-group-btn">
                                                <button class="am-btn am-btn-default am-icon-search"
                                                        type="submit"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="order-list am-scrollable-horizontal am-u-sm-12 am-margin-top-xs">
                        <table width="100%" class="am-table am-table-centered
                        am-text-nowrap am-margin-bottom-xs">
                            <thead>
                            <tr style="border:none;border-bottom: 2px solid #ddd;">
                                <th width="30%" class="goods-detail">商品信息</th>
                                <th width="10%">单价/数量</th>
                                <th width="15%">实付款</th>
                                <th>买家</th>
                                <th>配送方式</th>
                                <th>交易状态</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $order): ?>
                                <tr class="order-empty">
                                    <td colspan="6"></td>
                                </tr>
                                <tr>
                                    <?php if($order['order_status']['value'] == 21):?>
                                    <td class="am-text-middle am-text-left" colspan="7">
                                    <?php else: ?>
                                    <td class="am-text-middle am-text-left" colspan="7">
                                    <?php endif; ?>
                                        <span class="am-margin-right-lg"> <?= $order['create_time'] ?></span>
                                        <span class="am-margin-right-lg">订单号：<?= $order['order_no'] ?></span>
                                        <span class="tpl-table-black-operation" style="float:right;">
                                            <?php if (checkPrivilege('order/detail')): ?>
                                                <a class="tpl-table-black-operation-green" style="display:inline;"
                                                   href="<?= url('order/detail', ['order_id' => $order['order_id']]) ?>">
                                                    订单详情</a>
                                            <?php endif; ?>
                                            <?php if (checkPrivilege(['order/detail', 'order/delivery'])): ?>
                                                <?php if ($order['pay_status']['value'] == 20
                                                    && $order['delivery_type']['value']==10
                                                    && $order['delivery_status']['value'] == 10
                                                    && $order['order_status']['value'] != 20
                                                    && $order['order_status']['value'] != 21

                                                ): ?>
                                                    <a class="tpl-table-black-operation" style="display:inline;"
                                                       href="<?= url('order/detail#delivery',
                                                           ['order_id' => $order['order_id']]) ?>">去发货</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php $i = 0;
                                foreach ($order['goods'] as $goods): $i++; ?>
                                    <tr>
                                        <td class="goods-detail am-text-middle">
                                            <div class="goods-image">
                                                <img src="<?= $goods['image']['file_path'] ?>" alt="">
                                            </div>
                                            <div class="goods-info">
                                                <p class="goods-title"><?= $goods['goods_name'] ?></p>
                                                <p class="goods-spec am-link-muted"><?= $goods['goods_attr'] ?></p>
                                            </div>
                                        </td>
                                        <td class="am-text-middle">
                                            <p>￥<?= $goods['goods_price'] ?></p>
                                            <p>×<?= $goods['total_num'] ?></p>
                                        </td>
                                        <?php if ($i === 1) : $goodsCount = count($order['goods']); ?>
                                            <td class="am-text-middle" rowspan="<?= $goodsCount ?>">
                                                <p>￥<?= $order['pay_price'] ?></p>
                                                <p class="am-link-muted">(含运费：￥<?= $order['express_price'] ?>)</p>
                                            </td>
                                            <td class="am-text-middle" rowspan="<?= $goodsCount ?>">
                                                <p><?= $order['user']['nickName'] ?></p>
                                                <p class="am-link-muted">(用户id：<?= $order['user']['user_id'] ?>)</p>
                                            </td>
                                            <td class="am-text-middle" rowspan="<?= $goodsCount ?>">
                                                <span class="am-badge am-badge-secondary">
                                                    <?= $order['delivery_type']['text'] ?>
                                                </span>
                                            </td>
                                            <?php if (checkPrivilege(['order/detail', 'order.operate/confirmcancel'])): ?>
                                                <?php if ($order['order_status']['value'] == 21): ?>
                                                <td class="am-text-middle" rowspan="<?= $goodsCount ?>">
                                                <?php else: ?>
                                                    <td class="am-text-middle" rowspan="<?= $goodsCount ?>" colspan="2">
                                                <?php endif; ?>
                                            <?php else:?>
                                                <td class="am-text-middle" rowspan="<?= $goodsCount ?>" colspan="2">
                                            <?php endif; ?>
                                                <p>付款状态：
                                                    <span class="am-badge
                                                <?= $order['pay_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                        <?= $order['pay_status']['text'] ?></span>
                                                </p>
                                                <?php if($order['delivery_type']['value'] == '10'): ?>
                                                    <p>发货状态：
                                                        <span class="am-badge
                                                    <?= $order['delivery_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                            <?= $order['delivery_status']['text'] ?></span>
                                                    </p>
                                                    <p>收货状态：
                                                        <span class="am-badge
                                                    <?= $order['receipt_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                            <?= $order['receipt_status']['text'] ?></span>
                                                    </p>
                                                <?php endif; ?>
                                                <?php if ($order['order_status']['value'] == 20 || $order['order_status']['value'] == 21): ?>
                                                    <!-- <p>订单状态：
                                                        <span class="am-badge am-badge-warning"><?= $order['order_status']['text'] ?></span>
                                                    </p> -->
                                                <?php endif; ?>
                                                <?php if($order['delivery_type']['value'] == 20): ?>
                                                    <p>订单状态：
                                                        <span class="am-badge <?= $order['receipt_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                            <?= $order['receipt_status']['value'] == 20 ? '已完成' : '未完成' ?>
                                                        </span>
                                                    </p>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                        <?php if (checkPrivilege(['order/detail', 'order.operate/confirmcancel'])): ?>
                                            <?php if ($goods['status'] == 10): ?>
                                                <td class="am-text-middle">
                                                    <div class="tpl-table-black-operation">
                                                        <span>申请取消</span>
                                                        <a class="tpl-table-black-operation-del j-audit" style="margin-top:0"
                                                            href="javascript:void(0);"
                                                           data-url="<?= url('order.operate/confirmcancel',
                                                               ['order_id' => $order['order_id'], 'goods_id' => $goods['goods_id'], 'goods_sku_id' => $goods['goods_sku_id']]) ?>">审核</a>
                                                    </div>
                                                </td>
                                            <?php else: ?>
                                                <?php if($order['order_status']['value'] == 21): ?>
                                                    <td></td>
                                                <?php endif;?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="am-u-lg-12 am-cf">
                        <div class="am-fr"><?= $list->render() ?> </div>
                        <div class="am-fr pagination-total am-margin-right">
                            <div class="am-vertical-align-middle">总记录：<?= $list->total() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 商品取消审核 -->
<div class="am-padding-top-sm" id="j-goods-cancel" style="display:none;">
    <form class="form-goods-cancel-audit am-form tpl-form-line-form" method="post">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label"> 审核状态 </label>
            <div class="am-u-sm-9">
                <label class="am-radio-inline">
                    <input type="radio" name="cancel[is_agree]" value="10" data-am-ucheck
                           checked> 同意
                </label>
                <label class="am-radio-inline">
                    <input type="radio" name="cancel[is_agree]" value="20" data-am-ucheck> 拒绝
                </label>
            </div>
        </div>
    </form>
</div>


<script>
    $(function () {
        /**
         * 订单导出
         */
        $('.j-export').click(function () {
            var data = {};
            var formData = $('#form-search').serializeArray();
            $.each(formData, function () {
                this.name !== 's' && (data[this.name] = this.value);
            });
            window.location = "<?= url('order.operate/export') ?>" + '&' + $.urlEncode(data);
        });

        // 审核操作
        $('.j-audit').click(function () {
            var $this = $(this);
            var url = $this.data('url');
            $('.form-goods-cancel-audit').attr('action', url);
            layer.open({
                type: 1
                , title: '商品取消审核'
                , area: '340px'
                , offset: 'auto'
                , anim: 1
                , closeBtn: 1
                , shade: 0.3
                , btn: ['确定', '取消']
                , content: $("#j-goods-cancel").html()
                , success: function (layero) {
                    // 注册radio组件
                    layero.find('input[type=radio]').uCheck();
                }
                , yes: function (index, layero) {
                    // 表单提交
                    layero.find('.form-goods-cancel-audit').ajaxSubmit({
                        type: 'post',
                        dataType: 'json',
                        success: function (result) {
                            result.code === 1 ? $.show_success(result.msg, result.url)
                                : $.show_error(result.msg);
                        }
                    });
                    layer.close(index);
                }
            });
        });
    });

</script>
