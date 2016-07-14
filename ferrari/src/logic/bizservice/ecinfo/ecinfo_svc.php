<?php

class ExpresscompanyinfoSvc
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

    public function addecinfo($data)
    {
        $add = new Expresscompanyinfo();
        $add->name=$data['name'];
        $add->payment=$data['payment'];
        $add->fee=$data['fee'];
        $add->stateid=$data['stateid'];
        $add->cityid=$data['cityid'];
        $add->districtid=$data['countyid'];
        $add->address=$data['address'];
        $add->postcode=$data['postcode'];
        $add->contactname=$data['contactname'];
        $add->telphone=$data['telphone'];
        $add->mobile=$data['mobile'];
        $add->body=$data['body'];
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    public function editecinfo($id,$shuju)
    {
        $edit = new Expresscompanyinfo($id);
        $edit['name']=$shuju['name'];
        $edit['payment']=$shuju['payment'];
        $edit['fee']=$shuju['fee'];
        $edit['stateid']=$shuju['stateid'];
        $edit['cityid']=$shuju['cityid'];
        $edit['districtid']=$shuju['districtid'];
        $edit['address']=$shuju['address'];
        $edit['postcode']=$shuju['postcode'];
        $edit['contactname']=$shuju['contactname'];
        $edit['telphone']=$shuju['telphone'];
        $edit['mobile']=$shuju['mobile'];
        $edit['body']=$shuju['body'];
        try {
            $pdata = $edit->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    public function startecinfo($id)
    {
        $edit = new Expresscompanyinfo($id);
        $edit['status']='Y';
        try {
            $pdata = $edit->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    public function stopecinfo($id)
    {
        $edit = new Expresscompanyinfo($id);
        $edit['status']='S';
        try {
            $pdata = $edit->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
}