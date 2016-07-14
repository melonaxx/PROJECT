<?php

class MoneyrecodeSvc
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

//添加账户
    public function addrecode($supplierid,$userid,$kemuid,$comment,$bankid,$type,$money,$yifu,$qiankuan,$gysqiankuan)
    {
        $add = new moneyrecode();
        $add->infoid = $supplierid;
        $add->bankid = $bankid;
        $add->faccountid = $kemuid;
        $add->type = $type;
        $add->comment = $comment;
        $add->staffid = $userid;
        $add->tradesum = $money;
        $add->paymentalready = $yifu;
        $add->paymentremain = $qiankuan;
        $add->paymentreturn = $gysqiankuan;
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }

}