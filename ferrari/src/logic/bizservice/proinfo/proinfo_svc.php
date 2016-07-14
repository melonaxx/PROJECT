<?php

class ProInfoSvc
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
     * @brief 添加商品详细信息表
     *
     * @param 商品的详细信息列表
     *
     * @return bool
     **/
    public function addproinfodata($productid,$volume,$pricetag,$pricepurchase,$pricesell,$pricetotal,$weight,$unitid,$procomment,$formatid1,$formatid2,$formatid3,$formatid4,$formatid5,$valueid1,$valueid2,$valueid3,$valueid4,$valueid5)
    {
        $proinfo = new ProInfo();
        $proinfo->productid         = $productid;
        $proinfo->volume            = $volume;
        $proinfo->pricetag          = $pricetag;
        $proinfo->pricepurchase     = $pricepurchase;
        $proinfo->pricesell         = $pricesell;
        $proinfo->pricetotal        = $pricetotal;
        $proinfo->weight            = $weight;
        $proinfo->unitid            = $unitid;
        $proinfo->comment           = $procomment;
        $proinfo->formatid1         = $formatid1;
        $proinfo->formatid2         = $formatid2;
        $proinfo->formatid3         = $formatid3;
        $proinfo->formatid4         = $formatid4;
        $proinfo->formatid5         = $formatid5;
        $proinfo->valueid1          = $valueid1;
        $proinfo->valueid2          = $valueid2;
        $proinfo->valueid3          = $valueid3;
        $proinfo->valueid4          = $valueid4;
        $proinfo->valueid5          = $valueid5;

        try {
            $pdata = $proinfo->insert();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
}