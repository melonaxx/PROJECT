<?php

class StrLocationSvc
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

/*==========================库区start=================================*/
    /**
     * @brief 添加对应仓库的库区
     *
     * @param storeid 库区所有信息
     *
     * @return bool
     * */
    public function addStrArea($storeid,$placeno,$locationtype,$comment)
    {
        $strlocation = new StrLocation();
        $strlocation->parentid      = $storeid;
        $strlocation->placeno       = $placeno;
        $strlocation->locationtype  = $locationtype;
        $strlocation->comment       = $comment;
        $strlocation->storeid       = $storeid;

        try {
            $strlocation->insert();
            return $strlocation->getKeyQuery();
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addstrlocation".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 查询对应仓库的库区
     *
     * @param areaid
     *
     * @return 对应仓库的库区
     * */
    public function getStoreArea($areaid)
    {
        $strlocation = new StrLocation($areaid);

        try {
            $slocations = $strlocation->get();
            return $slocations;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getStoreArea".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 修改对应仓库的库区
     *
     * @param areaid 库区ID
     * @param  库区编号 备注
     *
     * @return 修改成功状态 bool
     * */
    public function updateStoreArea($areaid,$areano,$comment)
    {
        $strlocation = new StrLocation($areaid);
        $strlocation->placeno = $areano;
        $strlocation->comment = $comment;

        try {
            $slocations = $strlocation->update();
            return $slocations;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateStoreArea".$ex->getMessage());
            return false;
        }
    }

    /*==========================库区end==============================*/
    /*==========================货架start==============================*/
    /**
     * @brief 添加货架
     *
     * @param 货架所有的信息
     *
     * @return bool
     * */
    public function addStrShelve($storeid,$parentid,$placeno,$comment)
    {
        $strlocation = new StrLocation();
        $strlocation->parentid      = $parentid;
        $strlocation->placeno       = $placeno;
        $strlocation->comment       = $comment;
        $strlocation->storeid       = $storeid;

        try {
            $strlocation->insert();
            return $strlocation->getKeyQuery();
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addStrShelve".$ex->getMessage());
            return false;
        }
    }


    /**
     * @brief 查询单个货架为了修改
     *
     * @param shelveid
     *
     * @return 对应仓库的库区
     * */
    public function getStoreShelve($shelveid)
    {
        $strlocation = new StrLocation($shelveid);

        try {
            $slocations = $strlocation->get();
            return $slocations;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getStoreShelve".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 修改单个货架
     *
     * @param shelveid 货架ID
     * @param  货架的所有信息
     *
     * @return 修改成功状态 bool
     * */
    public function updateStoreShelve($shelveid,$shelveno,$shelvecomment)
    {
        $strlocation = new StrLocation($shelveid);
        $strlocation->placeno = $shelveno;
        $strlocation->comment = $shelvecomment;

        try {
            $slocations = $strlocation->update();
            return $slocations;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateStoreShelve".$ex->getMessage());
            return false;
        }
    }
    /*==========================货架end==============================*/
    /*==========================货位start==============================*/
    /**
     * @brief 添加货位
     *
     * @param 货位所有的信息
     *
     * @return bool
     * */
    public function addStrLocation($storeid,$parentid,$placeno,$comment)
    {
        $strlocation = new StrLocation();
        $strlocation->parentid      = $parentid;
        $strlocation->placeno       = $placeno;
        $strlocation->comment       = $comment;
        $strlocation->storeid       = $storeid;

        try {
            $strlocation->insert();
            return $strlocation->getKeyQuery();
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addStrLocation".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 查询单个货位为了修改
     *
     * @param locaionid
     *
     * @return 单个货位的所有信息
     * */
    public function getStoreLocation($locaionid)
    {
        $strlocation = new StrLocation($locaionid);

        try {
            $slocations = $strlocation->get();
            return $slocations;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getStoreLocation".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 修改单个货位
     *
     * @param locationid 货位ID
     * @param  货位的所有信息
     *
     * @return 修改成功状态 bool
     * */
    public function updateStoreLocations($locationid,$locationno,$locationcomment)
    {
        $strlocation = new StrLocation($locationid);
        $strlocation->placeno = $locationno;
        $strlocation->comment = $locationcomment;
        try {
            $slocations = $strlocation->update();
            return $slocations;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateStoreLocation".$ex->getMessage());
            return false;
        }
    }
    /*==========================货位end==============================*/

    /**
     * @brief 删除单个区、架、位
     *
     * @param   salid 区、架、位ID
     *
     * @retrun bool
     **/
    public function delSal($salid)
    {
        $strlocation = new StrLocation($salid);
        $strlocation->isdelete = 'Y';

        try {
            $slocations = $strlocation->update();
            return $slocations;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when delSal".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 单个区、架、位中子类的数量 total加一
     *
     * @param   salparentid 区、架、位ID
     *
     * @retrun bool
     **/
    public function addOne($salparentid)
    {
        $strlocation = new StrLocation($salparentid);
        $strlocation->total = 'Y';

        try {
            $slocations = $strlocation->update();
            return $slocations;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when delSal".$ex->getMessage());
            return false;
        }
    }

}
