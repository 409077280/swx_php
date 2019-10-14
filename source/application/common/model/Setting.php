<?php

namespace app\common\model;

use think\Cache;
use app\common\enum\DeliveryType as DeliveryTypeEnum;

/**
 * 系统设置模型
 * Class Setting
 * @package app\common\model
 */
class Setting extends BaseModel
{
    protected $name = 'setting';
    protected $createTime = false;

    /**
     * 获取器: 转义数组格式
     * @param $value
     * @return mixed
     */
    public function getValuesAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 修改器: 转义成json格式
     * @param $value
     * @return string
     */
    public function setValuesAttr($value)
    {
        return json_encode($value);
    }

    /**
     * 获取指定项设置
     * @param $key
     * @param $wxapp_id
     * @return array
     */
    public static function getItem($key, $wxapp_id = null)
    {
        $data = self::getAll($wxapp_id);
        return isset($data[$key]) ? $data[$key]['values'] : [];
    }

    /**
     * 获取设置项信息
     * @param $key
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($key)
    {
        return self::get(compact('key'));
    }

    /**
     * 全局缓存: 系统设置
     * @param null $wxapp_id
     * @return array|mixed
     */
    public static function getAll($wxapp_id = null)
    {
        $static = new static;
        is_null($wxapp_id) && $wxapp_id = $static::$wxapp_id;
        if (!$data = Cache::get('setting_' . $wxapp_id)) {
            $setting = $static::all(compact('wxapp_id'));
            $data = empty($setting) ? [] : array_column(collection($setting)->toArray(), null, 'key');
            Cache::tag('cache')->set('setting_' . $wxapp_id, $data);
        }
        return $static->getMergeData($data);
    }

    /**
     * 合并用户设置与默认数据
     * @param $userData
     * @return array
     */
    private function getMergeData($userData)
    {
        $defaultData = $this->defaultData();
        // 商城设置：配送方式
        if (isset($userData['store']['values']['delivery_type'])) {
            unset($defaultData['store']['values']['delivery_type']);
        }
        return array_merge_multiple($defaultData, $userData);
    }

