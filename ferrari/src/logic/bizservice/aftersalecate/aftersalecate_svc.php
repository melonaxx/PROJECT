<?php
/**
 * 订单售后分类表
 */
class AfterSaleCateSvc
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

    /*进行售后信息的分类添加*/
    public function addAfterCate($catename,$comment)
    {
        $aftercate = new AfterSaleCate();
        $aftercate->catename = $catename;
        $aftercate->comment  = $comment;

        try {
            $afterres = $aftercate->insert();
            return $afterres;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addAfterCate".$ex->getMessage());
            return false;
        }
    }

    /*修改售后信息列表*/
    public function editAfterCate($id,$catename,$comment)
    {
        $aftercate = new AfterSaleCate($id);
        $aftercate->catename = $catename;
        $aftercate->comment  = $comment;

        try {
            $afterres = $aftercate->update();
            return $afterres;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when editAfterCate".$ex->getMessage());
            return false;
        }
    }

    /*删除售后信息列表*/
    public function delAfterCate($id)
    {
        $aftercate = new AfterSaleCate($id);
        $aftercate->isdelete = 'Y';

        try {
            $afterres = $aftercate->update();
            return $afterres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when delAfterCate".$ex->getMessage());
            return false;
        }
    }

}
