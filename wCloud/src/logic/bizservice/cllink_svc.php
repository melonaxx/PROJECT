<?php

class CLlinkSvc 
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
     * @brief 根据公司id获取合作的劳务方id
     *
     * @param $companyid 公司id
     *
     * @return
     */
    public function getsLabIdByCompanyId($companyid)
    {
        $cllink = new CLlink();
        $is_delete = CLlink::IS_DELETE_FALSE;

        $result = $cllink->gets(array("platformid" => $companyid, "is_delete" => $is_delete));

        if ($cllink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getsLabId, platformid: $companyid");
            return null;
        }  
    
        return $result;             
    }

    /**
     * @brief 添加劳务方
     *
     * @param $platformid
     * @param $laborid
     *
     * @return
     */
    public function addLabor($platformid, $laborid, $userid)
    {
        $cllink = new CLlink();
        $cllink->platformid = $platformid;
        $cllink->laborid    = $laborid;
        $cllink->userid     = $userid;

        try {
            $cllink->insert();
            return true;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when addLabor, platformid: $platformid");
            return false;
        }
    }

    /**
     * @brief 删除劳务方
     *
     * @param $platformid
     * @param $laborid
     *
     * @return
     */
    public function removeLabor($platformid, $laborid) 
    {
        $cllink = new CLlink();
        $cllink->is_delete = CLlink::IS_DELETE_TRUE;

        $cllink->update(array("platformid" => $platformid, "laborid" => $laborid));

        if ($cllink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when removeLabor, platformid: $platformid; laborid: $laborid");
            return false;
        }

        return true;
    }

    /**
     * @brief 获取平台信息
     *
     * @param $laborid
     *
     * @return
     */
    public function getsPlatformByLaborId($laborid)
    {
        $cllink = new CLlink();
        $is_delete = CLlink::IS_DELETE_FALSE;

        $result = $cllink->gets(array("laborid" => $laborid, "is_delete" => $is_delete));

        if ($cllink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getLabor, laborid: $laborid");
            return null;
        }

        return $result;
    }

    /**
     * @brief 劳务方更换管理员工
     *
     * @param $platformid
     * @param $laborid
     * @param $userid
     *
     * @return
     */
    public function updateEmployee($platformid, $laborid, $userid)
    {
        $cllink = new CLlink();
        $cllink->userid = $userid;

        $result = $cllink->update(array("platformid" => $platformid, "laborid" => $laborid));

        if ($cllink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when updateEmployee, userid: $userid");
            return null;
        }

        return $result;
    }

    /**
     * @brief 根据平台和劳务方,
     *        精确匹配关联的信息
     *
     * @param $platformid
     * @param $laborid
     *
     * @return
     */
    public function getPlatform($platformid, $laborid)
    {
        $cllink = new CLlink();
        $is_delete = CLlink::IS_DELETE_FALSE;

        $cllink->get(array("platformid" => $platformid, "laborid" => $laborid, "is_delete" => $is_delete));

        if ($cllink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getPlatform, platformid: $platformid; laborid: $laborid");
            return null;
        }

        return $cllink;
    }

    /**
     * @brief 获取用户下管理劳务方数量
     *
     * @param $userid
     *
     * @return
     */
    public function getLaborByUserId($userid)
    {
        $cllink = new CLlink();
        $is_delete = CLlink::IS_DELETE_FALSE;

        $result = $cllink->gets(array("userid" => $userid, "is_delete" => $is_delete));

        if ($cllink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when updateEmployee, userid: $userid");
            return null;
        }

        return $result;
    }

    /**
     * @brief 解除删除员工的与劳务方关系
     *
     * @param $companyid
     * @param $userid
     *
     * @return
     */
    public function updateCLlink($companyid, $userid)
    {
        $cllink = new CLlink();
        $cllink->userid = 0;

        $result = $cllink->update(array("platformid" => $companyid, "userid" => $userid));

        if ($cllink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when updateCLlink, companyid: $companyid");
            return null;
        }

        return $result;
    }
}
