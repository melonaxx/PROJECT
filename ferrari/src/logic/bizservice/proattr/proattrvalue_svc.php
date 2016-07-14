<?php

class ProAttrValueSvc
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
     * @brief 添加商品属性值
     *
     * @param 商品属性名称ID pattrnameid
     * @param 商品属性值 pattrvalue
     *
     * @return 商品属性值列表
     */
    public function addgoodsattrvalue($attribid,$optional)
    {
        $proattr = new ProAttrValue();
        $proattr->attribid     = $attribid;
        $proattr->optional    = $optional;
        try {
            $pdata = $proattr->insert();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsattrvalue".$ex->getMessage());
            return false;
        }
    }


    /**
     * @brief 修改商品属性值
     *
     * @param 商品属性值ID pattrvalueid
     * @param 商品属性值 pattrvalue
     *
     * @return bool
     */
    public function editgoodsattrvalue($pattrname,$pattrvid)
    {
        $proattr = new ProAttrValue();
        $proattr->optional      = $pattrname;
        $proattr->id            = $pattrvid;
        try {
            $pdata = $proattr->update();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when editgoodsattrvalue".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除商品属性值
     *
     * @param 商品属性值ID pattrvalueid
     *
     * @return bool
     */
    public function delgoodsattrvalue($pattrvalueid)
    {
        $proattr = new ProAttrValue();
        $proattr->isdelete      = 'Y';
        $proattr->id            = $pattrvalueid;
        try {
            $pdata = $proattr->update();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when delgoodsattrvalue".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 通过商品属性ID获取商品属性值
     *
     * @param 商品属性值ID pattrvalueid
     *
     * @return 商品属性值
     */
    public function getgoodsattrvaluebyid($pattrvalueid)
    {
        $proattr = new ProAttrValue();
        $proattr->id            = $pattrvalueid;
        try {
            $pdata = $proattr->get();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getgoodsattrvaluebyid".$ex->getMessage());
            return false;
        }
    }

}