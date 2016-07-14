<?php

class PurchasebillattachSvc
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

    
    public function add($result,$kemuid,$kemujine,$enum)
    {
        $add = new Purchasebillattach();
        $add->purchasebillid = $result;
        $add->faccountid = $kemuid;
        $add->price = $kemujine;
        $add->direction = $enum;
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
}