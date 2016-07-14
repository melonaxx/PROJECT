<?php
/**
*   @brief 验证码类
*/
class Captcha
{
    const COOKIE_NAME = "ccid";

    private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
    private $code;              //验证码
    private $codelen = 4;       //验证码长度
    private $width = 100;       //宽度
    private $height = 35;       //高度
    private $img;               //图形资源句柄
    private $font;              //指定的字体
    private $fontsize = 20;     //指定字体大小
    private $fontcolor;         //指定字体颜色

    public function __construct() {
        $this->font = dirname(__FILE__).'/font/Elephant.ttf';//注意字体路径要写对，否则显示不了图片
    }

    /**
     * @brief 生成随机码
     *
     * @return
     */
    private function createCode()
    {
        $_len = strlen($this->charset)-1;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->code .= $this->charset[mt_rand(0,$_len)];
        }
    }

    /**
     * @brief 生成背景
     *
     * @return
     */
    private function createBg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
    }

    /**
     * @brief 生成文字
     *
     * @return
     */
    private function createFont()
    {
        $_x = $this->width / $this->codelen;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
        }
    }

    /**
     * @brief 生成线条、雪花
     *
     * @return
     */
    private function createLine()
    {
        //线条
        for ($i=0;$i<6;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
        }
        //雪花
        for ($i=0;$i<100;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
            imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
        }
    }

    /**
     * @brief 输出图片
     *
     * @return
     */
    public function outPut() {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    /**
     * @brief 对外接口，直接调用生成验证码
     *
     * @return
     */
    public function doimg()
    {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
    }

    /**
     * @brief 获取验证码
     *
     * @return
     */
    public function getCode()
    {
        return strtolower($this->code);
    }
}


/**
*   @brief 应用示例
*/
    // $captcha = new Captcha();           //实例化一个对象
    // $captcha->doimg();                  //生成验证码

    // $code = $captcha->getCode();        //获取验证码值
    // $captchsImg = $captcha->outPut();   //输出图片