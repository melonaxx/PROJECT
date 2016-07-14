<?php

class Action_login extends XAction
{
    public function _run($request, $xcontext)
    {
        $errtype = intval($request->errtype);
        $xcontext->errtype = $errtype;

        return XNext::useTpl("login.html");
    }
}

class Action_dologin extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $username = $_POST['name'];
        $password = $_POST['pwd'];

        // 验证码
        $captcha = $_POST['c'];
        // 验证码的ID
        $capid = $_COOKIE[Captcha::COOKIE_NAME];

        if (!$username || !$password || !$captcha) {
            echo ResultSet::jfail(400, "params of name or pwd or c is empty");
            return XNext::nothing();
        }

        // 首先验证验证码
        if (!$capid || !VerifyCodeSvc::ins()->verifyCode($capid, $captcha)) {
            // 验证码错误
            echo ResultSet::jfail(40301, "fail to verify code");
            return XNext::nothing();
        }

        // 判断用户是否存在，以及是否状态正常
        $user = UserSvc::ins()->getUserByName($username);
        if (!$user || !$user->isValid()) {
            echo ResultSet::jfail(404, "user not found");
            return XNext::nothing();
        }
        if ($user['status']=='S'){
            echo ResultSet::jfail(404, "user not found");
            return XNext::nothing();
        }
        if ($user['status']=='T'){
            echo ResultSet::jfail(4041, "user be stop");
            return XNext::nothing();
        }
        $userid = $user->id;
        // 验证用户密码
        $result = SecuritySvc::ins()->verifyPassword($userid, $password);
        list($retcode, $retmsg) = $result;
        if ($retcode === 0) {
            // 密码验证成功，设置用户的cookie
            list($retcode, $retmsg) = UserSvc::ins()->setCookie($userid, $_SERVER['REMOTE_ADDR']);
            if ($retcode !== 0) {
                echo ResultSet::jfail(500, $retmsg);
                return XNext::nothing();
            }
            echo ResultSet::jsuccess($user->toArray());
        } else {
            echo ResultSet::jfail($retcode, $retmsg);
        }

        return XNext::nothing();
    }
}

class Action_logout extends XAction
{
    public function _run($request, $xcontext)
    {
        setcookie("U",'',time()-1111);
        setcookie("S",'',time()-1111);
        return XNext::useTpl("login.html");
    }
}
