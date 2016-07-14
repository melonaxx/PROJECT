<?php

class AccountcategorySvc
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

    public function addaccounttypeclass($data)
    {
        $add = new Accountcategory();
        $add['goryname']=$data['name'];
        $add['remark']=$data['comment'];
        $add['acctypeid']=$data['type'];
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }

     public function delaccounttypeclass($id)
    {
        $del = new Accountcategory($id);
        $del['status'] = "D";
        try {
            $pdata = $del->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }

    public function editaccounttypeclass($id,$name,$comment,$type)
    {
        $edit = new Accountcategory($id);
        $edit['goryname']=$name;
        $edit['remark']=$comment;
        $edit['acctypeid']=$type;
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