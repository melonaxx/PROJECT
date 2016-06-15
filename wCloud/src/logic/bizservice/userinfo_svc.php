<?php

class UserInfoSvc 
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
     * @brief 用户详情
     *
     * @param $userid   用户id
     * @param $mobileno 手机号
     *
     * @return
     */
    public function createUserInfo($userid, $mobileno)
    {
        $userinfo = new UserInfo($userid);
        $userinfo->mobileno = $mobileno;

        $userinfo->indate();
       
        return $userinfo;
    }

    /**
     * @brief 完善用户信息
     *
     * @param $uesrid 用户id
     * @param $name   姓名
     * @param $email  邮箱
     * @param $qq     QQ号
     * @param $wechat 微信
     *
     * @return
     */
    public function updateUserInfoByUserId($userid, $email, $qq=null, $wechat=null)
    {
        $userinfo = new UserInfo($userid);
        $userinfo->email  = $email;
        if ($qq) {
            $userinfo->qq = $qq;
        }
        if ($wechat) {
            $userinfo->wechat = $wechat;
        }

        try {
            $userinfo->update();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateUserInfo[$userid]:".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  验证登陆
     *
     * @param $mobileno 手机号 
     *
     * @return
     */
    public function getUserInfoByMobileNo($mobileno)
    {
        $userinfo = new UserInfo();
        $userinfo->get(array("mobileno" => $mobileno));

        if ($userinfo->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUserInfoByMobileNo, mobileno: $mobileno");
            return null;
        }

        return $userinfo;
    }

    /**
     * @brief 获取用户详情
     *
     * @param $userid
     *
     * @return
     */
    public function getUserInfoByUserId($userid)
    {
        $userinfo = new UserInfo($userid);
        $userinfo->get();

        if ($userinfo->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return null;
        }

        return $userinfo;
    }

    /**
     * @brief 获取手机号
     *
     * @param $userid
     *
     * @return 
     */
    public function getMobileNoByUserId($userid)
    {
        $userinfo = new UserInfo($userid);
        $userinfo->get();

        if ($userinfo->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getMobileNoByUserId, userid: $userid");
            return 0;
        }

        return $userinfo->mobileno;
    }

    /**
     * @brief 根据电车id获取用户信息
     *
     * @param $ebikeid
     *
     * @return
     */
    public function getUserInfoByEbikeId($ebikeid)
    {
        $userinfo = new UserInfo();
        $userinfo->get(array("ebikeid" => $ebikeid));

        if ($userinfo->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUserInfoByEbikeId, ebikeid: $ebikeid");
            return null;
        }

        return $userinfo;
    }

    /**
     * @brief 分配电车给骑士
     *
     * @param $userid
     * @param $ebikeid
     *
     * @return
     */
    public function addEbikeById($userid, $ebikeid)
    {
        $userinfo = new UserInfo($userid);
        $userinfo->ebikeid = $ebikeid;

        try {
            $userinfo->update();
            return true;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when addEbikeById, userid: $userid" . $ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 解除电车关联
     *
     * @param $userid
     *
     * @return
     */
    public function unwrapEbike($userid)
    {
        $userinfo = new UserInfo($userid);
        $userinfo->ebikeid = 0;

        try {
            $result = $userinfo->update();
            return $result;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when addEbikeById, userid: $userid" . $ex->getMessage());
            return false;
        }
    }

    public function updateKGropByUserId($userid, $kgid)
    {
        $userinfo = new UserInfo($userid);
        $userinfo->gropid = $kgid;

        try {
            $result = $userinfo->update();
            return $result;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateKGropByUserId, userid: $userid" . $ex->getMessage());
            return false;
        }
    }

    public function getUserInfoByKGropId($kgid)
    {
        $userinfo = new UserInfo();
        $result = $userinfo->gets(array("gropid" => $kgid));

        if ($userinfo->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUserInfoByKGropId, gropid: $kgid");
            return null;
        }

        return $result;
    }

    public function updateUserInfoByKGorpId($kgid)
    {
        $userinfo = new UserInfo();
        $userinfo->gropid = 0;
        $result = $userinfo->update(array("gropid" => $kgid));

        if ($userinfo->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when updateUserInfoByKGropId, gropid: $kgid");
            return false;
        }

        return $result;
    }
}
