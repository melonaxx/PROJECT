<?php

class OrderMsgImgSvc
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

    /*添加订单中图片*/
    public function addmsgimg($orderid,$filename)
    {
        $msgsrc = new OrderMsgImg();
        $msgsrc->orderid  = $orderid;
        $msgsrc->filename = $filename;

        try {
            $msgsrc->insert();
            return $msgsrc->getKeyQuery();
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addmsgimg".$ex->getMessage());
            return false;
        }
    }

}