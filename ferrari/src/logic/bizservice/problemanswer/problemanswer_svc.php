<?php

class ProblemanswerSvc
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

    //添加问题答案
    public function addquestion($platid,$question,$answer)
    {
        $add = new Problemanswer();
        $add->platformid    = $platid;
        $add->problem    = $question;
        $add->answer   = $answer;

        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
    //编辑问题
    public function editquestion($id,$platformid,$problem,$answer)
    {
        $edit = new Problemanswer($id);
        $edit['platformid']   = $platformid;
        $edit['problem']      = $problem;
        $edit['answer']       = $answer;

        try {
            $pdata = $edit->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
    //删除问题
    public function delquest($id)
    {
        $del = new Problemanswer($id);
        $del['isdelete'] = 'Y';
        try {
            $pdata = $del->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }

}