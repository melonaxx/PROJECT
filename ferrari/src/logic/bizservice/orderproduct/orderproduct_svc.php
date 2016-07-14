<?php

class OrderproductSvc
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

//添加订单商品
    public function add($orderid,$proid,$singleprice,$goodsnum,$youhui,$pay,$procomment)
    {
        $add = new Orderproduct();
        $add->orderid = $orderid;
        $add->productid = $proid;
        $add->total = $goodsnum;
        $add->price = $singleprice;
        $add->discount = $youhui;
        $add->payment = $pay;
        $add->comment = $procomment;
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
}