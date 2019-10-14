<?php

namespace app\common\service\qrcode;

use Grafika\Color;
use Grafika\Grafika;

class Cover extends Base {
    private $goods;
    private $user_id;
    private $coupon;
    private $goodsType = 10;

    /* @var array $pages 小程序码链接 */
    private $pages = [
        10 => 'pages/goods/index',
        20 => 'pages/sharing/goods/index'
    ];

    /**
     * 构造方法
     * Goods constructor.
     * @param $goods
     * @param $user
     * @param int $goodsType
     */
    public function __construct($goods, $user, $coupon)
    {
        parent::__construct();
        // 商品信息
        $this->goods = $goods;
        // 当前用户id
        $this->user_id = $user ? $user['user_id'] : 0;
        // 新人优惠券
        $this->coupon = $coupon;
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
        $wxappId = $this->goods['wxapp_id'];
        // 商品海报背景图
        $backdrop = __DIR__ . '/resource/goods_cover_bg.png';
        // 下载商品首图
        $goodsUrl = $this->saveTempImage($wxappId, $this->goods['image'][0]['file_path'], 'goods');
        // 拼接海报图
        return $this->savePoster($backdrop, $goodsUrl);
    }

    /**
     * 拼接海报图
     * @param $backdrop
     * @param $goodsUrl
     * @param $qrcode
     * @return string
     * @throws \Exception
     */
    private function savePoster($backdrop, $goodsUrl)
    {
        // 实例化图像编辑器
        $editor = Grafika::createEditor(['Gd']);
        // 字体文件路径
        $fontPath = Grafika::fontsDir() . '/' . 'st-heiti-light.ttc';
        // 打开海报背景图
        $editor->open($backdropImage, $backdrop);
        // 打开商品图片
        $editor->open($goodsImage, $goodsUrl);
        // 重设商品图片宽高
        $editor->resizeExact($goodsImage, 250, 250);
        // 商品图片添加到背景图
        $editor->blend($backdropImage, $goodsImage, 'normal', 1.0, 'top-left', 10, 10);
        // 写入商品价格
        $priceType = [10 => 'goods_price', 20 => 'sharing_price'];
        $editor->text($backdropImage, '￥' . $this->goods['sku'][0][$priceType[$this->goodsType]], 22, 270, 100, new Color('#da3e44'),  $fontPath);
        $editor->text($backdropImage, '￥' . $this->goods['sku'][0]['line_price'], 16, 273, 135, new Color('#ababab'), $fontPath);
        $editor->text($backdropImage, '—————', 16, 273, 135, new Color('#aaaaaa'), $fontPath);
        if($this->coupon['reduce_price'])
            $editor->text($backdropImage, '新人券￥' . $this->coupon['reduce_price'], 12, 280, 208, new Color('#fff'), $fontPath);
        else
            $editor->text($backdropImage, '新人券￥0', 12, 280, 208, new Color('#fff'), $fontPath);
        // 保存图片
        $editor->save($backdropImage, $this->getPosterPath());
        return $this->getPosterUrl();
    }

    /**
     * 处理文字超出长度自动换行
     * @param integer $fontsize 字体大小
     * @param integer $angle 角度
     * @param string $fontface 字体名称
     * @param string $string 字符串
     * @param integer $width 预设宽度
     * @param null $max_line 最多行数
     * @return string
     */
    private function wrapText($fontsize, $angle, $fontface, $string, $width, $max_line = null)
    {
        // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
        $content = "";
        // 将字符串拆分成一个个单字 保存到数组 letter 中
        $letter = [];
        for ($i = 0; $i < mb_strlen($string, 'UTF-8'); $i++) {
            $letter[] = mb_substr($string, $i, 1, 'UTF-8');
        }
        $line_count = 0;
        foreach ($letter as $l) {
            $testbox = imagettfbbox($fontsize, $angle, $fontface, $content . ' ' . $l);
            // 判断拼接后的字符串是否超过预设的宽度
            if (($testbox[2] > $width) && ($content !== "")) {
                $line_count++;
                if ($max_line && $line_count >= $max_line) {
                    $content = mb_substr($content, 0, -1, 'UTF-8') . "...";
                    break;
                }
                $content .= "\n";
            }
            $content .= $l;
        }
        return $content;
    }

    /**
     * 海报图文件路径
     * @return string
     */
    private function getPosterPath()
    {
        // 保存路径
        $tempPath = WEB_PATH . 'temp' . '/' . $this->goods['wxapp_id'] . '/';
        !is_dir($tempPath) && mkdir($tempPath, 0755, true);
        return $tempPath . $this->getPosterName();
    }

    /**
     * 海报图文件名称
     * @return string
     */
    private function getPosterName()
    {
        return 'cover_' . md5("{{$this->user_id}_{$this->goodsType}_{$this->goods['goods_id']}") . '.png';
    }

    /**
     * 海报图url
     * @return string
     */
    private function getPosterUrl()
    {
        return \base_url() . 'temp/' . $this->goods['wxapp_id'] . '/' . $this->getPosterName() . '?t=' . time();
    }


}
