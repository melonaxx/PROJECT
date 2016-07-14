<?php

class ProFormateValueSvc
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
     * @brief 查询规格值的信息
     *
     * @param fnameid
     **/
    public function getFormateValue($fnameid)
    {
        $fvalue = new Proformatevalue();

        try {
            $fvalue->gets($fnameid);
            return $fvalue;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getFormateValue".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 通过规格值ID查询规格值的信息
     *
     * @param fvalueid
     *
     * @return 规格值列表
     **/
    public function getFormateValueById($fvalueid)
    {
        $fvalue = new Proformatevalue($fvalueid);

        try {
            $fvalue->get();
            return $fvalue;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getFormateValueById".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 添加商品规格值
     *
     * @param fvaluename 规格值
     * @param fnameid 规格名称ID
     *
     * @return bool
     **/
    public function addfvalueinfo($addfvalue,$afnameid)
    {
        $fvalue = new Proformatevalue();
        $fvalue->formatnameid   = $afnameid;
        $fvalue->choice         = $addfvalue;

        try {
            $fvalue->insert();
            return $fvalue;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addfvalueinfo".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 修改商品规格值
     *
     * @param fvaluename 规格值
     * @param fnameid 规格名称ID
     *
     * @return bool
     **/
    public function editfvalueinfo($efvalue,$efvalueid)
    {
        $fvalue = new Proformatevalue();
        $fvalue->choice     = $efvalue;
        $fvalue->id         = $efvalueid;
        try {
            $fvalueres = $fvalue->update();
            return $fvalueres;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when editfvalueinfo".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除商品规格值
     *
     * @param fvalueid 规格值ID
     *
     * @return bool
     **/
    public function delfvalueinfo($dfvalueid)
    {
        $fvalue = new Proformatevalue();
        $fvalue->id         = $dfvalueid;
        $fvalue->isdelete   = 'Y';
        try {
            $fvalueres = $fvalue->update();
            return $fvalueres;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when delfvalueinfo".$ex->getMessage());
            return false;
        }
    }
}