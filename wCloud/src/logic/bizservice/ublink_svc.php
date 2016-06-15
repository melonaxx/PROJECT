<?php

class UBlinkSvc 
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
     * @brief 创建用户--电车关联
     *
     * @param [int] $ebikeid [电车id]
     * @param [int] $userid  [用户id]
     *
     * @return
     */
    public function createUBlink($userid, $ebikeid) 
    {
        $ublink = new UBlink();
        $ublink->ebikeid  = $ebikeid;
        $ublink->userid   = $userid;

        try {
            $ublink->insert();
            return $ublink;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when addUBlink[$ebikeid.':'.$userid]：".$ex->getMessage());
            return false;
        }
    }
 
    public function showAllEbikeByUserId($userid)
    {
        $ublink = new UBlink();
        $is_delete = UBlink::IS_DELETE_FALSE;

        $result = $ublink->gets(array("userid" => $userid, "is_delete" => $is_delete));

        if ($ublink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when showAllEbikeByiUserId, userid: $userid");
            return null;
        }

        return $result;
    }

    public function getUBlinkByEbikeId($ebikeid, $userid=0)
    {
        $ublink = new UBlink();
        $is_delete = UBlink::IS_DELETE_FALSE;

        if ($userid) {
            $ublink->get(array("userid"=>$userid, "ebikeid" => $ebikeid, "is_delete" => $is_delete));
        } else {
            $ublink->get(array("ebikeid"=> $ebikeid, "is_delete"=> $is_delete));
        }

        if ($ublink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUBlink, userid: $userid; ebikeid: $ebikeid");
            return false;
        }

        return $ublink;
    } 

    public function updateIsDelete($userid, $ebikeid)
    {
        $ublink = new UBlink();
        $ublink->is_delete = UBlink::IS_DELETE_FALSE;

        $ublink->update(array("userid" => $userid, "ebikeid" => $ebikeid));

        if ($ublink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUBlink, userid: $userid; ebikeid: $ebikeid");
            return false;
        }

        return true;
    }
    public function destoryRelate($userid, $ebikeid=0)
    {
        $ublink = new UBlink();
        $ublink->is_delete = UBlink::IS_DELETE_TRUE;
        
        if ($ebikeid) {
            $result = $ublink->update(array("userid" => $userid, "ebikeid" => $ebikeid));
        } else {
            $result = $ublink->update(array("userid" => $userid));
        }

        if ($ublink->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when showAllEbikeByiUserId, userid: $userid");
            return null;
        }

        return $result;;
     }
} 
