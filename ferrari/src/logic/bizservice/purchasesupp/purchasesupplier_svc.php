<?php

class PurchasesupplierSvc
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

    public function addsuppliers($data)
    {
        $add = new Purchasesupplier();
        $add->number=$data['number'];
        $add->name=$data['name'];
        $add->type=$data['type'];
        $add->level=$data['level'];
        $add->tax=$data['tax'];
        $add->balance=$data['balance'];
        $add->bankname=$data['bankname'];
        $add->banknumber=$data['banknumber'];
        $add->website=$data['website'];
        $add->contactname=$data['contactname'];
        $add->mobile=$data['mobile'];
        $add->phone=$data['phone'];
        $add->email=$data['email'];
        $add->fax=$data['fax'];
        $add->postcode=$data['postcode'];
        $add->stateid=$data['stateid'];
        $add->cityid=$data['cityid'];
        $add->districtid=$data['districtid'];
        $add->address=$data['address'];
        $add->comment=$data['comment'];
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //删除供应商
     public function delsuppliers($id)
    {
        $del = new Purchasesupplier($id);
        $del['isdelete'] = 'Y';
        try {
            $pdata = $del->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //编辑供应商
     public function updatesuppliers($data,$id)
    {
        $upda = new Purchasesupplier($id);
        $upda['number']=$data['number'];
        $upda['name']=$data['name'];
        $upda['type']=$data['type'];
        $upda['level']=$data['level'];
        $upda['tax']=$data['tax'];
        $upda['balance']=$data['balance'];
        $upda['bankname']=$data['bankname'];
        $upda['banknumber']=$data['banknumber'];
        $upda['website']=$data['website'];
        $upda['contactname']=$data['contactname'];
        $upda['mobile']=$data['mobile'];
        $upda['phone']=$data['phone'];
        $upda['email']=$data['email'];
        $upda['fax']=$data['fax'];
        $upda['postcode']=$data['postcode'];
        $upda['stateid']=$data['stateid'];
        $upda['cityid']=$data['cityid'];
        $upda['districtid']=$data['districtid'];
        $upda['address']=$data['address'];
        $upda['comment']=$data['comment'];
        try {
            $pdata = $upda->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }

}