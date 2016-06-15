<?php

class CBlinkSvc 
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
     * @brief 添加车辆跟公司绑定关系
     * 
     * @param $companyid
     * @param $ebikeid
     *
     * @return
     */
    public function addCBlink($companyid, $ebikeid, $useid=0, $distribute=1)
    {
        $cblink = new CBlink();
        $cblink->useid      = $useid;
        $cblink->distribute = $distribute;  

        $cblink->update(array("companyid"=>$companyid, "ebikeid"=>$ebikeid));

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when addCBlink, companyid: $companyid; ebikeid: $ebikeid");
            return false;
         }

         return true;
    }
 
    /**
     * @brief 激活电动车
     *
     * @param $companyid
     * @param $ebikeid
     *
     * @return
     */
    public function activateEbike($companyid, $ebikeid)
    {
        try {
            $cblink = $this->getCBlinkByEbikeId($companyid, $ebikeid);
            if ($cblink) {
                $result = $this->updateEbikeRelate($ebikeid, $companyid);
            } else {
                $result = $this->createEbikeRelate($ebikeid, $companyid);
            }

            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when activateEbike, ebikeid: $ebikeid");
            return false;
        }
    }

    /**
     * @brief 解除绑定
     *
     * @param $companyid
     * @param $ebikeid
     *
     * @return
     */
    public function removeCBlink($companyid, $ebikeid)
    {
        $cblink = new CBlink();
        $cblink->is_delete = CBlink::IS_DELETE_TRUE;

        $result = $cblink->update(array("companyid" => $companyid, "ebikeid" => $ebikeid));

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when RemoveCBlink, companyid: $companyid; ebikeid: $ebikeid");
            return 0;
        }

        return $result;
    }
 
    /**
     * @brief 获取车辆关联信息
     *
     * @param $companyid
     * @param $distribute
     * @param $laborid
     *
     * @return
     */
    public function getCBlink($companyid, $distribute=null, $laborid=null)
    {
        $cblink = new CBlink();
        $is_delete = CBlink::IS_DELETE_FALSE;

        if (empty($laborid) && empty($distribute)) {
            $result =  $cblink->gets(array("companyid" => $companyid, "is_delete" => $is_delete));
        } else  if (empty($laborid)){
            $result = $cblink->gets(array("companyid" => $companyid, "distribute" => $distribute, "is_delete" => $is_delete));
        } else {
            $result = $cblink->gets(array("companyid" => $companyid, "distribute" => $distribute, "useid" => $laborid, "is_delete" => $is_delete));
        }

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getCBlink, companyid: $companyid");
            return null;
        } 

        return $result;
    }

    /**
     * @brief 根据劳务方获取车辆关联信息
     * 
     * @param $companyid
     * @param $laborid
     *
     * @return
     */
    public function getCBlinkByLab($companyid, $laborid)
    {
        $cblink = new CBlink();
        $is_delete = CBlink::IS_DELETE_FALSE;

        $result =  $cblink->gets(array("companyid" => $companyid, "useid" => $laborid, "is_delete" => $is_delete));

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getCBlink, companyid: $companyid");
            return null;
        } 

        return $result;
    }

    /**
     * @brief 取消分配
     *
     * @param $ebikeid
     * @param $useid
     *
     * @return
     */
    public function cancleDistribute($ebikeid, $companyid)
    {
        $cblink = new CBlink();
        $cblink->useid      = 0;
        $cblink->distribute = -1;

        $result = $cblink->update(array("ebikeid" => $ebikeid, "companyid" => $companyid));

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when cancleDistribute, ebikeid: $ebikeid; useid: $useid");
            return false;
        }

        return $result;
    }

    /**
     * @brief 获取使用方对应的车辆信息
     *
     * @param $useid
     *
     * @return
     */
    public function getCBlinkByUseId($useid, $ebikeid=0)
    {
        $cblink = new CBlink();
        $is_delete = CBlink::IS_DELETE_FALSE;

        if ($ebikeid) {
            $result = $cblink->get(array("useid" => $useid, "ebikeid" => $ebikeid, "is_delete" => $is_delete));
        } else {
            $result = $cblink->gets(array("useid" => $useid, "is_delete" => $is_delete));
        }

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getCBlinkByUseId, useid: $useid");
            return null;
        }

        return $result;
    }

    /**
     * @brief 取消合作时回收电车
     *
     * @param $companyid
     * @param $useid
     *
     * @return
     */
    public function removeEbikeRelate($companyid, $useid)
    {
        $cblink = new CBlink();
        $cblink->useid      = 0;
        $cblink->distribute = -1;

        $cblink->update(array("useid" => $useid, "companyid" => $companyid));

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when removeEbikeRelate, useid: $useid");
            return false;
        }

        return true;
    }

    /**
     * @brief 平台查看状态
     *
     * @param $companyid
     * @param $ebikeid
     * @param $useid
     *
     * @return
     */
    public function updateUseIdByCompanyId($companyid, $ebikeid, $useid, $distribute)
    {
        $cblink = new CBlink();
        $cblink->useid = $useid;
        $cblink->distribute = $distribute;

        $result = $cblink->update(array("companyid" => $companyid, "ebikeid" => $ebikeid));

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when updateUseIdByCompanyId, companyid: $companyid");
            return null;
        }

        return $result;
    }

    public function createEbikeRelate($ebikeid, $companyid)
    {
        $cblink = new CBlink();
        $cblink->ebikeid   = $ebikeid;
        $cblink->companyid = $companyid;

        try {
            $cblink->insert();
            return true;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when activateEbike; companyid: $companyid");
            return false;
        }
    }

    public function getCBlinkByEbikeId($companyid, $ebikeid)
    {
        $cblink = new CBlink();
        $cblink->get(array("companyid" => $companyid, "ebikeid" => $ebikeid));

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getCBlinkByEbikeId, ebikeid: $ebikeid");
            return false;
        }

        return $cblink;
    }

    public function updateEbikeRelate($ebikeid, $companyid)
    {
        $cblink = new CBlink();
        $cblink->is_delete = CBlink::IS_DELETE_FALSE;

        $cblink->update(array("companyid" => $companyid, "ebikeid" => $ebikeid));

        if ($cblink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getCBlinkByEbikeId, ebikeid: $ebikeid");
            return false;
        }

        return $cblink;
    }
}
