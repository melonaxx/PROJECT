<?php

class CustomerInfoSvc
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

    public function addcustomerdata($name,$nick,$type,$payment,$mobile,$telphone,$companyname,$postcode,$mailbox,$qq,$stateid,$cityid,$districtid,$address,$comment)
    {
        $cusmer = new CustomerInfo();
        $cusmer->name        = $name;
        $cusmer->nick        = $nick;
        $cusmer->type        = $type;
        $cusmer->payment     = $payment;
        $cusmer->mobile      = $mobile;
        $cusmer->telphone    = $telphone;
        $cusmer->companyname = $companyname;
        $cusmer->postcode    = $postcode;
        $cusmer->mailbox     = $mailbox;
        $cusmer->qq          = $qq;
        $cusmer->stateid     = $stateid;
        $cusmer->cityid      = $cityid;
        $cusmer->districtid  = $districtid;
        $cusmer->address     = $address;
        $cusmer->comment     = $comment;
        try {
            $cdata = $cusmer->insert();
            return $cdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addcustomerdata".$ex->getMessage());
            return false;
        }
    }

    public function add($khname,$nick,$mobile,$telphone,$postcode,$companyname,$stateid,$cityid,$districtid,$address)
    {
        $cusmer = new CustomerInfo();
        $cusmer->name        = $khname;
        $cusmer->nick        = $nick;
        $cusmer->mobile      = $mobile;
        $cusmer->telphone    = $telphone;
        $cusmer->companyname = $companyname;
        $cusmer->postcode    = $postcode;
        $cusmer->stateid     = $stateid;
        $cusmer->cityid      = $cityid;
        $cusmer->districtid  = $districtid;
        $cusmer->address     = $address;
        try {
            $cdata = $cusmer->insert();
            return $cusmer->id;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addcustomerdata".$ex->getMessage());
            return false;
        }
    }
}