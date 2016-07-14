<?php
class UserinfoSvc
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
    public function adduserinfo($data)
    {
        $add = new Userinfo();
        $add->userid = $data['userid'];
        $add->departmentid = $data['partment'];
        $add->realname = $data['realname'];
        $add->purchasecompid = $data['company'];
        $add->salesid = $data['companysales'];
        $add->number = $data['number'];
        $add->tel = $data['tel'];
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addpropartsinfo".$ex->getMessage());
            return false;
        }
    }
    //修改信息
    public function updateuserinfo($data,$id)
    {
        $add = new Userinfo($id);
        // $add->userid = $data['userid'];
        $add->departmentid = $data['departmentid'];
        $add->realname = $data['realname'];
        $add->purchasecompid = $data['purchasecompid'];
        $add->salesid = $data['salesid'];
        $add->number = $data['number'];
        $add->tel = $data['tel'];
        $add->roleid = $data['roleid'];
        try {
            $pdata = $add->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addpropartsinfo".$ex->getMessage());
            return false;
        }
    }
}