<?php

class BankactactionSvc
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

    public function addbankactaction($bankid,$userid,$banktype,$bankcomment,$money,$newbalance)
    {
        $add = new Bankactaction();
        $add->bankid=$bankid;
        $add->staffid=$userid;
        $add->changepce=$money;
        $add->endingpce=$newbalance;
        $add->type = $banktype;
        $add->purpose = $bankcomment;
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