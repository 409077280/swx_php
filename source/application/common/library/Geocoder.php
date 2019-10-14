<?php

namespace app\common\library;

/**
 * 小票打印机驱动
 * Class driver
 * @package app\common\library\printer
 */
class Geocoder {
    public static function location($lat, $lng) {
        // 坐标地址转换
        $translateUrl = "https://apis.map.qq.com/ws/coord/v1/translate?locations={$lat},{$lng}&type=3&key=3YTBZ-V25RX-EHM4R-ZJWEM-UIZD2-QYFEU";
        $result = file_get_contents($translateUrl);
        $conversion = json_decode($result, true);
        if ($conversion['status'] == 0) {
            $latitude = $conversion['locations'][0]['lat'];
            $longitude = $conversion['locations'][0]['lng'];

            $url = "https://apis.map.qq.com/ws/geocoder/v1/?location={$latitude},{$longitude}&key=3YTBZ-V25RX-EHM4R-ZJWEM-UIZD2-QYFEU";
            $res = file_get_contents($url);
            $data = json_decode($res, true);
            if (!$data['status']) {
                return $data['result']['address_component'];
            }
        }
    }
}
