<?php

class SecuritySvc
{
    const DOMAIN = "1sheng.COM";
    const INNER_DOMAIN = "1sheng.NET";
    const SALT = "Cd&0u5D8^9%7@1h6;Nxz";
    const SESSION_TIMEOUT = 1800;   // 用户登录的session 30分钟后过期
    const SESSION_PREFIX = "uss_";

    private static $_ins = null;
    private $logger = null;
    private $redisclient = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }

        $this->redisclient = GClientAltar::getSessionRedisClient();
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }


    /**
    * @brief  HASH密码: md5(sha1(userid : $passwd))
    *
    * @param $userid
    * @param $passwd  并非明文密码，它是明文密码的散列值 即 md5(orgin_passwd@DOMAIN)
    *
    * @return
    */
    public function hashedPassword($userid, $passwd)
    {
        // 一些特殊帐号密码可能为空
        if (empty($passwd)) {
            return "";
        }

        return md5(sha1($userid . ":" . $passwd));
    }

    /**
    * @brief HASH密码；md5(sha1(userid : md5(origin_pwd@DOMAIN)))
    *
    * @param $userid
    * @param $origin_pwd 明文密码
    *
    * @return
    */
    public function hashedOriginPasswd($userid, $origin_pwd)
    {
        if (empty($origin_pwd)) {
            return "";
        }

        $passwd = md5($origin_pwd . "@" . self::DOMAIN);
        return $this->hashedPassword($userid, $passwd);
    }

    /**
    * @brief  验证密码。该过程非常重要，必须保证捕获异常
    *
    * @param $userid
    * @param $passwd
    *
    * @return  如果验证成功，则返回True，否则返回一个包含errno和errmsg的数组
    */
    public function verifyPassword($userid, $passwd)
    {
        try {
            $userSecurity = new Security($userid);
            $userSecurity->get();

            // 用户没有密码信息，这可能是因为用户是第三方登录的缘故
            if ($userSecurity->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
                $this->logger->error("The user has not security info, userid: $userid");
                return array(404, "The user has not security info");
            }

            $passwd_in = $userSecurity->password;
            $passwd_out = $this->hashedPassword($userid, $passwd);

            if ($passwd_in && $passwd_in === $passwd_out) {
                return array(0, "");
            } else {
                $this->logger->error("The password certification has failed, userid: $userid");
                return array(403, "The password certification has failed");
            }
        } catch (Exception $e) {
            $this->logger->error("Server error occurs when valify password, userid: $userid");
            return array(500, "Server Error");
        }
    }

    /**
    * @brief  密码验证通过后，加密用户信息的部分字段，作为登录凭证
    *
    * @param $userid 用户的userid
    * @param $clientip 用户登录的IP
    *
    * @return
    */
    public function encryptUserInfo($userid, $clientip)
    {
        $now = time();
        $data = "$userid\n$now\n$clientip";
        $key = $this->getMcryptKeyByUserId($userid);

        $encryptData = EncryptUtls::encrypt($data, $key);
        return $encryptData;
    }

    /**
    * @brief  解密用户信息的字符串，获得对应用户信息
    *
    * @param $userid
    * @param $encryptedData
    *
    * @return
    */
    public function decryptUserInfo($userid, $encryptedData)
    {
        $key = $this->getMcryptKeyByUserId($userid);
        $originData = EncryptUtls::decrypt($encryptedData, $key);

        if (!$originData) {
            $this->logger->error("failed to decrpt session, token[$token], session[$session]");
            return false;
        }

        $sessionData = explode("\n", $originData);

        $userid = intval($sessionData[0]);
        $logintime = intval($sessionData[1]);
        $clientip = $sessionData[2];

        $this->logger->info("decrypt session result: userid[$userid], logintime[$logintime], clientip[$clientip]");

        return array("userid"=>$userid, "logintime"=>$logintime, "clientip"=>$clientip);
    }

    public function createSession($userid)
    {
        // 生成一个随机的session,并存入redis中
        $sessionid = md5(gethostname() . ":" . $userid . ":" . uniqid("", true) . ":" . mt_rand());
        $key = $this->getUserSessionKey($userid);

        try {
            $result = $this->redisclient->setEx($userid, $key, self::SESSION_TIMEOUT, $sessionid);
            if (!$result) {
                return false;
            }

            return $sessionid;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when createSession[$userid]: " . $ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 获取redis中$userid对应的session
     *
     * @param $userid
     * @param $session
     *
     * @return
     */
    public function getSession($userid)
    {
        try {
            $key = $this->getUserSessionKey($userid);
            $cur_session = $this->redisclient->get($userid, $key);
            if (!$cur_session) {
                return null;
            }
            return $cur_session;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when getSession[$userid]: " . $ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 延长userid对应的session的生命周期
     *
     * @param $userid
     *
     * @return
     */
    public function liveSession($userid)
    {
        try {
            $key = $this->getUserSessionKey($userid);
            return $this->redisclient->expire($userid, $key, self::SESSION_TIMEOUT);
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when liveSession[$userid]: " . $ex->getMessage());
            return false;
        }
    }

    public function createSecurity($userid, $passwd, $strength=0)
    {
        $security = new Security($userid);
        $security->password = $this->hashedOriginPasswd($userid, $passwd);

        // 每次创建密码，都需要更新token
        $security->token = md5($userid . ":" . gethostname() . ":" . microtime());

        $security->strength = $strength;
        $security->insert();

        return $security;
    }
    //修改密码
    public function updateSecurity($userid, $passwd, $strength=0)
    {
        $security = new Security($userid);
        $security->password = $this->hashedOriginPasswd($userid, $passwd);

        // 每次创建密码，都需要更新token
        $security->token = md5($userid . ":" . gethostname() . ":" . microtime());

        $security->strength = $strength;
        $security->update();

        return $security;
    }

    private function getUserSessionKey($userid)
    {
        return self::SESSION_PREFIX . $userid;
    }

    /**
     * @brief 根据$userid获取token
     *
     * @param $userid
     *
     * @return
     */
    private function getTokenByUserId($userid)
    {
        $security = new Security($userid);
        $security->get();

        if ($security->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return "";
        }

        return $security->token;
    }

    /**
    * @brief  获取Mcrypt加解密的key
    *           key=($token . $dynamic_veersion . SALT)
    *           token = 用户每次修改密码，token都会发生改变
    *
    * @param $userid
    *
    * @return
    */
    private function getMcryptKeyByUserId($userid)
    {
        $token = $this->getTokenByUserId($userid);
        $key = $token . "###" . self::SALT;
        return $key;
    }

}

class EncryptUtls
{
    const ALGORITHM = "rijndael-256";
    const MODE = "cfb";
    const IV = "2df66968fdd720d7ce2845bcbae98edf";

    public static function encrypt($data, $key)
    {
        /* Open the cipher */
        $td = mcrypt_module_open(self::ALGORITHM, '', self::MODE, '');

        /* Create key */
        $ks = mcrypt_enc_get_key_size($td);
        $key = substr(md5($key), 0, $ks);

        /* Intialize encryption */
        mcrypt_generic_init($td, $key, self::IV);

        /* Encrypt data */
        $encrypted = mcrypt_generic($td, $data);

        /* Terminate encryption handler */
        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        return base64_encode($encrypted);
    }

    public static function decrypt($encryptedData, $key)
    {
        if (empty($encryptedData)) {
            return "";
        }

        $encryptedData = base64_decode($encryptedData);
        if (!$encryptedData) {
            return false;
        }

        /* Open the cipher */
        $td = mcrypt_module_open(self::ALGORITHM, '', self::MODE, '');

        /* Create key */
        $ks = mcrypt_enc_get_key_size($td);
        $key = substr(md5($key), 0, $ks);

        /* Initialize encryption module for decryption */
        mcrypt_generic_init($td, $key, self::IV);

        /* Decrypt encrypted string */
        $decrypted = mdecrypt_generic($td, $encryptedData);

        /* Terminate decryption handle and close module */
        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        return trim($decrypted);
    }
}
