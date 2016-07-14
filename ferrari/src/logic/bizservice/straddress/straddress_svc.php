<?php

class StrAddressSvc
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
     * @brief  添加仓库的发货址
     *
     * @param 省、市
     *
     * @return bool
     * */
    public function addShipAddress($storeid,$pronumber,$citynumber)
    {
        $straddress = new StrAddress();
        $straddress->storeid = $storeid;
        $straddress->stateid = $pronumber;
        $straddress->cityid = $citynumber;

        try {
            $straddres = $straddress->insert();
            return $straddres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addShipAddress".$ex->getMessage());
            return false;
        }
    }

}
