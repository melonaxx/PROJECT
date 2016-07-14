<?php

class PrintBillSvc
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

    /*进行印刷方式的添加*/
    public function addprintbill($printmethodid ,$printunitid ,$content ,$vnumber ,$pnumber ,$frequency ,$position ,$orderid ,$stylename ,$loadaddress ,$tpsetstatus ,$verifystatus ,$printcost ,$comment,$staffid)
    {
        $pres = new PrintBill();
        $pres->printmethodid = $printmethodid;
        $pres->printunitid   = $printunitid;
        $pres->content       = $content;
        $pres->vnumber       = $vnumber;
        $pres->pnumber       = $pnumber;
        $pres->frequency     = $frequency;
        $pres->position      = $position;
        $pres->orderid       = $orderid;
        $pres->stylename     = $stylename;
        $pres->loadaddress   = $loadaddress;
        $pres->tpsetstatus   = $tpsetstatus;
        $pres->verifystatus  = $verifystatus;
        $pres->printcost     = $printcost;
        $pres->comment       = $comment;
        $pres->staffid       = $staffid;

        try {
            $pres->insert();
            return $pres->getKeyQuery();
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addprintbill".$ex->getMessage());
            return false;
        }
    }

    /*通过印刷方式ID获取一条印刷方式信息*/
    public function getprintbillbyid($id)
    {
        $pbill     = new PrintBill();
        $pbill->id = $id;

        try {
            $pdata = $pbill->get();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when getprintbillbyid".$ex->getMessage());
            return false;
        }
    }

    // /*进行印刷单位的修改*/
    // public function editprintmethod($id ,$name ,$printunitid ,$type ,$price ,$comment)
    // {
    //     $pmethodres = new PrintMethod($id);
    //     $pmethodres->name        = $name;
    //     $pmethodres->printunitid = $printunitid;
    //     $pmethodres->type        = $type;
    //     $pmethodres->price       = $price;
    //     $pmethodres->comment     = $comment;

    //     try {
    //         $pmethodres->update();
    //         return $pmethodres;
    //     } catch (Exception $ex) {
    //         Debug::watch(__FILE__,__LINE__,$ex,'$ex');
    //         $this->logger->error("exception occurs when editprintmethod".$ex->getMessage());
    //         return false;
    //     }
    // }

}