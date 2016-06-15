<?php

class SecuritySvc 
{
    const DOMAIN = "waimaiw.COM";
    const INNER_DOMAIN = "waimaiw.NET";
    const SALT = "wm@#$%^&*13;/&*%~@#$";

    private static $_ins = null;
    private $logger      = null;

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

    /**
     * @brief 创建密码信息
     *
     * @param $userid 用户id
     * @param $passwd 密码
     *
     * @return
     */
    public function createSecurity($userid, $passwd)
    {
        $security = new Security($userid);
        $security->passwd = $this->hashedOriginPasswd($userid, $passwd);

        $security->token = md5($userid . ":" . gethostname() . ":" . microtime());

        try {
            $security->insert();
            return $security;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when createSecurity, userid: $userid" . $ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 修改密码信息
     *
     * @param $userid
     * @param $passwd
     *
     * @return
     */
    public function updateSecurity($userid, $passwd)
    {
        $security = new Security($userid);
        $security->passwd = $this->hashedOriginPasswd($userid, $passwd);

        $security->token = md5($userid . ":" . gethostname() . ":" . microtime());

        try {
            $security->update();
            return $security;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateSecurity, userid: $userid" . $ex->getMessage());
            return false;
        }
    }

    /**
     * @brief HASH密码：md5(sha1($userid : md5(origin_pwd@DOMAIN)))
     *
     * @param $userid     用户id
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

        return $this->hashedPasswd($userid, $passwd);
    }

    /**
     * @brief HASH密码：md5(sha1($userid : $passwd))
     *
     * @param $userid  用户id
     * @param $passwd  并非明文密码，它是明文密码的散列值，即md5(origin_pwd@DOMAIN)
     *
     * @return
     */
    public function hashedPasswd($userid, $passwd)
    {
        if (empty($passwd)) {
            return "";
        }

        return md5(sha1($userid . ":" . $passwd));
    }

    /**
     * @brief 验证密码
     *
     * @param $userid 用户id
     * @param $passwd 用户密码
     *
     * @return
     */
    public function verifyPasswd($userid, $passwd)
    {
        try {
            $userSecurity = new Security($userid);
            $userSecurity->get();

            if ($userSecurity->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
                $this->logger->error("The user has not security info, userid: $userid");
                return false; 
            }

            $passwd_in = $userSecurity->passwd;
            $passwd_out = $this->hashedOriginPasswd($userid, $passwd);
            if ($passwd_in && $passwd_in === $passwd_out) {
                return true; 
            } else {
                $this->logger->error("The passwd certification has failed, userid: $userid");
                return false; 
            }
        } catch (Exception $ex) {
            $this->logger->error("Server Error when verifyPasswd , userid: $uesrid");
            return false; 
        }
    }

    /**
     * @brief 生成sessionid
     *
     * @param $userid
     *
     * @return
     */
    public function createSession($userid)
    {
        $sessionid = md5(gethostname() . ":" . $userid . ":" . uniqid("", true) . ":" . mt_rand());
        return $sessionid;
    }

    /**
     * @brief 加密用户信息的部分字段，作为登录凭证
     *
     * @param $userid   用户信息
     * @param $clientip 用户登陆的IP
     *
     * @return
     */
    public function encryptUserInfo($userid, $clientip)
    {
        $now  = time();
        $data = "$userid\n$now\n$clientip";
        $key  = $this->getMcryptKeyByUserId($userid);

        $encryptData = EncryptUtls::encrypt($data, $key);
        return $encryptData;
    }

    /**
     * @brief 解密用户信息的字符串，获取对应用户信息
     *
     * @param $userid        用户的id
     * @param $encryptedData 要解密的数据
     *
     * @return
     */
    public function decryptUserInfo($userid, $encryptedData)
    {
        $key = $this->getMcryptKeyByUserId($userid);
        $originData = EncryptUtls::decrypt($encryptedData, $key);      
        if (!$originData) {
            $this->logger->error("failed to decrypt, userid : $userid");
            return false;
        }

        return $originData;
    }

    /**
     * @brief 获取Mcrypt加解密的key
     *            key   = ($token . $dynamic_version . SALT)
     *            token = 用户每次修改密码，token都会发生变化
     *
     * @param $userid 用户id
     *
     * @return
     */
    public function getMcryptKeyByUserId($userid)
    {
        $token = $this->getTokenByUserId($userid);
        $key   = $token . "***" . self::SALT;
        return $key;
    }

    /**
     * @brief 根据userid获取token
     *
     * @param $userid;
     *
     * @return
     */
    private function getTokenByUserId($userid)
    {
        $security = new Security($userid);
        $security->get();

        if ($security->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getTokenByUserId, userid: $userid");
            return "";
        }

        return $security->token;
    }
}

class EncryptUtls
{
    const ALGORITHM = "rijndael-256";
    const MODE = "cfb";
    const IV = "67adsfhjikidfr383798324jfdalj0sf";

    public static function encrypt($data, $key)
    {
        /* 打开加密算法和模式 */
        $td = mcrypt_module_open(self::ALGORITHM, '', self::MODE, '');
        
        /* 检测密钥长度并创建密钥 */
        $ks = mcrypt_enc_get_key_size($td);
        $key = substr(md5($key), 0, $ks);
    
        /* 初始化加密 */
        mcrypt_generic_init($td, $key, self::IV);

        /* 加密数据 */
        $encrypted = mcrypt_generic($td, $data);

        /* 加密结束，执行清理工作 */
        mcrypt_generic_deinit($td);

        /* 关闭模块 */
        mcrypt_module_close($td);

        return base64_encode($encrypted);
    }

    public static function decrypt($encryptedData, $key)
    {
        if (empty($encryptedData)) {
            return '';
        }

        $encryptedData = base64_decode($encryptedData);
        if (!$encryptedData) {
            return false;
        }

        /* 打开加密算法和模式 */
        $td = mcrypt_module_open(self::ALGORITHM, '', self::MODE, '');
        
        /* 检测密钥长度并创建密钥 */
        $ks = mcrypt_enc_get_key_size($td);
        $key = substr(md5($key), 0, $ks);
    
        /* 初始化加密 */
        mcrypt_generic_init($td, $key, self::IV);

        /* 加密数据 */
        $decrypted = mdecrypt_generic($td, $encryptedData);

        /* 加密结束，执行清理工作 */
        mcrypt_generic_deinit($td);

        /* 关闭模块 */
        mcrypt_module_close($td);

        return trim($decrypted);
    }
}
