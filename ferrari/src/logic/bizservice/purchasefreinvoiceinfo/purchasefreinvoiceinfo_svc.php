<?php

class PurchasefreinvoiceinfoSvc
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

    public function add($time,$userid,$number,$comment,$hanshui,$shuilv,$shuie,$noshui)
    {
        $add = new Purchasefreinvoiceinfo();
        $add->actiontime = $time;
        $add->staffid = $userid;
        $add->purchaseid = $number;
        $add->taxprice = $hanshui;
        $add->taxrate = $shuilv;
        $add->taxtotal = $shuie;
        $add->taxedprice = $noshui;
        $add->comment = $comment;
        try {
            $pdata = $add->insert();
            return $add->id;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
}