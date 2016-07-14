<?php

class ProUnitSvc
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
     * @brief 添加单位的名称
     *
     * @param
     *
     * @return
     */
    public function addgoodsunits($unitname)
    {
        $prounit = new ProUnit();
        $prounit->name    = $unitname;

        try {
            $pdata = $prounit->insert();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsunits".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 修改单位的名称
     *
     * @param unitname 单位名称
     * @param unitid 单位ID
     *
     * @return bool
     */
    public function editgoodsunits($unitname,$unitid)
    {
        $prounit = new ProUnit();
        $prounit->name    = $unitname;
        $prounit->id    = $unitid;

        try {
            $pdata = $prounit->update();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when editgoodsunits".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除单位的名称
     *
     * @param unitid 单位ID
     *
     * @return bool
     */
    public function delgoodsunits($unitid)
    {
        $prounit = new ProUnit();
        $prounit->id    = $unitid;
        $prounit->isdelete    = 'Y';

        try {
            $pdata = $prounit->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when delgoodsunits".$ex->getMessage());
            return false;
        }
    }

}