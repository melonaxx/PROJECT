<?php

class ProPartsSvc
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
     * @brief 添加商品配件信息
     *
     * @param 商品配件的详细信息列表
     *
     * @return bool
     **/
    public function addpropartsinfo($prodid,$partsid,$partssum)
    {
        $proparts = new ProParts();
        $proparts->productid    = $prodid;
        $proparts->subid        = $partsid;
        $proparts->total        = $partssum;

        try {
            $pdata = $proparts->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addpropartsinfo".$ex->getMessage());
            return false;
        }
    }


}