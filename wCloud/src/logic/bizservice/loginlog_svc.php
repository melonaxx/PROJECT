<?php

class LoginLogSvc 
{
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
     * @brief 设置登陆日志
     *
     * @param $userid 
     * @param $loginip
     *
     * @return
     */
    public function addLoginLog($userid, $loginip, $status=0)
    {
        $loginlog = new LoginLog();
        $loginlog->userid    = $userid;
        $loginlog->loginip   = $loginip;
        $loginlog->status    = $status;
        $loginlog->logintime = date("Y-m-d H:i:s", time());

        try {
            $loginlog->insert();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addLoginLog, userid: $userid");
            return false;
        }
    }
}
