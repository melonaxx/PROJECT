<?php

class PurchasefinanceSvc
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

    public function addfinance($bank)
    {
        $add = new Purchasefinance();
        $add->purchaseid = $bank['purchaseid'];
        $add->paymenttotal = $bank['paymenttotal'];
        $add->paymentremain = $bank['paymentremain'];
        $add->supplierid = $bank['supplierid'];
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
