<?php

class PurchaseproductSvc
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

    public function addpurchaseproduct($shuju)
    {
        $add = new Purchaseproduct();
        $add->productid = $shuju['productid'];
        $add->partsid = $shuju['partsid'];
        $add->price = $shuju['price'];
        $add->total = $shuju['total'];
        $add->storeid = $shuju['storeid'];
        $add->purchaseid = $shuju['purchaseid'];
        $add->totalway = $shuju['total'];
        $add->taxprice = $shuju['taxprice'];
        $add->taxrate = $shuju['shuilv'];
        $add->tax = $shuju['shuie'];
        $add->notaxprice = $shuju['shuijia'];
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