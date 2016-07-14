<?php

class TransportinfoSvc
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

//添加
    public function add($data)
    {
        $add = new Transportinfo();
        $add->name = $data['name'];
        $add->payment = $data['payment'];
        $add->stateid = $data['stateid'];
        $add->cityid = $data['cityid'];
        $add->districtid = $data['districtid'];
        $add->address = $data['address'];
        $add->postcode = $data['postcode'];
        $add->contactname = $data['contactname'];
        $add->telphone = $data['telphone'];
        $add->mobile = $data['mobile'];
        $add->comment = $data['comment'];
        $add->isdelete = 'N';
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }


    public function edit($data)
    {
        $add = new Transportinfo($data['id']);
        $add->name = $data['name'];
        $add->payment = $data['payment'];
        $add->stateid = $data['stateid'];
        $add->cityid = $data['cityid'];
        $add->districtid = $data['districtid'];
        $add->address = $data['address'];
        $add->postcode = $data['postcode'];
        $add->contactname = $data['contactname'];
        $add->telphone = $data['telphone'];
        $add->mobile = $data['mobile'];
        $add->comment = $data['comment'];
        try {
            $pdata = $add->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }

    public function del($id)
    {
        $add = new Transportinfo($id);
        $add->isdelete = 'Y';
        try {
            $pdata = $add->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
}