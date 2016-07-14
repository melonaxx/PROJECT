<?php

class ProSaleSvc
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
     * @brief 添加商品 - 销售信息列表
     *
     * @param
     * */
    public function addprosaleinfo($productid,$salesstatus)
    {
        $prosale = new ProSale();
        $prosale->productid 	= $productid;
        $prosale->salesstatus 	= $salesstatus;

        try {
            $pdata = $prosale->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }

}
