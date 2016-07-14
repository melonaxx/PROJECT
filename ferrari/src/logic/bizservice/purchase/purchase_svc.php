<?php

class CompanySvc
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

    public function addcompany($data)
    {
        $add = new Company();
        $add->name=$data['name'];
        $add->comment=$data['comment'];
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }


}
class PurchaseSvc
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

    public function addpurchase($data)
    {
        $add = new Purchase();
        $add->supplierid=$data['supplier'];
        $add->comment=$data['comment'];
        $add->staffid=$data['uid'];
        $add->number=time();
        $add->purchasecompanyid=$data['company'];
        $add->brief=$data['brief'];
        $add->actiondate=$data['time'];
        $add->total=$data['num'];
        $add->taxprice=$data['zongjia'];
        $add->storeid=$data['store'];
        $add->notaxprice=$data['shuijia'];
        $add->tax=$data['shuie'];
        $add->totalway=$data['num'];
        try {
            $pdata = $add->insert();
            return $add->id;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //返回修改采购单
    public function editpurchase($id)
    {
        $edit = new Purchase($id);
        $edit['statusaudit'] = 'R';
        try {
            $pdata = $edit->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //审核通过采购单
    public function passpurchase($id)
    {
        $pass = new Purchase($id);
        $pass['statusaudit'] = 'Y';
        try {
            $pdata = $pass->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //审核通过采购单
    public function delpurchase($id)
    {
        $del = new Purchase($id);
        $del['statusaudit'] = 'F';
        try {
            $pdata = $del->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //执行修改采购单
    public function editpur($id,$data)
    {
        $edit = new Purchase($id);
        $edit->supplierid=$data['supplier'];
        $edit->comment=$data['comment'];
        $edit->purchasecompanyid=$data['company'];
        $edit->brief=$data['brief'];
        $edit->total=$data['num'];
        $edit->taxprice=$data['zongjia'];
        $edit->storeid=$data['store'];
        $edit->notaxprice=$data['shuijia'];
        $edit->tax=$data['shuie'];
        $edit->totalway=$data['num'];
        $edit->statusaudit = 'N';
        try {
            $pdata = $edit->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
}