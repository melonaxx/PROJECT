<?php

class PurchasefreightinfoSvc
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

    public function add($pnumber,$time,$hanshui,$shuilv,$shuie,$noshui,$comment,$company,$waynumber,$bankid,$userid)
    {
        $add = new Purchasefreightinfo();
        $add->staffid = $userid;
        $add->actiontime = $time;
        $add->bankid = $bankid;
        $add->purchaseid = $pnumber;
        $add->taxprice = $hanshui;
        $add->taxrate = $shuilv;
        $add->taxtotal = $shuie;
        $add->taxedprice = $noshui;
        $add->shippingcpy = $company;
        $add->waybillnbr = $waynumber;
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