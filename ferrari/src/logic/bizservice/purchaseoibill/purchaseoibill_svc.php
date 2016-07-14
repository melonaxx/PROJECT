<?php

class PurchaseoibillSvc
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

    public function adddanju($gysid,$storeid,$purchaseid,$company,$userid,$nums,$prices,$kemuid,$type)
    {
        $add = new Purchaseoibill();
        $add->supplierid=$gysid;
        $add->storeid=$storeid;
        $add->purchaseid=$purchaseid;
        $add->companyid=$company;
        $add->userid=$userid;
        $add->total=$nums;
        $add->price=$prices;
        $add->faccountid=$kemuid;
        $add->storetype=$type;
        try {
            $pdata = $add->insert();
            return $add->id;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }


}