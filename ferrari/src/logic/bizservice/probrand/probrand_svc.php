<?php

class ProBrandSvc
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
     * @brief  查询商品品牌名称
     *
     * @param 商品品牌ID
     *
     * @return 品牌名称
     * */
    public function getbrandbyid($brandid)
    {
        $probrand = new ProBrand();
        $probrand->id = $brandid;

        try {
            $probrand->get();
            return $probrand;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getbrandbyid".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  添加商品品牌名称
     *
     * @param 商品品牌名称
     *
     * @return bool
     * */
    public function addgoodsbrand($brandname)
    {
        $probrand = new ProBrand();
        $probrand->name = $brandname;

        try {
            $probrand->insert();
            return $probrand->getkeyQuery();
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsbrand".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  添加商品子品牌名称
     *
     * @param 商品子品牌名称
     *
     * @return bool
     * */
    public function addgoodsbrandchild($brandname,$parendid)
    {
        $probrand = new ProBrand();
        $probrand->name     = $brandname;
        $probrand->parentid = $parendid;

        try {
            $probrand->insert();
            return $probrand->getkeyQuery();
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsbrandchild".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  删除单个品牌
     *
     * @param 商品品牌ID
     *
     * @return bool
     * */
    public function delgoodsbrand($brandid)
    {
        $probrand = new ProBrand();
        $probrand->id     = $brandid;
        $probrand->isdelete = 'Y';
        try {
            $brandflag = $probrand->update();
            return $brandflag;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when delgoodsbrand".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  修改单个品牌
     *
     * @param 商品品牌ID 商品品牌名称
     *
     * @return bool
     * */
    public function editgoodsbrand($brandid,$brandname)
    {
        $probrand = new ProBrand();
        $probrand->id     = $brandid;
        $probrand->name = $brandname;
        try {
            $brandflag = $probrand->update();
            return $brandflag;
        } catch (Exception $ex) {
            // Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when editgoodsbrand".$ex->getMessage());
            return false;
        }
    }

}
