<?php
namespace app\common\library\wechat;

use think\Cache;
use app\common\library\wechat\WxUser;
use app\common\exception\BaseException;
use app\common\model\Wxapp;
/**
 * 对微信小程序用户加密数据的解密示例代码.
 *
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */

class WxBizDataCrypt extends WxBase {
	/**
	 * 检验数据的真实性，并且获取解密后的明文.
	 * @param $encryptedData string 加密的用户数据
	 * @param $iv string 与用户数据一同返回的初始向量
	 * @param $data string 解密后的原文
     *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public static function decrypt($encryptedData, $iv, $userId) {
        $sessionKey = Cache::get('session_key-' . $userId);

        if(!$sessionKey)
            throw new BaseException(['msg' => 'session_key获取失败']);

        // 获取当前小程序信息
        $wxConfig = Wxapp::getWxappCache();

		$aesKey     = base64_decode($sessionKey);

		if (strlen($iv) != 24)
			throw new BaseException(['msg' => 'iv不合法']);

		$aesIV     = base64_decode($iv);
		$aesCipher = base64_decode($encryptedData);
		$result    = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
		$dataObj   = json_decode($result, true);
		if( $dataObj  == NULL )
			throw new BaseException(['msg' => 'aes解密失败']);

		if( $dataObj['watermark']['appid'] != $wxConfig['app_id'] )
			throw new BaseException(['msg' => 'aes解密失败']);

		return $dataObj;
	}
}
