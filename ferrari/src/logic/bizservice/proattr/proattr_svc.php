<?php

class ProAttrSvc
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
     * @brief 添加商品属性与商品属性值
     *
     * @param productid 商品ID
     * @param attrname 商品属性名称
     * @param attrvalue 商品属性值
     *
     * @return bool
     **/
    public function addproattrinfo($productid,$attrname,$attrvalue)
    {
        $proattr = new Proattr();
        $proattr->productid     = $productid;
        $proattr->attrnameid    = $attrname;
        $proattr->attrvalueid   = $attrvalue;

        try {
            $pdata = $proattr->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }

}