<?php

class UserSvc
{
    const COOKIE_U = "U";
    const COOKIE_U_UID = "u";
    const COOKIE_U_TOKEN = "t";
    const COOKIE_S = "S";

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

    public function getUserById($userid)
    {
        $user = new User($userid);
        $user->get();

        if ($user->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return null;
        }

        return $user;
    }

    public function getUserByName($name)
    {
        $user = new User();
        $user->get(array("name"=>$name));

        if ($user->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return null;
        }

        return $user;
    }

    public function addUser($name, $password)
    {
        // 使用事务
        $writer = XDao::dwriter("DWriter");

        $writer->beginTrans();

        try {
            // 首先新增user
            $user = $this->createUser($name);

            $security = SecuritySvc::ins()->createSecurity($user->id, $password);

            $writer->commit();

            return $user;
        } catch (Exception $ex) {
            $writer->rollback();
            return false;
        }
    }


    public function setCookie($userid, $clientip)
    {
        // 为用户生成一个唯一的ID，每次登录，都会生成一个新的session
        // 并且会记录到redis中，30分钟之后自动过期
        $session = SecuritySvc::ins()->createSession($userid);
        if (!$session) {
            return array(-1, "fail to create session");
        }
        setcookie(self::COOKIE_S, $session, 0, "/", "", false, true);

        // 加密用户的信息
        $encryptInfo = SecuritySvc::ins()->encryptUserInfo($userid, $clientip);

        if (!$encryptInfo) {
            return array(-1, "fail to encrypt user info");
        }

        $cookieU = self::COOKIE_U_TOKEN . "=" . urlencode($encryptInfo) . "&" . self::COOKIE_U_UID . "=" . $userid;
        setcookie(self::COOKIE_U, $cookieU, 0, "/", "", false, true);

        return array(0, "");
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
        $login_info = SecuritySvc::ins()->decryptUserInfo($userid, $token);
        if ($login_info['userid'] != $userid) {
            return array(LoginErrno::BADCOOKIE, "fail to decrypt user info");
        }

        // 如果登录时间超过了24小时，则强制cookie失效
        if (time() - $login_info['logintime'] > 24 * 3600) {
            return array(LoginErrno::FORCED, "cookie time out");
        }

        // 查看用户session
        $cur_session = SecuritySvc::ins()->getSession($userid);
        if (!$cur_session) {
            return array(LoginErrno::EXPIRED, "session expired");
        }

        if ($cur_session != $cookieS) {
            return array(LoginErrno::KICKED, "session modified");
        }

        // 延长session的生命周期
        SecuritySvc::ins()->liveSession($userid);

        // 根据$userid获取用户信息
        $user = $this->getUserById($userid);
        if (!$user || !$user->isValid()) {
            return array(LoginErrno::NOTFOUND, "user not found");
        }

        return array(0, $user);
    }


    private function createUser($name, $status=User::STATUS_FIRST_LOGIN)
    {
        $user = new User();
        $user->name = $name;
        $user->status = $status;

        $user->insert();

        return $user;
    }
}
