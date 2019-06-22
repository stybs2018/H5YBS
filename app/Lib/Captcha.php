<?php
/**
 * 生成验证码
 */
namespace App\Lib;

class Captcha {
    private $str;
    private $code;
    private $width;
    private $height;
    private $len;
    private $img;
    
    function __construct($type = 1, $width, $height, $len = 4)
    {
        $this->width    = $width;
        $this->height   = $height;
        $this->len      = $len;
        
        switch ($type) {
            case 1: 
                $this->buildVerify1();
                break;
            case 2: 
                $this->buildVerify2();
                break;
        }
        $this->createImg();
    }
    
    //  四则算术验证码
    private function buildVerify1() 
    {
        $a = rand(10, 100);
        $b = (int)($a * (mt_rand() / mt_getrandmax() * 1)) + 1;
        $m = rand(1, 4);
        switch ($m) {
            case 1: 
                $this->code = $a + $b; 
                $this->str = $a.'+'.$b;
                break;
            case 2: 
                $this->code = $a - $b; 
                $this->str = $a.'-'.$b;
                break;
            case 3:
                $this->code = $a * $b; 
                $this->str = $a.'x'.$b;
                break;
            case 4: 
                $this->code = $a / $b; 
                $this->str = $a.'÷'.$b;
                break;
        }
    }
    
    //  字串验证码
    private function buildVerify2()
    {
        $this->str = $this->randstr($this->len);
        $this->code = $this->str;
    }
    
    //  生成图像
    private function createImg()
    {
        // 创建画布
        $this->img = imagecreatetruecolor($this->width, $this->height);
        // 设置背景
        $background = imagecolorallocate($this->img, 255, 255, 255);
        imagefill($this->img, 0, 0, $background);
        
        for($i = 0; $i < strlen($this->str); $i++) {
            $fontSize   = strlen($this->str) + 10;
            $fontColor  = imagecolorallocate($this->img, rand(0, 120), rand(0, 120), rand(0, 120)); 
            $x = ($i * 100 / strlen($this->str)) + rand(5, 10);
    	    $y = rand(5,10);
            imagestring($this->img, $fontSize, $x, $y, $this->str[$i], $fontColor);
        }
        for ($i=0; $i < 400; $i++) { 
    	    $pointcolor = imagecolorallocate($this->img, rand(50, 200),rand(50, 200), rand(50, 200));
    	    imagesetpixel($this->img, rand(1, 120), rand(1, 38), $pointcolor);
        }
        for ($i=0; $i < 7; $i++) { 
    	    $linecolor = imagecolorallocate($this->img, rand(80, 220), rand(80, 220), rand(80, 220));
    	    imageline($this->img, rand(1, 99), rand(1, 29), rand(1, 99), rand(1, 29) ,$linecolor);
        }

        header('content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }
    
    //  获取结果
    public function getRes()
    {
        return [
            'img' => $this->img,
            'code'=> $this->code
        ];
    }
    
    // 生成随机字符串
    private function randstr($len){
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $strlen = mb_strlen($pattern) - 1;
        $str = "";
        for($i = 0;$i < $len;$i++){
            $str .= $pattern[mt_rand(0,$strlen)];
        }
        return $str;
    }
}