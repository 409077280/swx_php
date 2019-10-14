<?php

namespace app\common\service\qrcode;

use Grafika\Color;
use Grafika\Grafika;

class ChartTrendBg extends Base {
    private $user;

    /**
     * 构造方法
     * @param $user
     */
    public function __construct($user) {
        parent::__construct();
        // 当前用户
        $this->user = $user;
    }

    /**
     * @return mixed
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function getImage() {
        // 判断海报图文件存在则直接返回url
        if (file_exists($this->getPosterPath())) {
            return $this->getPosterUrl();
        }
        // 小程序id
        $wxappId = $this->user['wxapp_id'];
        // 分享图表背景图
        $backdrop = __DIR__ . '/resource/chart_trend_bg.png';
        // 小程序码参数
        $scene = "uid:" . ($this->user['user_id'] ?: 0);
        // 下载小程序码
        $qrcode = $this->saveQrcode($wxappId, $scene, 'pages/user/line');
        // 拼接海报图
        return $this->savePoster($backdrop, $qrcode);
    }

    /**
     * 拼接海报图
     * @param $backdrop
     * @param $qrcode
     * @return string
     * @throws \Exception
     */
    private function savePoster($backdrop, $qrcode)
    {
        // 实例化图像编辑器
        $editor = Grafika::createEditor(['Gd']);
        // 字体文件路径
        $fontPath = Grafika::fontsDir() . '/' . 'st-heiti-light.ttc';
        // 打开海报背景图
        $editor->open($backdropImage, $backdrop);
        // 打开小程序码
        $editor->open($qrcodeImage, $qrcode);
        // 重设小程序码宽高
        $editor->resizeExact($qrcodeImage, 180, 180);
        // 小程序码添加到背景图
        $editor->blend($backdropImage, $qrcodeImage, 'normal', 1.0, 'top-left', 85, 970);
        // 保存图片
        $editor->save($backdropImage, $this->getPosterPath());
        return $this->getPosterUrl();
    }

    /**
     * 海报图文件路径
     * @return string
     */
    private function getPosterPath()
    {
        // 保存路径
        $tempPath = WEB_PATH . 'temp' . '/' . $this->user['wxapp_id'] . '/';
        !is_dir($tempPath) && mkdir($tempPath, 0755, true);
        return $tempPath . $this->getPosterName();
    }

    /**
     * 海报图文件名称
     * @return string
     */
    private function getPosterName()
    {
        return 'chart_trend_bg_' . md5("{{$this->user['wxapp_id']}_{$this->user['user_id']}") . '.png';
    }

    /**
     * 海报图url
     * @return string
     */
    private function getPosterUrl()
    {
        return \base_url() . 'temp/' . $this->user['wxapp_id'] . '/' . $this->getPosterName() . '?t=' . time();
    }
}
