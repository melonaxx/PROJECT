<?php

class Action_captcha extends XAction
{
    public function _run($request, $xcontext)
    {
        // 首先生成图片验证码
        $captcha = new Captcha();  //实例化一个对象
        $captcha->doimg();
        $code = $captcha->getCode();

        $sessionid = VerifyCodeSvc::ins()->setVerifyCode($code);

        // $sessionid写入cookie
        setcookie(Captcha::COOKIE_NAME, $sessionid, 0, "/");

        // 输出图片
        $captcha->outPut();

        return XNext::nothing();
    }
}
