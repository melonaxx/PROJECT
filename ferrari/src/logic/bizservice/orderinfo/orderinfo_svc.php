<?php

class OrderinfoSvc
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

//添加订单
    public function addmainorder($time,$onlineid,$orderclass,$qudao,$shop,$store,$guanlian,$serviceid,$isreceive,$status,$isbill,$billtype,$comment,$youhuis,$pays,$cusmsg,$khid,$type,$deliversta,$desstoresta)
    {
        $add = new Orderinfo();
        $add->orderdate = $time;
        $add->onlineid = $onlineid;
        $add->categoryid = $orderclass;
        $add->channelid = $qudao;
        $add->companyid = $shop;
        $add->storeid = $store;
        $add->relatedid = $guanlian;
        $add->serviceid = $serviceid;
        $add->isreceive = $isreceive;
        $add->orstatus = $status;
        $add->isbill = $isbill;
        $add->billtype = $billtype;
        $add->comment = $comment;
        $add->discount = $youhuis;
        $add->ypayment = $pays;
        $add->cusmsg = $cusmsg;
        $add->customerid = $khid;
        $add->type = $type;
        $add->deliversta = $deliversta;
        $add->desstoresta = $desstoresta;
        try {
            $pdata = $add->insert();
            return $add->id;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
    // 审核通过主订单
    public function checkmain($id)
    {
        $check = new Orderinfo($id);
        $check->orstatus = 'P';
        try {
            $pdata = $check->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
    // 删除订单
    public function delmain($id)
    {
        $check = new Orderinfo($id);
        $check->isdelete = 'Y';
        try {
            $pdata = $check->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
    //编辑订单
    public function editmain($id,$time,$onlineid,$orderclass,$qudao,$shop,$store,$guanlian,$serviceid,$isreceive,$status,$isbill,$billtype,$comment,$youhuis,$pays,$cusmsg,$khid,$type)
    {
        $add = new Orderinfo($id);
        $add->orderdate = $time;
        $add->onlineid = $onlineid;
        $add->categoryid = $orderclass;
        $add->channelid = $qudao;
        $add->companyid = $shop;
        $add->storeid = $store;
        $add->relatedid = $guanlian;
        $add->serviceid = $serviceid;
        $add->isreceive = $isreceive;
        $add->orstatus = $status;
        $add->isbill = $isbill;
        $add->billtype = $billtype;
        $add->comment = $comment;
        $add->discount = $youhuis;
        $add->ypayment = $pays;
        $add->cusmsg = $cusmsg;
        $add->customerid = $khid;
        $add->type = $type;
        try {
            $pdata = $add->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
    //拆分订单修改原订单
    public function chaiedit($id,$ci,$syouhuis,$spays)
    {
        $add = new Orderinfo($id);
        $add->splittimes = $ci;
        $add->discount = $syouhuis;
        $add->ypayment = $spays;
        try {
            $pdata = $add->update();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }
     //合并订单修改选中订单
    public function heedit($id,$syouhuis,$spays)
    {
        $add = new Orderinfo($id);
        $add->discount = $syouhuis;
        $add->ypayment = $spays;
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