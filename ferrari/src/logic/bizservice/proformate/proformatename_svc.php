<?php

class ProFormateNameSvc
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
     * @brief 查询规格名的信息
     *
     * @param fnameid
     **/
    public function getFormateName($fnameid)
    {
        $fname = new Proformatename($fnameid);

        try {
            $fname->get();
            return $fname;
        } catch (Exception $ex) {
            // Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getFormateName".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除单个商品规格
     *
     * @param productid
     **/
    public function delgoodformatbyproid($proid)
    {
        $proformat = new Proformatename($proid);
        $proformat->status = 1;

        try {
            $delgformatflag->update();
            return $delgformatflag;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when delgoodformatbyproid".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 添加商品分类
     *
     * @param 商品分类名称
     *
     * @return bool
     **/
    public function addformate($formatename)
    {
        $fvalue = new Proformatename();
        $fvalue->name = $formatename;
        try {
            $fvalue->insert();
            return $fvalue;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addformate".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除商品分类
     *
     * @param 商品分类名称ID
     *
     * @return bool
     **/
    public function delfnamebyid($fnameid)
    {
        $fname = new Proformatename($fnameid);
        $fname->isdelete = 'Y';
        try {
            $fname->update();
            return $fname;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when delfnamebyid".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 修改商品分类
     *
     * @param 商品分类名称ID 商品分类名称
     *
     * @return bool
     **/
    public function editfnamebyid($eformatename,$eformateid)
    {
        $fname = new Proformatename($eformateid);
        $fname->name = $eformatename;
        try {
            $fname->update();
            return $fname;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when editfnamebyid".$ex->getMessage());
            return false;
        }
    }
}