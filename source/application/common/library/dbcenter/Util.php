<?php
/**
 * 数据中心通用操作
 *
 * @package app\api\controller\dbcenter
 * @author Jerry <tanping@dedeshijie.com>
 * @version 1.0
 * @created 2019-03-01
 */

namespace app\common\library\dbcenter;
use think\Config;

class Util {
    /**
     * 生成paySign
     * @param $nonceStr
     * @param $prepay_id
     * @param $timeStamp
     * @return string
     */
    public function makePaySign($data) {
        // 签名步骤一：按字典序排序参数
        ksort($data);
        $string = $this->toUrlParams($data);
        // 签名步骤二：在string后加入KEY
        $string = $string . '&key=' . Config::get('dbcenter.appSecret');
        // 签名步骤三：MD5加密
        $string = md5($string);
        // 签名步骤四：所有字符转为大写
        $result = strtolower($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     * @param $values
     * @return string
     */
    private function toUrlParams($values) {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }

    /**
     * 模拟请求
     *
     * @param string $url API
     * @param array $params 参数数组
     * @return mixed
     */
    public static function request($url, $params) {
        $header[] = 'Content-Type:application/json;charset=utf-8';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $result = curl_exec($ch);
        // print_r($result);
        // file_put_contents('/www/web/swx_dedeshijie_com/web/temp/callback.html', print_r($result, true));
        curl_close($ch);
        return $result;
    }
}
