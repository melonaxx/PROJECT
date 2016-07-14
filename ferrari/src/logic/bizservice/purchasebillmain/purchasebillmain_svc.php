<?php

class PurchasebillmainSvc
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

    
    public function add($time,$userid,$type,$number,$hanshui,$shuilv,$shuie,$noshui,$comment,$bankid)
    {
        $add = new Purchasebillmain();
        $add->actiontime = $time;
        $add->staffid = $userid;
        $add->stamptype = $type;
        $add->purchaseid = $number;
        $add->taxprice = $hanshui;
        $add->taxrate = $shuilv;
        $add->taxtotal = $shuie;
        $add->taxedprice = $noshui;
        $add->comment = $comment;
        $add->bankid = $bankid;
        try {
            $pdata = $add->insert();
            return $add->id;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
}