<?php

class ProductSvc
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
     * @brief 查询是否是二手商品
     *
     * @param
     * */
    public function getProductquality($productid)
    {
        $product = new Porduct();

        try {
            $pdata = $product->get();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }


    /**
     * @brief 添加商品信息product表
     *
     * @param 商品的信息列表
     *
     * @return bool
     **/
    public function addoneproductinfo($number,$name,$brandid,$categoryid,$producttype,$productquality,$serialnumber,$barcode,$total,$indexpic)
    {
        $product = new Product();
        $product->number            = $number;
        $product->name              = $name;
        $product->brandid           = $brandid;
        $product->categoryid        = $categoryid;
        $product->producttype       = $producttype;
        $product->productquality    = $productquality;
        $product->serialnumber      = $serialnumber;
        $product->barcode           = $barcode;
        $product->total             = $total;
        $product->image             = $indexpic;

        try {
            $product->insert();
            return $product->getkeyQuery();
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addoneproductinfo".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除单个商品信息
     *
     * @param 商品ID
     *
     * @return bool
     **/
    public function deletegoodsbyid($productid)
    {
        $product = new Product($productid);
        $product->isdelete   = 'Y';

        try {
            $delgoodflag = $product->update();
            return $delgoodflag;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when deletegoodsbyid".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 通过商品ID查找单个商品的详细信息
     *
     * @param 商品ID
     *
     * @return 单个商品的详细信息
     **/
    public function getgoodsbyid($productid)
    {
        $product = new Product($productid);

        try {
            $getproflag = $product->get();
            return $getproflag;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getgoodsbyid".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 通过商品ID修改单个商品的详细信息
     *
     * @param 单个商品的详细信息
     *
     * @return bool
     **/
    public function editgoodsbyid($productid,$number,$name,$brandid,$categoryid,$producttype,$productquality,$serialnumber,$barcode,$total)
    {
        $product = new Product();
        $product->productid        = $productid;
        $product->number           = $number;
        $product->name             = $name;
        $product->brandid          = $brandid;
        $product->categoryid       = $categoryid;
        $product->producttype      = $producttype;
        $product->productquality   = $productquality;
        $product->serialnumber     = $serialnumber;
        $product->barcode          = $barcode;
        $product->total            = $total;

        try {
            $editproflag = $product->update();
            return $editproflag;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when editgoodsbyid".$ex->getMessage());
            return false;
        }
    }


}