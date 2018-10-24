<?php
defined('FREAK_ACCESS') or exit('Access Denied');
class lib_captcha
{
    /**
     * 随机因子
     *
     * @var string
     */
    private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';

    //验证码
    private $code;

    //验证码长度
    private $codeLen;

    //宽度
    private $width;

    //高度
    private $height;

    //图形资源句柄
    private $img;

    //指定的字体
    private $font;

    //指定字体大小
    private $fontSize;

    //指定字体颜色
    private $fontColor;
    //gif 数据组
    private $gifData = null;

    /**
     * 构造方法初始化
     *
     * @param int $width
     * @param int $height
     * @param int $codeLen
     * @param int $fontSize
     */
    public function __construct($width = 130, $height = 50, $codeLen = 4, $fontSize = 20)
    {
        $this->width = $width;
        $this->height = $height;
        $this->codeLen = $codeLen;
        $this->fontSize = $fontSize;
        $this->createCode();

        $this->font = PATH_PUBLIC .DS. 'Elephant.ttf';
    }

    /**
     * 生成随机码
     */
    private function createCode()
    {
        $_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $this->codeLen; $i++) {
            $this->code .= $this->charset[mt_rand(0, $_len)];
        }
    }

    /**
     * 生成背景
     */
    private function createBg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    /**
     * 生成文字
     */
    private function createFont()
    {
        $_x = ceil($this->width / $this->codeLen);
        $_y = floor($this->height * 0.75);
        for ($i = 0; $i < $this->codeLen; $i++) {
            $x = $_x * $i + mt_rand(1, 5);
            $y = mt_rand($_y - 10, $_y + 10);
            $angle = mt_rand(-30, 30);
            $this->fontColor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imagettftext(
                $this->img,
                $this->fontSize,
                $angle,
                $x,
                $y,
                $this->fontColor,
                $this->font,
                $this->code[$i]
            );
        }
    }

    /**
     * 生成线条、雪花
     */
    private function createLine()
    {
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline(
                $this->img,
                mt_rand(0, $this->width),
                mt_rand(0, $this->height),
                mt_rand(0, $this->width),
                mt_rand(0, $this->height),
                $color
            );
        }
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring(
                $this->img,
                mt_rand(1, 5),
                mt_rand(0, $this->width),
                mt_rand(0, $this->height),
                '*',
                $color
            );
        }
    }

    /**
     * 输出图片
     */
    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    private function outPutGif(){
        include PATH_ROOT.DS.'lib'.DS.'gif.php';
        $gif=new GIFEncoder($this->gifData,
            array_fill(0, $this->codeLen, 88),
            0,
            1,
            0, 0, 1,
            "bin"
        );
        Header('Content-type:image/gif');
        echo $gif->GetAnimation();
    }
    /**
     * 获取验证码图片
     */
    public function getImage()
    {
        $this->createBg();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }
    /**
     * 获取 GIF 验证码图片
     * */
    public function getGifImage(){
        /*for($i=1;$i<=16;$i++){
            ob_start();
            $image=imagecreate($this->width,$this->height);
            imagecolorallocate($image,0,0,0);

            $gray=imagecolorallocate($image,245,245,245);
            imagefill($image,0,0,$gray);
            $space=30;// 字符间距
            $top=$this->height/4;//y 轴初始位置
            //遍历文字长度
            for($k=0;$k<$this->codeLen;$k++){
                $float_top=rand(0,10);
                //添加文字
                imagestring($image,5,$space*$k,$top+$float_top,substr($this->code,$k,1),imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)));
                //添加干扰线
                imageline($image, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)));
                //添加干扰点
                imagesetpixel($image,rand(),rand(),imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)));
            }

            imagegif($image);
            imagedestroy($image);
            $this->gifData[]=ob_get_contents();
            ob_clean();
        }
        include PATH_ROOT.DS.'lib'.DS.'gif.php';
        $gif=new GIFEncoder($imagedata,
            $delay,
            0,
            1,
            0, 0, 1,
            "bin"
        );
        Header('Content-type:image/gif');
        echo $gif->GetAnimation();
        */
        //创建16帧
        for($i=0;$i<=16;$i++){
            ob_start();
            $this->createBg();
            $this->createLine();
            $this->createFont();
            imagegif($this->img);
            imagedestroy($this->img);
            $this->gifData[] = ob_get_contents();
            ob_clean();
        }
        $this->outPutGif();
        return;
    }

    /**
     * 获取验证码
     *
     * @return string
     */
    public function getCode()
    {
        return strtolower($this->code);
    }
}