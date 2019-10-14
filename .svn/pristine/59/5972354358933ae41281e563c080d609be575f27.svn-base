<?php

namespace app\common\model;

use app\common\model\BaseModel;

/**
 * 分销商提现明细模型
 * Class Apply
 * @package app\common\model\dealer
 */
class Withdraw extends BaseModel
{
    protected $name = 'withdraw';

    /**
     * 打款方式
     * @var array
     */
    public $payType = [
        10 => '余额',
        20 => '支付宝',
        30 => '银行卡',
    ];

    /**
     * 申请状态
     * @var array
     */
    public $applyStatus = [
        10 => '待审核',
        20 => '审核通过',
        30 => '已驳回',
        40 => '已打款',
    ];

    /**
     * 关联分销商用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 提现详情
     * @param $id
     * @return Apply|static
     * @throws \think\exception\DbException
     */
    public static function detail($id)
    {
        return self::get($id);
    }

    /**
     * 生成订单号
     * @return string
     */
    protected function orderNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

}
