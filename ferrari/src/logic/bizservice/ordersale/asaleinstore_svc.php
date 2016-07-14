<?php
/**
 * 订单中售后信息
 */
class ASaleInStoreSvc
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

 //添加售后服务入库单asaleinstore
    public function addasaleinstore($orderid ,$storeid ,$productid ,$asaleid ,$shopid ,$inedstore ,$outstore ,$comment)
    {
        $saleres = new ASaleInStore();
        $saleres->orderid   =$orderid;
        $saleres->storeid   =$storeid;
        $saleres->productid =$productid;
        $saleres->asaleid   =$asaleid;
        $saleres->shopid    =$shopid;
        $saleres->inedstore =$inedstore;
        $saleres->outstore  =$outstore;
        $saleres->comment   =$comment;

        try {
            $result = $saleres->insert();
            return $result;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addasaleinstore".$ex->getMessage());
            return false;
        }
    }

}