<?php
class UserroleSvc
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
//新增用户插入信息
    public function adduserrole($userid,$roleid)
    {
        $add = new Userrole();
        $add->userid = $userid;
        $add->roleid = $roleid;
        try {
            $pdata = $add->insert();
            return $add->id;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addpropartsinfo".$ex->getMessage());
            return false;
        }
    }
}