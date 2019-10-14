<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">提现清单</div>
                </div>
                <div class="widget-body am-fr">
                    <!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom-xs am-cf">

                    </div>
                    <div class="am-scrollable-horizontal am-u-sm-12 am-padding-bottom-lg">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <!-- <th>用户ID</th> -->
                                <th>微信头像</th>
                                <th>微信昵称</th>
                                <th>姓名</th>
                                <th>提现金额</th>
                                <th>提现方式</th>
                                <th>提现信息</th>
                                <th class="am-text-center">审核状态</th>
                                <th>申请时间</th>
                                <th>审核时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <!-- <td class="am-text-middle"><?= $item['user_id'] ?></td> -->
                                    <td class="am-text-middle">
                                        <a href="<?= $item['avatarUrl'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['avatarUrl'] ?>"
                                                 width="50" height="50" alt="">
                                        </a>
                                    </td>
                                    <td class="am-text-middle">
                                        <p><span><?= $item['nickName'] ?></span></p>
                                    </td>

                                    <td class="am-text-middle">
                                        <p><span><?= $item['real_name'] ?></span></p>
                                    </td>

                                    <td class="am-text-middle">
                                        <p><span><?= $item['money'] ?></span></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p><span><?= $item['pay_type']['text'] ?></span></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <?php if ($item['pay_type']['value'] == 20) : ?>
                                            <p><span><?= $item['alipay_name'] ?></span></p>
                                            <p><span><?= $item['alipay_account'] ?></span></p>
                                        <?php elseif ($item['pay_type']['value'] == 30) : ?>
                                            <p><span><?= $item['bank_name'] ?></span></p>
                                            <p><span><?= $item['bank_account'] ?></span></p>
                                            <p><span><?= $item['bank_card'] ?></span></p>
                                        <?php else : ?>
                                            <p><span>--</span></p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="am-text-middle am-text-center">
                                        <?php if ($item['apply_status'] == 10) : ?>
                                            <span class="am-badge">待审核</span>
                                        <?php elseif ($item['apply_status'] == 20) : ?>
                                            <span class="am-badge am-badge-secondary">审核通过</span>
                                        <?php elseif ($item['apply_status'] == 30) : ?>
                                            <p><span class="am-badge am-badge-warning">已驳回</span></p>
                                            <span class="f-12">
                                                <a class="j-show-reason" href="javascript:void(0);"
                                                   data-reason="<?= $item['reject_reason'] ?>">
                                                    查看原因</a>
                                            </span>
                                        <?php elseif ($item['apply_status'] == 40) : ?>
                                            <span class="am-badge am-badge-success">已打款</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle"><?= $item['audit_time'] ?: '--' ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="11" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
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
</div>
