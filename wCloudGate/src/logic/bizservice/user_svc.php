<?php

class UserSvc
{
    const COOKIE_U 		 = "U";
    const COOKIE_U_UID   = "u";
    const COOKIE_U_TOKEN = "t";
    const COOKIE_S 		 = "S";

    private static $_ins = null;
    private $logger = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }

    public function setCookie($userid, $session, $encryptInfo , $time = 0)
    {
        if($time == 1) {
            $time = time() + 60 * 60 * 24 * 7;
        }else {
            $time = 0;
        }
        setcookie(self::COOKIE_S, $session, $time);

        $cookieU = self::COOKIE_U_TOKEN . "=" . urlencode($encryptInfo) . "&" . self::COOKIE_U_UID . "=" . $userid;

        setcookie(self::COOKIE_U, $cookieU, $time);

        return ;
    }

    public function getUserInfoFromCookie()
    {
        $cookieU = $_COOKIE[self::COOKIE_U];
        $cookieS = $_COOKIE[self::COOKIE_S];

        if (!$cookieU || !$cookieS) {
            // cookie不存在，返回小于0的错误码-1
            return array(LoginErrno::NOCOOKIE, "cookie is empty");
        }

        // 首先解析$cookieU
        $uinfo = null;
        parse_str($cookieU, $uinfo);
        $userid = intval($uinfo[self::COOKIE_U_UID]);
        $token = $uinfo[self::COOKIE_U_TOKEN];
        if (!$userid || !$token) {
            // cookie不存在，返回小于0的错误码-1
            return array(LoginErrno::NOCOOKIE, "cookie U is invalid");
        }

        // 解密
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->decryptUserInfo($userid ,  $token);
        if(!$result) {
            return array(500, "Internal server error ");
        }
        $data = $result->data;

        if ($data['id'] != $userid) {
            return array(LoginErrno::BADCOOKIE, "fail to decrypt user info");
        }

        // 如果登录时间超过了24小时，则强制cookie失效
        if (time() - $data['time'] > 24 * 3600) {
            return array(LoginErrno::FORCED, "cookie time out");
        }

        return array(0, $data);
    }


}
