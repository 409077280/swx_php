<?php

namespace app\common\enum;

/**
 * 商城设置枚举类
 * Class Setting
 * @package app\common\enum
 */
class Setting extends EnumBasics
{
    // 商城设置
    const STORE = 'store';

    // 交易设置
    const TRADE = 'trade';

    // 短信通知
    const SMS = 'sms';

    // 模板消息
    const TPL_MSG = 'tplMsg';

    // 上传设置
    const STORAGE = 'storage';

    // 小票打印
    const PRINTER = 'printer';

    // 满额包邮设置
    const FULL_FREE = 'full_free';

    // 充值设置
    const RECHARGE = 'recharge';

    // 提现设置
    const WITHDRAW = 'withdraw';

    // 新人礼包设置
    const NEWBIE = 'newbie';

    // 用户签到设置
    const CHECK_IN = 'checkin';

    // 商品分享
    const GOODS_SHARE = 'goods_share';

    // 自由合伙人
    const PARTNER = 'partner';

    /**
     * 获取订单类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::STORE => [
                'value' => self::STORE,
                'describe' => '商城设置',
            ],
            self::TRADE => [
                'value' => self::TRADE,
                'describe' => '交易设置',
            ],
            self::SMS => [
                'value' => self::SMS,
                'describe' => '短信通知',
            ],
            self::TPL_MSG => [
                'value' => self::TPL_MSG,
                'describe' => '模板消息',
            ],
            self::STORAGE => [
                'value' => self::STORAGE,
                'describe' => '上传设置',
            ],
            self::PRINTER => [
                'value' => self::PRINTER,
                'describe' => '小票打印',
            ],
            self::FULL_FREE => [
                'value' => self::FULL_FREE,
                'describe' => '满额包邮设置',
            ],
            self::RECHARGE => [
                'value' => self::RECHARGE,
                'describe' => '充值设置',
            ],
            self::WITHDRAW => [
                'value' => self::WITHDRAW,
                'describe' => '提现设置',
            ],
            self::NEWBIE => [
                'value' => self::NEWBIE,
                'describe' => '新人礼包设置',
            ],
            self::CHECK_IN => [
                'value' => self::CHECK_IN,
                'describe' => '用户签到设置',
            ],
            self::GOODS_SHARE => [
                'value' => self::GOODS_SHARE,
                'describe' => '商品分享',
            ],
            self::PARTNER => [
                'value' => self::PARTNER,
                'describe' => '自由合伙人',
            ],
        ];
    }

}
