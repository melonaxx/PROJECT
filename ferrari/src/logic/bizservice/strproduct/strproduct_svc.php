<?php

class StrProductSvc
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
     * @brief  添加商品到仓库中
     *
     * @param $productid
     *
     * @return bool
     * */
    public function addStrProduct($productid,$storeid,$totalreal)
    {
        $strproduct = new StrProduct();
        if ($productid)
        {
            $strproduct->productid = $productid;
        }

        if ($storeid)
        {
            $strproduct->storeid = $storeid;
        }

        if ($totalreal)
        {
            $strproduct->totalreal = $totalreal;
        }


        try {
            $strproductres = $strproduct->insert();
            return $strproductres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addStrProduct".$ex->getMessage());
            return false;
        }
    }
    /**
     * 添加商品到仓库中
     */
    public function add_newgoods($number,$productid,$storeid)
    {
        $strproduct = new StrProduct();

        $strproduct['totalproduction'] = intval($number); //生产中数量

        $strproduct['productid'] = intval($productid); //商品id

        $strproduct['storeid'] = intval($storeid); //商品id

        try {

            $strproduct->insert();
            return true;

        } catch (Exception $ex) {

            return false;
        }
    }
}
