<?php

class OrdercategorySvc
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

//添加
    public function add($name,$comment)
    {
        $add = new Ordercategory();
        $add->name = $name;
        $add->remark = $comment;
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }


    public function edit($id,$name,$comment)
    {
        $add = new Ordercategory($id);
        $add->name = $name;
        $add->remark = $comment;
        try {
            $pdata = $add->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }

    public function del($id)
    {
        $add = new Ordercategory($id);
        $add->isdelete = 'Y';
        try {
            $pdata = $add->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
}