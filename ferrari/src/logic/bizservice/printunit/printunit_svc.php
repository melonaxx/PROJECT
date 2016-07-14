<?php

class PrintunitSvc
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

    /*进行印刷单位的添加*/
    public function add($name,$comment)
    {
        $add = new Printunit();
        $add->name = $name;
        $add->comment = $comment;

        try {
            $add->insert();
            return $add;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when add".$ex->getMessage());
            return false;
        }
    }

    /*进行印刷单位的修改*/
    public function editprintunit($id,$name,$comment)
    {
        $punitres = new Printunit();
        $punitres->id      = $id;
        $punitres->name    = $name;
        $punitres->comment = $comment;

        try {
            $punitres->update();
            return $punitres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when editprintunit".$ex->getMessage());
            return false;
        }
    }

}