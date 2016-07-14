<?php

class PlatformSvc
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


    //添加品台
    public function addplatform($data)
    {
        $add = new Platform();
        $add->name = $data['name'];
        $add->body = $data['body'];

        try {
            $add->insert();
            return $add;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsbrand".$ex->getMessage());
            return false;
        }
    }
    //修改平台状态为删除
    public function delplatform($id)
    {
        $del = new Platform($id);
        $del['isdelete'] = 'Y';
        try {
            $pdel = $del->update();
            return $pdel;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsbrand".$ex->getMessage());
            return false;
        }
    }
    public function editplatform($id,$name,$body)
    {
        $edit = new Platform($id);
        $edit['name'] = $name;
        $edit['body'] = $body;
        try {
            $pdel = $edit->update();
            return $pdel;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsbrand".$ex->getMessage());
            return false;
        }
    }
}