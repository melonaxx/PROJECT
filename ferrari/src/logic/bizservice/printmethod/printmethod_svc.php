<?php

class PrintMethodSvc
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

    /*进行印刷方式的添加*/
    public function addprintmethod($name ,$printunitid ,$type ,$price ,$comment)
    {
        $pmres = new PrintMethod();
        $pmres->name        = $name;
        $pmres->printunitid = $printunitid;
        $pmres->type        = $type;
        $pmres->price       = $price;
        $pmres->comment     = $comment;

        try {
            $pmres->insert();
            return $pmres;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addprintmethod".$ex->getMessage());
            return false;
        }
    }

    /*通过印刷方式ID获取一条印刷方式信息*/
    public function getprintmethodbyid($id)
    {
        $pmres = new PrintMethod();
        $pmres->id     = $id;

        try {
            $pdata = $pmres->get();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getprintmethodbyid".$ex->getMessage());
            return false;
        }
    }

    /*进行印刷单位的修改*/
    public function editprintmethod($id ,$name ,$printunitid ,$type ,$price ,$comment)
    {
        $pmethodres = new PrintMethod($id);
        $pmethodres->name        = $name;
        $pmethodres->printunitid = $printunitid;
        $pmethodres->type        = $type;
        $pmethodres->price       = $price;
        $pmethodres->comment     = $comment;

        try {
            $pmethodres->update();
            return $pmethodres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when editprintmethod".$ex->getMessage());
            return false;
        }
    }

}