<?php

use app\common\enum\DeliveryType as DeliveryTypeEnum;
// 订单详情
$detail = isset($detail) ? $detail : null;

?>
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<style media="screen">
    .btn-add, .btn-minus {
        border: 1px dashed #999;
        padding: 5px;
        cursor: pointer;
    }
    .el-select {
        width: 100%;
    }
</style>
<div class="row-content am-cf" id="app">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget__order-detail widget-body am-margin-bottom-lg">

                    <!-- 订单进度步骤条 -->
                    <div class="am-u-sm-12">
                        <?php
                        // 计算当前步骤位置
                        $progress = 2;
                        $detail['pay_status']['value'] == 20 && $progress += 1;
                        $detail['delivery_status']['value'] == 20 && $progress += 1;
                        $detail['receipt_status']['value'] == 20 && $progress += 1;
                        // $detail['order_status']['value'] == 30 && $progress += 1;
                        ?>
                        <ul class="order-detail-progress progress-<?= $progress ?>">
                            <li>
                                <span>下单时间</span>
                                <div class="tip"><?= $detail['create_time'] ?></div>
                            </li>
                            <li>
                                <span>付款</span>
                                <?php if ($detail['pay_status']['value'] == 20): ?>
                                    <div class="tip">
                                        付款于 <?= date('Y-m-d H:i:s', $detail['pay_time']) ?>
                                    </div>
                                <?php endif; ?>
                            </li>
                            <?php if($detail['delivery_type']['value'] == 10): ?>
                                <li>
                                    <span>发货</span>
                                    <?php if ($detail['delivery_status']['value'] == 20): ?>
                                        <div class="tip">
                                            发货于 <?= date('Y-m-d H:i:s', $detail['delivery_time']) ?>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>

                            <?php if($detail['delivery_type']['value'] == 10): ?>
                                <li>
                                    <span>收货</span>
                                    <?php if ($detail['receipt_status']['value'] == 20): ?>
                                        <div class="tip">
                                            收货于 <?= date('Y-m-d H:i:s', $detail['receipt_time']) ?>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                            <li>
                                <span>完成</span>
                                <?php if ($detail['order_status']['value'] == 30): ?>
                                    <div class="tip">
                                        完成于 <?= date('Y-m-d H:i:s', $detail['receipt_time']) ?>
                                    </div>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>

                    <!-- 基本信息 -->
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">基本信息</div>
                    </div>
                    <div class="am-scrollable-horizontal">
                        <table class="regional-table am-table am-table-bordered am-table-centered
                            am-text-nowrap am-margin-bottom-xs">
                            <tbody>
                            <tr>
                                <th>订单号</th>
                                <th>买家</th>
                                <th>订单金额</th>
                                <th>配送方式</th>
                                <th>交易状态</th>
                                <?php if ($detail['pay_status']['value'] == 10 && $detail['order_status']['value'] == 10) : ?>
                                    <th>操作</th>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td><?= $detail['order_no'] ?></td>
                                <td>
                                    <p><?= $detail['user']['nickName'] ?></p>
                                    <p class="am-link-muted">(用户id：<?= $detail['user']['user_id'] ?>)</p>
                                </td>
                                <td class="">
                                    <div class="td__order-price am-text-left">
                                        <ul class="am-avg-sm-2">
                                            <li class="am-text-right">订单总额：</li>
                                            <li class="am-text-right">￥<?= $detail['total_price'] ?> </li>
                                        </ul>
                                        <?php if ($detail['coupon_id'] > 0) : ?>
                                            <ul class="am-avg-sm-2">
                                                <li class="am-text-right">优惠券抵扣：</li>
                                                <li class="am-text-right">- ￥<?= $detail['coupon_price'] ?></li>
                                            </ul>
                                        <?php endif; ?>
                                        <ul class="am-avg-sm-2">
                                            <li class="am-text-right">运费金额：</li>
                                            <li class="am-text-right">+￥<?= $detail['express_price'] ?></li>
                                        </ul>
                                        <?php if ($detail['update_price']['value'] != '0.00') : ?>
                                            <ul class="am-avg-sm-2">
                                                <li class="am-text-right">后台改价：</li>
                                                <li class="am-text-right"><?= $detail['update_price']['symbol'] ?>
                                                    ￥<?= $detail['update_price']['value'] ?></li>
                                            </ul>
                                        <?php endif; ?>
                                        <ul class="am-avg-sm-2">
                                            <li class="am-text-right">实付款金额：</li>
                                            <li class="x-color-red am-text-right">
                                                ￥<?= $detail['pay_price'] ?></li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                       <span class="am-badge
                                                 am-badge-<?= $detail['delivery_type']['value'] == DeliveryTypeEnum::EXPRESS ? 'secondary' : 'success' ?>">
                                                    <?= $detail['delivery_type']['text'] ?>
                                                </span>
                                </td>
                                <td>
                                    <p>付款状态：
                                        <span class="am-badge
                                        <?= $detail['pay_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                <?= $detail['pay_status']['text'] ?></span>
                                    </p>

                                    <?php if($detail['delivery_type']['value'] == 10): ?>
                                    <p>发货状态：
                                        <span class="am-badge
                                        <?= $detail['delivery_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                <?= $detail['delivery_status']['text'] ?></span>
                                    </p>
                                    <?php endif; ?>

                                    <?php if($detail['delivery_type']['value'] == 10): ?>
                                    <p>收货状态：
                                        <span class="am-badge
                                        <?= $detail['receipt_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                <?= $detail['receipt_status']['text'] ?></span>
                                    </p>
                                    <?php endif; ?>

                                    <?php if($detail['delivery_type']['value'] == 20): ?>
                                        <p>订单状态：
                                            <span class="am-badge
                                        <?= $detail['receipt_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                            <?= $detail['receipt_status']['value'] == 20 ? '已完成' : '未完成' ?>
                                            </span>
                                        </p>
                                    <?php endif; ?>

                                    <?php if ($detail['order_status']['value'] == 20 || $detail['order_status']['value'] == 21): ?>
                                        <!-- <p>订单状态：
                                            <span class="am-badge am-badge-warning"><?= $detail['order_status']['text'] ?></span>
                                        </p> -->
                                    <?php endif; ?>
                                </td>
                                <?php if ($detail['pay_status']['value'] == 10 && $detail['order_status']['value'] == 10) : ?>
                                    <td>
                                        <?php if (checkPrivilege('order/updateprice')): ?>
                                            <p class="am-text-center">
                                                <a class="j-update-price" href="javascript:void(0);"
                                                   data-order_id="<?= $detail['order_id'] ?>"
                                                   data-order_price="<?= $detail['total_price'] - $detail['coupon_price'] + $detail['update_price']['value'] ?>"
                                                   data-express_price="<?= $detail['express_price'] ?>">修改价格</a>
                                            </p>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- 商品信息 -->
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">商品信息</div>
                    </div>
                    <div class="am-scrollable-horizontal">
                        <table class="regional-table am-table am-table-bordered am-table-centered
                            am-text-nowrap am-margin-bottom-xs">
                            <tbody>
                            <tr>
                                <th>商品名称</th>
                                <th>商品编码</th>
                                <th>重量(Kg)</th>
                                <th>单价</th>
                                <th>购买数量</th>
                                <th>商品总价</th>
                            </tr>
                            <?php foreach ($detail['goods'] as $goods): ?>
                                <tr>
                                    <td class="goods-detail am-text-middle">
                                        <div class="goods-image">
                                            <img src="<?= $goods['image']['file_path'] ?>" alt="">
                                        </div>
                                        <div class="goods-info">
                                            <p class="goods-title"><?= $goods['goods_name'] ?></p>
                                            <p class="goods-spec am-link-muted">
                                                <?= $goods['goods_attr'] ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td><?= $goods['goods_no'] ?: '--' ?></td>
                                    <td><?= $goods['goods_weight'] ?: '--' ?></td>
                                    <td>￥<?= $goods['goods_price'] ?></td>
                                    <td>×<?= $goods['total_num'] ?></td>
                                    <td>￥<?= $goods['total_price'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="6" class="am-text-right am-cf">
                                    <span class="am-fl">买家留言：<?= $detail['buyer_remark'] ?: '无' ?></span>
                                    <span class="am-fr">总计金额：￥<?= $detail['total_price'] ?></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- 收货信息 -->
                    <?php if ($detail['delivery_type']['value'] == DeliveryTypeEnum::EXPRESS): ?>
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">收货信息</div>
                        </div>
                        <div class="am-scrollable-horizontal">
                            <table class="regional-table am-table am-table-bordered am-table-centered
                            am-text-nowrap am-margin-bottom-xs">
                                <tbody>
                                <tr>
                                    <th>收货人</th>
                                    <th>收货电话</th>
                                    <th>收货地址</th>
                                    <th>操作</th>
                                </tr>
                                <tr>
                                    <td><?= $detail['address']['name'] ?></td>
                                    <td><?= $detail['address']['phone'] ?></td>
                                    <td>
                                        <?= $detail['address']['region']['province'] ?>
                                        <?= $detail['address']['region']['city'] ?>
                                        <?= $detail['address']['region']['region'] ?>
                                        <?= $detail['address']['detail'] ?>
                                    </td>
                                    <td>
                                        <el-button type="text" @click="displayAddressEdit = true">修改收货人信息</el-button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <!-- 自提门店信息 -->
                    <?php if ($detail['delivery_type']['value'] == DeliveryTypeEnum::EXTRACT): ?>
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">自提买家信息</div>
                        </div>
                        <div class="am-scrollable-horizontal">
                            <table class="regional-table am-table am-table-bordered am-table-centered
                            am-text-nowrap am-margin-bottom-xs" style="width: 30%">
                                <tbody>
                                <tr>
                                    <th width="50">买家图像</th>
                                    <th width="110">买家姓名</th>
                                    <th>预留电话</th>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="<?= $detail['user']['avatarUrl'] ?>" title="点击查看大图"
                                           target="_blank">
                                            <img src="<?= $detail['user']['avatarUrl'] ?>" height="72"
                                                 alt="">
                                        </a>
                                    </td>
                                    <td><?= $detail['extract']['linkman'] ?></td>
                                    <td><?= $detail['extract']['phone'] ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <!-- 自提门店信息 -->
                    <?php if ($detail['delivery_type']['value'] == DeliveryTypeEnum::EXTRACT): ?>
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">自提门店信息</div>
                        </div>
                        <div class="am-scrollable-horizontal">
                            <table class="regional-table am-table am-table-bordered am-table-centered
                            am-text-nowrap am-margin-bottom-xs">
                                <tbody>
                                <tr>
                                    <th>门店ID</th>
                                    <th>门店logo</th>
                                    <th>门店名称</th>
                                    <th>联系人</th>
                                    <th>联系电话</th>
                                    <th>门店地址</th>
                                </tr>
                                <tr>
                                    <td><?= $detail['extract_shop']['shop_id'] ?></td>
                                    <td>
                                        <a href="<?= $detail['extract_shop']['logo']['file_path'] ?>" title="点击查看大图"
                                           target="_blank">
                                            <img src="<?= $detail['extract_shop']['logo']['file_path'] ?>" height="72"
                                                 alt="">
                                        </a>
                                    </td>
                                    <td><?= $detail['extract_shop']['shop_name'] ?></td>
                                    <td><?= $detail['extract_shop']['linkman'] ?></td>
                                    <td><?= $detail['extract_shop']['phone'] ?></td>
                                    <td>
                                        <?= $detail['extract_shop']['region']['province'] ?>
                                        <?= $detail['extract_shop']['region']['city'] ?>
                                        <?= $detail['extract_shop']['region']['region'] ?>
                                        <?= $detail['extract_shop']['address'] ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <!-- 付款信息 -->
                    <?php if ($detail['pay_status']['value'] == 20): ?>
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">付款信息</div>
                        </div>
                        <div class="am-scrollable-horizontal">
                            <table class="regional-table am-table am-table-bordered am-table-centered
                                am-text-nowrap am-margin-bottom-xs">
                                <tbody>
                                <tr>
                                    <th>应付款金额</th>
                                    <th>支付方式</th>
                                    <th>支付流水号</th>
                                    <th>付款状态</th>
                                    <th>付款时间</th>
                                </tr>
                                <tr>
                                    <td>￥<?= $detail['pay_price'] ?></td>
                                    <td><?= $detail['pay_type']['text'] ?></td>
                                    <td><?= $detail['transaction_id'] ?: '--' ?></td>
                                    <td>
                                        <span class="am-badge
                                        <?= $detail['pay_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                <?= $detail['pay_status']['text'] ?></span>
                                    </td>
                                    <td>
                                        <?= $detail['pay_time'] ? date('Y-m-d H:i:s', $detail['pay_time']) : '--' ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <!--  用户取消订单 -->
                    <?php if (checkPrivilege('order.operate/confirmcancel')): ?>
                        <?php if ($detail['pay_status']['value'] == 20 && $detail['order_status']['value'] == 21): ?>
                            <!-- <div class="widget-head am-cf">
                                <div class="widget-title am-fl"><strong>用户取消订单</strong></div>
                            </div>
                            <div class="tips am-margin-bottom-sm am-u-sm-12">
                                <div class="pre">
                                    <p>当前买家已付款并申请取消订单，请审核是否同意，如同意则自动退回付款金额（微信支付原路退款）并关闭订单。</p>
                                </div>
                            </div> -->
                            <!-- 去审核 -->
                            <!-- <form id="cancel" class="my-form am-form tpl-form-line-form" method="post"
                                  action="<?= url('order.operate/confirmcancel', ['order_id' => $detail['order_id']]) ?>">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">审核状态 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <div class="am-u-sm-9">
                                            <label class="am-radio-inline">
                                                <input type="radio" name="order[is_cancel]"
                                                       value="1"
                                                       data-am-ucheck
                                                       required>
                                                同意
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" name="order[is_cancel]"
                                                       value="0"
                                                       data-am-ucheck
                                                       checked>
                                                拒绝
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                        <button type="submit" class="j-submit am-btn am-btn-sm am-btn-secondary">
                                            确认审核
                                        </button>

                                    </div>
                                </div>
                            </form> -->
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- 发货信息 -->
                    <?php if ($detail['pay_status']['value'] == 20 && $detail['order_status']['value'] != 20 && $detail['order_status']['value'] != 21 && $detail['delivery_type']['value'] == 10): ?>
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">发货信息</div>
                        </div>
                        <?php if ($detail['delivery_status']['value'] == 10): ?>
                            <?php if (checkPrivilege('order/delivery')): ?>
                                <?php $goodsNum = count($detail['goods']); ?>
                                <!-- 去发货 -->
                                <form id="delivery" class="my-form am-form tpl-form-line-form" method="post"
                                      action="<?= url('order/delivery', ['order_id' => $detail['order_id']]) ?>">
                                    <table class="spec-sku-tabel am-table am-table-bordered am-table-centered am-margin-bottom-xs am-text-nowrap" id="box-delivery">
                                        <tbody>
                                            <tr>
                                                <td>订单商品</td>
                                                <td>物流公司</td>
                                                <td>物流单号</td>
                                                <td>操作</td>
                                            </tr>
                                            <?php for($i = 0; $i < $goodsNum; $i++): ?>
                                                <?php if($detail['goods'][$i]['delivery_status'] == '20'): ?>
                                                    <?php $expressInfo = \app\store\model\Express::detail($detail['goods'][$i]['express_id']); ?>
                                                    <tr>
                                                        <td>
                                                            <span class="am-badge am-badge-success">已发货</span>
                                                            <?= $detail['goods'][$i]['goods_name'] ?>
                                                        </td>
                                                        <td>
                                                            <?= $expressInfo['express_name'] ?>
                                                        </td>
                                                        <td>
                                                            <?= $detail['goods'][$i]['express_no'] ?>
                                                        </td>
                                                        <td>

                                                        </td>
                                                    </tr>
                                                <?php else:?>
                                                <?php $required = $i > 0 ? '' : 'required'; ?>
                                                <tr>
                                                    <td>
                                                        <select class="goods-id"
                                                                data-am-selected="{btnSize: 'sm', maxHeight: 300}" <?= $required ?> multiple>
                                                            <option value=""></option>
                                                            <?php foreach ($detail['goods'] as $goods): ?>
                                                                <?php if($goods['delivery_status'] == '10'): ?>
                                                                    <option value="<?= $goods['goods_id'] ?>"> <?= $goods['goods_name'] ?></option>
                                                                <?php endif; ?>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="express-id"
                                                                data-am-selected="{btnSize: 'sm', maxHeight: 240}" <?= $required ?>>
                                                            <option value=""></option>
                                                            <?php if (isset($expressList)): foreach ($expressList as $expres): ?>
                                                                <option value="<?= $expres['express_id'] ?>">
                                                                    <?= $expres['express_name'] ?></option>
                                                            <?php endforeach; endif; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="tpl-form-input express-no" style="border: 1px solid #e3e2e5" <?= $required ?>>
                                                    </td>
                                                    <td>
                                                        <?php if($i > 0): ?>
                                                            <i class="iconfont icon-minus btn-minus" onclick="delRow(this)"></i>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            <?php endfor;?>
                                        </tbody>
                                    </table>


                                    <!-- <div class="am-form-group">
                                        <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">物流公司 </label>
                                        <div class="am-u-sm-9 am-u-end am-padding-top-xs">
                                            <select name="order[express_id][]"
                                                    data-am-selected="{btnSize: 'sm', maxHeight: 240}" required multiple>
                                                <option value=""></option>
                                                <?php if (isset($expressList)): foreach ($expressList as $expres): ?>
                                                    <option value="<?= $expres['express_id'] ?>">
                                                        <?= $expres['express_name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                            <div class="help-block am-margin-top-xs">
                                                <small>可在 <a href="<?= url('setting.express/index') ?>" target="_blank">物流公司列表</a>
                                                    中设置
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">物流单号 </label>
                                        <div class="am-u-sm-9 am-u-end">
                                            <input type="text" class="tpl-form-input" name="order[express_no]" required>
                                        </div>
                                    </div> -->


                                    <div class="am-form-group">
                                        <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                            <button type="submit" class="j-submit am-btn am-btn-sm am-btn-secondary">
                                                确认发货
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="am-scrollable-horizontal">
                                <table class="regional-table am-table am-table-bordered am-table-centered
                                    am-text-nowrap am-margin-bottom-xs">
                                    <tbody>
                                    <tr>
                                        <th>商品名称</th>
                                        <th>物流公司</th>
                                        <th>物流单号</th>
                                        <th>发货状态</th>
                                        <th>发货时间</th>
                                        <th>操作</th>
                                    </tr>
                                    <?php
                                        foreach($detail['goods'] as $goods):
                                            if($goods['delivery_status'] == '20'):
                                                $expressId   = $goods['express_id'];
                                                $expressInfo = \app\store\model\Express::detail($expressId);
                                    ?>
                                        <tr>
                                            <td style="text-align:left;"><?= $goods['goods_name'] ?></td>
                                            <td><?= $expressInfo['express_name'] ?></td>
                                            <td><?= $goods['express_no'] ?></td>
                                            <td>
                                                 <span class="am-badge am-badge-success"><?= $detail['delivery_status']['text'] ?></span>
                                            </td>
                                            <td>
                                                <?= date('Y-m-d H:i:s', $goods['delivery_time']) ?>
                                            </td>
                                            <td>
                                                <?= '<el-button type="text" onclick="scanExpressInfo('."'". $expressInfo['express_code']."'". ','."'". $goods['express_no']."'". ')">查看物流</el-button>' ?>
                                            </td>
                                        </tr>
                                    <?php endif;?>
                                    <?php endforeach;?>
                                    <?php if($detail['receipt_status']['value'] == 10): ?>
                                        <tr>
                                            <td colspan="6">
                                                <el-button type="text" @click="dialogDisplay(true)">修改快递信息</el-button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <!--修改弹出框-->
    <div>
        <el-dialog title="设置新的收货人信息" :visible.sync="displayAddressEdit" width="30%" center>
            <el-form ref="form" label-width="80px">
                <el-form-item label="收货人">
                    <el-input v-model="name"></el-input>
                </el-form-item>

                <el-form-item label="收货电话">
                    <el-input v-model="phone" maxlength="14" show-word-limit></el-input>
                </el-form-item>

                <el-form-item label="省份">
                    <el-select v-model="province_id" placeholder="请选择省份" @change="changeProvince">
                        <el-option v-for="item in provinces" :key="item.id" :label="item.name" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="城市">
                    <el-select v-model="city_id" placeholder="请选择城市" @change="changeCity">
                        <el-option v-for="item in currentCitys" :key="item.id" :label="item.name" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="区/县">
                    <el-select v-model="region_id" placeholder="请选择地区" @change="">
                        <el-option v-for="item in currentRegions" :key="item.id" :label="item.name" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="详细地址">
                    <el-input v-model="detail"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="displayAddressEdit = false">取 消</el-button>
                <el-button type="primary" @click="resetOrderAddress">确 定</el-button>
              </span>
        </el-dialog>
    </div>

    <!-- 修改快递信息 -->
    <div>
        <el-dialog title="设置新的物流单号" :visible.sync="displayExpressEdit" width="80%" center>
            <el-table :data="orderGoods">
                <el-table-column label="商品编号" align="center" width="80">
                    <template slot-scope="scope">
                        <span>{{ scope.row.goods_id }}</span>
                    </template>
                </el-table-column>

                <el-table-column label="商品名称" align="center">
                    <template slot-scope="scope">
                        <span>{{ scope.row.goods_name }}</span>
                    </template>
                </el-table-column>

                <el-table-column label="物流公司">
                    <template slot-scope="scope">
                        <el-select v-model="scope.row.express_id" placeholder="请选择物流公司">
                            <el-option v-for="item in allExpress" :key="item.express_id" :label="item.express_name" :value="item.express_id"></el-option>
                        </el-select>
                    </template>
                </el-table-column>

                <el-table-column label="单号">
                    <template slot-scope="scope">
                        <el-input v-model="scope.row.express_no" placeholder="请填入单号"></el-input>
                    </template>
                </el-table-column>
            </el-table>

            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogDisplay(false)">取 消</el-button>
                <el-button type="primary" @click="resetExpressNumbers">确 定</el-button>
              </span>
        </el-dialog>
    </div>
</div>

<script id="tpl-update-price" type="text/template">
    <div class="am-padding-top-sm">
        <form class="form-update-price am-form tpl-form-line-form" method="post"
              action="<?= url('order/updatePrice', ['order_id' => $detail['order_id']]) ?>">
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label"> 订单金额 </label>
                <div class="am-u-sm-9">
                    <input type="number" min="0.00" class="tpl-form-input" name="order[update_price]"
                           value="{{ order_price }}">
                    <small>最终付款价 = 订单金额 + 运费金额</small>
                </div>
            </div>
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label"> 运费金额 </label>
                <div class="am-u-sm-9">
                    <input type="number" min="0.00" class="tpl-form-input" name="order[update_express_price]"
                           value="{{ express_price }}">
                </div>
            </div>
        </form>
    </div>
</script>

<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://cdn.bootcss.com/axios/0.19.0-beta.1/axios.min.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script>
    $(function () {
        /**
         * 修改价格
         */
        $('.j-update-price').click(function () {
            var $this = $(this);
            var data = $this.data();
            // var orderId = $(this).data('order_id');
            layer.open({
                type: 1
                , title: '订单价格修改'
                , area: '340px'
                , offset: 'auto'
                , anim: 1
                , closeBtn: 1
                , shade: 0.3
                , btn: ['确定', '取消']
                , content: template('tpl-update-price', data)
                , success: function (layero) {

                }
                , yes: function (index) {
                    // console.log('asdasd');
                    // 表单提交
                    $('.form-update-price').ajaxSubmit({
                        type: "post",
                        dataType: "json",
                        success: function (result) {
                            result.code === 1 ? $.show_success(result.msg, result.url)
                                : $.show_error(result.msg);
                        }
                    });
                    layer.close(index);
                }
            });
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('.my-form').superForm({
            buildData: function() {
                var goodsIds   = new Array();
                var expressIds = new Array();
                var expressNos = new Array();
                $("#box-delivery tbody tr").each(function(idx) {
                    if(idx > 0) {
                        var goodsId   = $(this).children("td").find("select.goods-id").val();
                        var expressId = $(this).children("td").find("select.express-id").val();
                        var expressNo = $(this).children("td").find("input.express-no").val();
                        if(goodsId) {
                            goodsIds.push(goodsId);
                            expressIds.push(expressId);
                            expressNos.push(expressNo);
                        }
                    }
                });
                return {
                    order: {
                        goods_id: goodsIds,
                        experss_id: expressIds,
                        express_no: expressNos
                    }
                };
            }
        });
    });

    function delRow(obj) {
        $(obj).parent().parent().remove();
    }

    function scanExpressInfo(expressCode, expressNumber){
        url = "http://m.kuaidi100.com/result.jsp?nu="+expressNumber+ "&com=" + expressCode;
        window.open(url, "_blank", "scrollbars=yes,resizable=1,modal=false,alwaysRaised=yes");
    }

    // Vue异步信息修改
    var Main = {
        data() {
            return {
                name: '',         //收货人姓名
                phone: '',        //收货人电话
                province_id: null,  //收货人省份
                city_id: null,      //收货人城市
                region_id: null,    //收货人地区
                detail: '',       //收货人详细地址

                provinces:[],       //下拉菜单所有省份
                currentCitys: [],    //当前省份城市
                citys: [],          //下拉菜单所有城市
                currentRegions: [],  //当前城市所有地区
                regions: [],        //下拉菜单所有地区

                orderGoods: [],     //当前订单商品信息
                allExpress: [],     //所有物流公司信息

                displayAddressEdit: false, //修改收货人弹出框开关
                displayExpressEdit: false,  //修改快递信息弹出框开关
            }
        },
        methods: {
            /* 下拉菜单省份改变 */
            changeProvince(val){
                this.currentCitys = [];
                this.city_id = null;
                this.currentRegions = [];
                this.region_id = null;
                var key = 0;
                for(var item in this.citys){
                    if (this.citys[item].pid == val) {
                        this.currentCitys[key] = this.citys[item];
                        key++;
                    }
                }
            },
            /* 地址下拉菜单城市改变 */
            changeCity(val){
                this.currentRegions = [];
                this.region_id = null;
                var key = 0;
                for(var item in this.regions) {
                    if (this.regions[item].pid == val) {
                        this.currentRegions[key] = this.regions[item];
                        key++;
                    }
                }
            },
            /* 打开修改快递信息弹出窗 */
            dialogDisplay(value){
                this.displayExpressEdit = value;
            },
            /* 获取所有地区并分类 */
            regionTreeGet(){
                var url = 'index.php?s=/store/region/get_cache_all';
                axios.get(url).then((res) => {
                    if ( res.data.code == 0){
                        var regionData = res.data.data;
                        var key1 = 0;
                        var key2 = 0;
                        var key3 = 0;
                        for(var item in regionData){
                            switch (regionData[item].level) {
                                case 1: this.provinces[key1] = regionData[item]; key1++; break;
                                case 2: this.citys[key2] = regionData[item]; key2++; break;
                                case 3: this.regions[key3] = regionData[item]; key3++; break;
                            }
                        }
                    }
                }).catch((error) => {
                    this.$message({
                        type: 'error',
                        message: error.msg
                    });
                });
            },
            /* 获取所有物流公司信息 */
            getAllExpress(){
                var url = 'index.php?s=/store/express/get_all';
                axios.get(url).then((res) => {
                    if ( res.data.code == 0){
                        this.allExpress = res.data.data;
                    }
                }).catch((error) => {
                    this.$message({
                        type: 'error',
                        message: error.msg
                    });
                });
            },
            /* 获取当前订单商品信息 */
            getOrderGoodsInfo(){
                var url = 'index.php?s=/store/order/get_order_goods_info&order_id=' + <?= $detail['order_id'] ?>;
                axios.get(url).then((res) => {
                    if ( res.data.code == 0){
                        this.orderGoods = res.data.data;
                        // 转换订单物流编号的数据数据类型为数字型
                        for (var i = 0; i < this.orderGoods.length; i++) {
                            this.orderGoods[i].express_id = parseInt(this.orderGoods[i].express_id);
                        }
                    }
                }).catch((error) => {
                    this.$message({
                        type: 'error',
                        message: error.msg
                    });
                });
            },
            /* 更新订单中收货人地址 */
            resetOrderAddress(){
                var isTrue = function(val){
                    if (val == '' || val ==null){
                        return false;
                    }
                    return true;
                };
                if (isTrue(this.name) && isTrue(this.phone) && isTrue(this.province_id) && isTrue(this.city_id) && isTrue(this.region_id) && isTrue(this.detail)){
                    row = {
                        name: this.name,
                        phone: this.phone,
                        province_id: this.province_id,
                        city_id: this.city_id,
                        region_id: this.region_id,
                        detail: this.detail,
                        order_id: <?= $detail['order_id'] ?>,
                    };
                } else{
                    this.$message({
                        type: 'error',
                        message: "数据不完整"
                    });
                    return;
                }
                var url = 'index.php?s=/store/order/reset_order_address';
                axios.put(url, row).then((res) =>{
                    if ( res.data.code == 0){
                        this.displayAddressEdit = false;
                        window.location.reload();
                    } else {
                        this.$message({
                            type: 'error',
                            message: res.data.msg
                        });
                    }
                }).catch((error) =>{
                    this.$message({
                        type: 'error',
                        message: error.msg
                    });
                });
            },
            /* 修改物流单号 */
            resetExpressNumbers(){
                var row = {
                    orderGoods: this.orderGoods,
                    order_id: <?= $detail['order_id'] ?>,
                };
                var url = 'index.php?s=/store/order/reset_order_express';
                axios.put(url, row).then((res) =>{
                    if ( res.data.code == 0){
                        this.displayExpressEdit = false;
                        window.location.reload();
                    }else {
                        this.$message({
                            type: 'error',
                            message: res.data.msg
                        });
                    }
                }).catch((error) =>{
                    this.$message({
                        type: 'error',
                        message: error.msg
                    });
                });
            },
        },
        mounted: function () {
            this.regionTreeGet();
            this.getAllExpress();
            this.getOrderGoodsInfo();
        },
    };
    var Ctor = Vue.extend(Main);
    new Ctor().$mount('#app');
</script>
