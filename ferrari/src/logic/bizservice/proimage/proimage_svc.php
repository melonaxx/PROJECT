<?php

class ProimageSvc
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
     * @brief 添加商品图片
     *
     * @param 商品图片名称
     * */
    public function addproimageinfo($prodid,$imagepath,$imagesize,$imagename)
    {
        $imageinfo = new ProImage();

        $imageinfo->productid       = $prodid;
        $imageinfo->filename        = $imagename ;
        $imageinfo->filesize        = $imagesize;
        $imageinfo->url             = $imagepath ;
        $imageinfo->filemd5         = $imagename ;

        try {
            $imagflag = $imageinfo->insert();
            return $imagflag;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addproimageinfo".$ex->getMessage());
            return false;
        }
    }

}
