<?php

class CallBoardSvc 
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

    public function showCallInfoList()
    {
        $callinfo = new CallBoard();
        $is_delete = CallBoard::IS_DELETE_FALSE;

        $result = $callinfo->gets(array("is_delete" => $is_delete));   

        if ($callinfo->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when showCallInfoList");
            return null;
        }

        return $result;
    }

    public function showCallInfo()
    {
        $callinfo = new CallBoard();
        $status = CallBoard::STATUS_SHOW;
        $is_delete = CallBoard::IS_DELETE_FALSE;

        $callinfo->get(array("status" => $status, "is_delete" => $is_delete));

        if ($callinfo->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when showCallInfo");
            return null;
        }

        return $callinfo; 
    }

    public function storeCallInfo($content)
    {
        $callinfo = new CallBoard();
        $callinfo->content = $content;

        try {
            $callinfo->insert();
            return true;
        } catch (Exception $ex) {
            $this->lgger->error("exception occurs when storeCallInfo" . $ex->getMessage());
            return false;
        }
    }

    public function updateShowStatus($id, $status)
    {
        $callinfo = new CallBoard($id);
        $callinfo->status = $status;

        try {
            $result = $callinfo->update();
            return $result;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateShowStatus, id: $id" . $ex->getMessage());
            return null;
        }

    }

    public function removeCallInfo($id)
    {
        $callinfo = new CallBoard($id);
        $callinfo->is_delete = CallBoard::IS_DELETE_TRUE;

        try {
            $result = $callinfo->update();
            return $result;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when removeCallInfo, id: $id" . $ex->getMessage());
            return null;
        }
    }
}
