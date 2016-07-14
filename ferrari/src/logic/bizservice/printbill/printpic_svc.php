<?php

class PrintPicSvc
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

    /*进行印刷单图片的添加*/
    public function addprintpic($printid ,$filename)
    {
        $pres = new PrintPic();

        $pres->printid  = $printid;
        $pres->filename = $filename;

        try {
            $ppic = $pres->insert();
            return $ppic;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addprintpic".$ex->getMessage());
            return false;
        }
    }

}