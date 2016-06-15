<?php
	
class EbikeSvc 
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
     * @brief 电车注册
     *
     * @param [varchar] $mobel [电车型号]
     * @param [varchar] $seqno [电车编号]
     *
     * @return
     */
    public function addEbike($mobel, $seqno, $brand='waimaiw', $remarks="")
    {
        $ebike          = new Ebike();
        $ebike->mobel   = $mobel;
        $ebike->seqno   = $seqno;
        $ebike->brand   = $brand;
        $ebike->remarks = $remarks;

        try {
            $ebike->insert();
            return $ebike;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when addEbike[$mobel]：".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  匹配电车id，用于验证注册
     *
     * @param  $seqno 电车序列号
     *
     * @return 
     */
    public function getEbikeBySeqno($seqno)
    {
        $ebike = new Ebike();
        $ebike->get(array('seqno'=>$seqno));

        if ($ebike->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getEbikeBySeqno, seqno: $seqno");
            return null;
        }

        return $ebike;
    }

    /**
     * @brief 根据电车id获取电车信息
     *
     * @param $id 
     *
     * @return
     */
    public function getEbikeById($id, $exception=null)
    {
        $ebike = new Ebike($id);
        if (empty($exception)) {
            $ebike->get();
        } else {
            $ebike->get(array("id" => $id, "exception" => $exception));
        }

        if ($ebike->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getEbikeById, id: $id");
            return null;
        }

        return $ebike;
    }

    /**
     * @brief 报警异常
     *
     * @param $ebikeid
     * @param $excep
     *
     * @return
     */
    public function updateExceptionByEbikeId($ebikeid, $excep)
    {
        $ebike = new Ebike($ebikeid);
        $ebike->exception = $excep;

        try {
            $ebike->update();
            return ture;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when updateExceptionByEbikeId, ebikeid: $ebikeid");
            return false;
        } 
    }

    public function updateDistribute($ebikeid, $distribute)
    {
        $ebike = new Ebike($ebikeid);
        $ebike->allot = $distribute;

        try {
            $result = $ebike->update();
            return $result;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when updateExceptionByEbikeId, ebikeid: $ebikeid");
            return false;
        } 
    }

    public function updateEbikeStatus($ebikeid, $status)
    {
        $ebike = new Ebike($ebikeid);
        $ebike->status = $status;

        try {
            $result = $ebike->update();
            return $result;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when updateExceptionByEbikeId, ebikeid: $ebikeid");
            return false;
        } 
    }
}
	
