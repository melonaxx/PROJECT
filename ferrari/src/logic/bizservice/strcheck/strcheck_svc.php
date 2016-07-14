<?php

class StrCheckSvc
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

    /**
     * @brief  添加仓库盘点单
     *
     * @param
     *
     * @return bool
     * */
    public function addStrCheck($comment,$newtotal,$productid,$storeid,$total,$oldtotal)
    {
        $strcheck = new StrCheck();
        $strcheck->comment   =$comment;
        $strcheck->newtotal  =$newtotal;
        $strcheck->productid =$productid;
        $strcheck->storeid   =$storeid;
        $strcheck->total     =$total;
        $strcheck->oldtotal  =$oldtotal;
        try {
            $strcheckres = $strcheck->insert();
            return $strcheckres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addShipAddress".$ex->getMessage());
            return false;
        }
    }

}
