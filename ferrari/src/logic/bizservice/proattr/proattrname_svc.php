<?php

class ProAttrNameSvc
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
     * @brief 添加商品属性名称
     *
     * @param attrname 商品属性名称
     *
     * @return bool
     **/
    public function addgoodsattr($goodattrname)
    {
        $proattrname = new ProAttrName();
        $proattrname->name     = $goodattrname;

        try {
            $pdata = $proattrname->insert();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsattr".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 修改商品属性名称
     *
     * @param attrname 商品属性名称
     * @param attrnameid 商品属性名称ID
     *
     * @return bool
     **/
    public function editgoodsattr($goodattrname,$goodattrid)
    {
        $proattrname = new ProAttrName();
        $proattrname->name     = $goodattrname;
        $proattrname->id     = $goodattrid;

        try {
            $pdata = $proattrname->update();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when editgoodsattr".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除商品属性名称
     *
     * @param attrnameid 商品属性名称ID
     *
     * @return bool
     **/
    public function delgoodsattr($goodattrid)
    {
        $proattrname = new ProAttrName();
        $proattrname->isdelete     = 'Y';
        $proattrname->id     = $goodattrid;

        try {
            $pdata = $proattrname->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when delgoodsattr".$ex->getMessage());
            return false;
        }
    }

}