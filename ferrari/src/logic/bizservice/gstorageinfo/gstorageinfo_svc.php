<?php

class GstorageinfoSvc
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

    public function addsdj($danju,$storeid,$pid,$num,$price,$gold,$comment)
    {
        $add = new Gstorageinfo();
        $add->infoid=$danju;
        $add->storeid=$storeid;
        $add->productid=$pid;
        $add->total=$num;
        $add->price=$price;
        $add->payment=$gold;
        $add->body=$comment;
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