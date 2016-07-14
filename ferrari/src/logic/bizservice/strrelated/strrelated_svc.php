<?php

class StrRelatedSvc
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
     * @brief  添加商品到对应的仓库中
     *
     * @param 商品ID 仓库ID
     *
     * @return bool
     * */
    public function addStoreToProduct($storeid,$productid,$areaid,$shelvesid,$locationid)
    {
        $strrelated = new StrRelated();
        $strrelated->storeid        = $storeid;
        $strrelated->productid      = $productid;
        $strrelated->areaid         = $areaid;
        $strrelated->shelvesid      = $shelvesid;
        $strrelated->locationid     = $locationid;

        try {
            $strrelres = $strrelated->insert();
            return $strrelres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addStoreToProduct".$ex->getMessage());
            return false;
        }
    }

}
