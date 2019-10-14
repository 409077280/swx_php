<?php

namespace app\common\library\express;

use think\Cache;

/**
 * 快递100API模块
 * Class Kuaidi100
 * @package app\common\library\express
 */
class Kuaidi100
{
    /* @var array $config 微信支付配置 */
    private $config;

    /* @var string $error 错误信息 */
    private $error;

    /**
     * 构造方法
     * WxPay constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 执行查询
     * @param $express_code
     * @param $express_no
     * @return bool
     */
    public function query($express_code, $express_no)
    {
        // 缓存索引
        $cacheIndex = 'express_' . $express_code . '_' . $express_no;
        if ($data = Cache::get($cacheIndex)) {
            return $data;
        }
        // 参数设置
        $postData = [
            'customer' => $this->config['customer'],
            'param' => json_encode([
                'resultv2' => '1',
                'com' => $express_code,
                'num' => $express_no
            ])
        ];
        $postData['sign'] = strtoupper(md5($postData['param'] . $this->config['key'] . $postData['customer']));
        // 请求快递100 api
        $url = 'http://poll.kuaidi100.com/poll/query.do';
        $result = curlPost($url, http_build_query($postData));
        $express = json_decode($result, true);
        // 记录错误信息
        if (isset($express['returnCode']) || !isset($express['data'])) {
            $this->error = isset($express['message']) ? $express['message'] : '查询失败';
            return false;
        }

        // 请求快递100 api
        // $url     = 'https://m.kuaidi100.com/query?type=' . $express_code . '&postid=' . $express_no;
        // $result  = self::_request($url);
        // $express = json_decode($result, true);
        // // file_put_contents('/www/web/swx_dedeshijie_com/web/temp/callback.html', print_r($express, true));
        // // 记录错误信息
        // if ($express['status'] != '200' || !isset($express['data'])) {
        //     $message = '快递状态未更新，请稍后再查询';
        //     /*
        //     if(isset($express['message']))
        //         $message = $express['message'];
        //
        //     if($message == '快递公司参数异常：单号不存在或者已经过期')
        //         $message = '快递状态未更新，请稍后再查询';
        //     */
        //
        //     $this->error = $message;
        //     return false;
        // }

        // 记录缓存, 时效5分钟
        Cache::set($cacheIndex, $express['data'], 300);
        return $express['data'];
    }

    /**
     * 返回错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    private static function _request($url, $params = []) {
        $agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.66 Safari/537.36';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.kuaidi100.com/');
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
