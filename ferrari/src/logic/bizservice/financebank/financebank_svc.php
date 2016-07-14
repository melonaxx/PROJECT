<?php

class FinancebankSvc
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

//添加账户
    public function addbank($data)
    {
        $add = new financebank();
        $add->isdefault = $data['isdefault'];
        $add->name = $data['name'];
        $add->number = $data['number'];
        $add->balance = $data['balance'];
        $add->type = $data['type'];
        $add->comment = $data['comment'];
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
    //修改账户余额
    public function editbalance($id,$newbalance)
    {
        $edit = new financebank($id);
        $edit->balance = $newbalance;
        try {
            $pdata = $edit->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }

}