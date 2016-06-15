<?php

class UClinkSvc 
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
     * @breif 建立公司-用户关系
     *
     * @param $companyid 公司id
     * @param $userid    用户id
     *
     * @return
     */
    public function createUClink($companyid, $userid)
    {
        $uclink = new UClink();
        $uclink->companyid = $companyid;
        $uclink->userid    = $userid;

        try {
            $uclink->insert();
            return true;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when createUClink, companyid: $companyid; userid: $userid");
            return false;
        }
    }

    /**
     * @brief 根据用户id获取公司id
     *
     * @param $userid 用户id
     *
     * @return
     */
    public function getCompanyIdByUserId($userid)
    {
        $uclink    = new UClink();
        $is_delete = UClink::IS_DELETE_FALSE;

        $uclink->get(array("userid" => $userid, "is_delete" => $is_delete));

        if ($uclink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getCompanyId, userid: $userid");
            return "";
        }

        return $uclink;
    }

    /**
     * @brief 获取企业下的所有用户id
     *
     * @param $companyid
     *
     * @return
     */
    public function getsUserIdByCompanyId($companyid)
    {
        $uclink    = new UClink();
        $is_delete = UClink::IS_DELETE_FALSE;

        $result = $uclink->gets(array("companyid" => $companyid, "is_delete" => $is_delete));

        if ($uclink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUser, companyid: $companyid");
            return "";
        }

        return $result;
    }

    /**
     * @brief 删除员工
     *
     * @param $userid
     *
     * @return
     */
    public function removeRelate($userid)
    {
        $uclink = new UClink();
        $uclink->is_delete = UClink::IS_DELETE_TRUE;

        $result = $uclink->update(array("userid" => $userid));

        if ($uclink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when delUClink, userid: $userid");
            return "";
        }

        return $result;
    }
}
