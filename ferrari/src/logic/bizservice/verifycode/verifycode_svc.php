<?php

class VerifyCodeSvc
{
    private static $_ins = null;
    private $logger = null;
    private $redisclient = null;

    const CODE_SERVER_KEY = "default";
    const TIME_OUT = 300;  // 5分钟

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
     * @brief 向redis服务器写入验证码，并返回一个sessionid
     * 
     * @param $code 
     * 
     * @return 
     */
    public function setVerifyCode($code)
    {
        // 首先生成一个唯一的sessionid
        $sessionid = md5(gethostname() . ":" . microtime() . ":" . $code . ":" . mt_rand());

        $redisclient = $this->redisclient;
        try {
            // 将验证码的值写入redis，并设置5分钟过期
            $redisclient->setEx(self::CODE_SERVER_KEY, $sessionid, self::TIME_OUT, $code);
            return $sessionid;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when setVerifyCode: " . $ex->getMessage());
            return false;
        }
    }

    /** 
     * @brief 验证验证码是否准确
     * 
     * @param $sessionid 
     * @param $code 
     * 
     * @return 
     */
    public function verifyCode($sessionid, $code)
    {
        $redisclient = $this->redisclient;
        try {
            $actual_code = $redisclient->get(self::CODE_SERVER_KEY, $sessionid);

            // 不论对错，都要删除对应的验证码记录
            $redisclient->delete(self::CODE_SERVER_KEY, $sessionid);

            if ($actual_code && strcasecmp($actual_code, $code) === 0) {
                return true;
            }

            return false;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when setVerifyCode: " . $ex->getMessage());
            return false;
        }
    }
}