    /**
     * 默认配置
     * @param null|string $storeName
     * @return array
     */
    public function defaultData($storeName = null)
    {
        return [
            'store' => [
                'key' => 'store',
                'describe' => '商城设置',
                'values' => [
                    // 商城名称
                    'name' => $storeName ?: '萤火小程序商城',
                    // 配送方式
                    'delivery_type' => array_keys(DeliveryTypeEnum::data()),
                    // 快递100
                    'kuaidi100' => [
                        'customer' => '',
                        'key' => '',
                    ],
                    // 趋势图
                    'show_chart' => '1'
                ],
            ],
            'trade' => [
                'key' => 'trade',
                'describe' => '交易设置',
                'values' => [
                    'order' => [
                        'close_days' => '3',
                        'receive_days' => '10',
                        'refund_days' => '7',
                        'contribution_rate' => '10',
                    ],
                    'freight_rule' => '10',
                ]
            ],
            'storage' => [
                'key' => 'storage',
                'describe' => '上传设置',
                'values' => [
                    'default' => 'local',
                    'engine' => [
                        'local' => [],
                        'qiniu' => [
                            'bucket' => '',
                            'access_key' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                        'aliyun' => [
                            'bucket' => '',
                            'access_key_id' => '',
                            'access_key_secret' => '',
                            'domain' => 'http://'
                        ],
                        'qcloud' => [
                            'bucket' => '',
                            'region' => '',
                            'secret_id' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                    ]
                ],
            ],
            'sms' => [
                'key' => 'sms',
                'describe' => '短信通知',
                'values' => [
                    'default' => 'aliyun',
                    'engine' => [
                        'aliyun' => [
                            'AccessKeyId' => '',
                            'AccessKeySecret' => '',
                            'sign' => '萤火科技',
                            'order_pay' => [
                                'is_enable' => '0',
                                'template_code' => '',
                                'accept_phone' => '',
                            ],
                        ],
                    ],
                ],
            ],
            'tplMsg' => [
                'key' => 'tplMsg',
                'describe' => '模板消息',
                'values' => [
                    'payment' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                    'delivery' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                    'refund' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                ],
            ],
            'printer' => [
                'key' => 'printer',
                'describe' => '小票打印机设置',
                'values' => [
                    'is_open' => '0',   // 是否开启打印
                    'printer_id' => '', // 打印机id
                    'order_status' => [], // 订单类型 10下单打印 20付款打印 30确认收货打印
                ],
            ],
            'full_free' => [
                'key' => 'full_free',
                'describe' => '满额包邮设置',
                'values' => [
                    'is_open' => '0',   // 是否开启满额包邮
                    'money' => '',      // 单笔订单额度
                    'notin_region' => [ // 不参与包邮的地区
                        'province' => [],
                        'citys' => [],
                        'treeData' => [],
                    ],
                    'notin_goods' => [],  // 不参与包邮的商品   (商品id集)
                ],
            ],
            'recharge' => [
                'key' => 'recharge',
                'describe' => '用户充值设置',
                'values' => [
                    'is_recharge' => '1',     // 是否开启充值功能
                    'is_custom' => '1',       // 是否允许自定义金额
                    'is_match_plan' => '1',   // 自定义金额是否自动匹配合适的套餐
                    'describe' => "1. 账户充值仅限微信在线方式支付，充值金额实时到账；\n" .
                        "2. 账户充值套餐赠送的金额即时到账；\n" .
                        "3. 账户余额有效期：自充值日起至用完即止；\n" .
                        "4. 若有其它疑问，可拨打客服电话400-000-1234",     // 充值说明
                ],
            ],
            'withdraw' => [
                'key' => 'withdraw',
                'describe' => '提现设置',
                'values' => [
                    'pay_type' => ['10', '20', '30'],
                    'min_money' => 10,
                ]
            ],
            'newbie' => [
                'key' => 'newbie',
                'describe' => '新人礼包',
                'values' => [
                    'register' => [
                        'is_open' => '0',
                        'self' => [
                            'contribution' => 1,
                            'bonus' => 1
                        ],
                        'referee' => [
                            'contribution' => 1,
                            'bonus' => 1
                        ]
                    ],
                    'first_order' => [
                        'is_open' => '0',
                        'referee' => '10',
                        'howlong' => 30
                    ],
                    'bigbonus' => '8.88|0.05，88.88|0.005'
                ]
            ],
            'checkin' => [
                'key' => 'checkin',
                'describe' => '用户签到奖励',
                'values' => [
                    'is_open' => 1,
                    'contribution' => ['0.88'],
                    'bonus' => ['0.88'],
                ],
            ],
            'goods_share' => [
                'key' => 'goods_share',
                'describe' => '商品分享购买',
                'values' => [
                    'is_open' => '0',
                    'referee' => '10',
                    'expires' => 7
                ]
            ],
            'partner' => [
                'key' => 'partner',
                'describe' => '自由合伙人',
                'values' => [
                    'is_open' => '1',
                    'condition' => [
                        'recharge_id' => 1,
                        'referrals' => 50
                    ],
                    'rights' => [
                        'newbie'  => 3,
                        'partner' => [
                            'recharge' => 10,
                            'bonus' => 10
                        ],
                        'platform' => [
                            'bonus' => 1,
                            'weight' => [
                                'users' => 70,
                                'contribution' => 30
                            ],
                            'condition' => [
                                'recharge_amount' => 1000,
                                'referrals' => 50
                            ]
                        ],
                        'course' => 35
                    ],
                    'describe' => [
                        'rights' => "1、邀请新用户永久分红；\n2、推荐合伙人奖励分红；\n3、全平台溢出共享分红；\n4、推荐付费课程分红；\n5、参加《48H极创营》；\n6、参加《舵主大荟》；\n7、参加线下志愿服务。",
                        'example' => "收入测算：\n" .
                            "样例：自然用户在平台月均消费额300元/月，A合伙人邀请了300个新用户，推荐了10个合伙人，该10个合伙人也完成了与A合伙人一样的业绩，则A与这10个合伙人共邀请了3300个新用户。这3300个新用户通过自然分享裂变可发展出3万溢出新用户。\n" .
                            "1、邀请新人：\n" .
                            "300人*300元/月*3%=2700元/月\n" .
                            "2、溢出共享：\n" .
                            "总溢出价值：3万人*100元/月*1%=30000元\n" .
                            "A分配所得：30000元/11人=2700元/月\n" .
                            "3、邀请合伙人：\n" .
                            "（2700元+2700元）*10%*10人=5400元/月\n" .
                            "月管道收入：2700+2700+5400=10800元/月"
                    ]
                ]
            ]
        ];
    }
}
