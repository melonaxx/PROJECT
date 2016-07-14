<?php

class processfactorySvc
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


    //添加代工户
    public function add_oem($oem_info)
    {
        $oem = new processfactory();
        $oem['number']=htmlspecialchars($oem_info["number"]);
        $oem['name']=htmlspecialchars($oem_info["name"]);
        $oem['level']=htmlspecialchars($oem_info["level"]);

            if ($oem['level'] != "P" && $oem['level'] != "A" && $oem['level'] != "E") {
                   $oem['level'] = "P";
            }

        $oem['balance']=htmlspecialchars($oem_info["balance"]);
        $oem['tax']=htmlspecialchars($oem_info["tax"]);
        $oem['bankname']=htmlspecialchars($oem_info["bankname"]);
        $oem['banknumber']=htmlspecialchars($oem_info["banknumber"]);
        $oem['website']=htmlspecialchars($oem_info["website"]);
        $oem['contactname']=htmlspecialchars($oem_info["contactname"]);
        $oem['mobile']=htmlspecialchars($oem_info["mobile"]);
        $oem['phone']=htmlspecialchars($oem_info["phone"]);
        $oem['mailbox']=htmlspecialchars($oem_info["mailbox"]);
        $oem['fax']=htmlspecialchars($oem_info["fax"]);
        $oem['postcode']=htmlspecialchars($oem_info["postcode"]);
        $oem['stateid']=intval($oem_info["stateid"]);
        $oem['cityid']=intval($oem_info["cityid"]);
        $oem['districtid']=intval($oem_info["districtid"]);
        $oem['address']=htmlspecialchars($oem_info["address"]);
        $oem['comment']=htmlspecialchars($oem_info["comment"]);
        try {
            $oem->insert();
            return true;
        } catch (Exception $ex) {
             Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //删除代工户
    public function del_oem($oemid)
    {
        $oem = new processfactory($oemid);
        $oem['isdelete']='Y';
        try {
            $oem->update();
            return true;
        } catch (Exception $ex) {
             Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //修改代工户
    public function update_oem($oem_info)
    {
        $oem = new processfactory($oem_info['oemid']);
        $oem['number']=htmlspecialchars($oem_info["number"]);
        $oem['name']=htmlspecialchars($oem_info["name"]);
        $oem['level']=htmlspecialchars($oem_info["level"]);

            if ($oem['level'] != "P" && $oem['level'] != "A" && $oem['level'] != "E") {
                   $oem['level'] = "P";
            }

        $oem['balance']=htmlspecialchars($oem_info["balance"]);
        $oem['tax']=htmlspecialchars($oem_info["tax"]);
        $oem['bankname']=htmlspecialchars($oem_info["bankname"]);
        $oem['banknumber']=htmlspecialchars($oem_info["banknumber"]);
        $oem['website']=htmlspecialchars($oem_info["website"]);
        $oem['contactname']=htmlspecialchars($oem_info["contactname"]);
        $oem['mobile']=htmlspecialchars($oem_info["mobile"]);
        $oem['phone']=htmlspecialchars($oem_info["phone"]);
        $oem['mailbox']=htmlspecialchars($oem_info["mailbox"]);
        $oem['fax']=htmlspecialchars($oem_info["fax"]);
        $oem['postcode']=htmlspecialchars($oem_info["postcode"]);
        $oem['stateid']=intval($oem_info["stateid"]);
        $oem['cityid']=intval($oem_info["cityid"]);
        $oem['districtid']=intval($oem_info["districtid"]);
        $oem['address']=htmlspecialchars($oem_info["address"]);
        $oem['comment']=htmlspecialchars($oem_info["comment"]);
        try {
            $oem->update();
            return true;
        } catch (Exception $ex) {
             Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
}

//生产单
class ManufactorySvc
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


    //添加生产单
    public function add_pro_order($pro_order_info,$uid){

       $manufactory = new Manufactory();
       //仓库
       $manufactory['storeid'] = intval($pro_order_info['storehouse']);
       //生产单摘要
       $manufactory['brief'] = htmlspecialchars($pro_order_info['pro_order']);
       //商品id
       $manufactory['productid'] = intval($pro_order_info['goods_info']);
       //生产数量
       $manufactory['total'] = htmlspecialchars($pro_order_info['number']);
       //生产单备注
       $manufactory['comment'] = htmlspecialchars($pro_order_info['remarks']);
       //生产单生成时间
       $manufactory['actiondate'] = date("Y-m-d h:i:s",time());
       //操作人
       $manufactory['staffid'] = $uid;
       //生产单编号
       $manufactory['number'] = time();
       try {
            $manufactory->insert();
            return true;
        } catch (Exception $ex) {
             Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //修改生产单
    public function change_pro_order($pro_order_info,$uid){

       $manufactory = new Manufactory($pro_order_info['id']);
       //仓库
       $manufactory['storeid'] = intval($pro_order_info['storehouse']);
       //生产单摘要
       $manufactory['brief'] = htmlspecialchars($pro_order_info['pro_order']);
       //商品id
       $manufactory['productid'] = intval($pro_order_info['goods_info']);
       //生产数量
       $manufactory['total'] = htmlspecialchars($pro_order_info['number']);
       //生产单备注
       $manufactory['comment'] = htmlspecialchars($pro_order_info['remarks']);
       //生产单生成时间
       // $manufactory['actiondate'] = date("Y-m-d h:i:s",time());
       //操作人
       $manufactory['staffid'] = $uid;
       //生产单编号
       // $manufactory['number'] = time();
       
       $manufactory['statusaudit'] = 'N';
       try {
            $manufactory->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    //打回修改
    public function to_change($oid,$status)
    {
        $manufactory = new Manufactory($oid);

        $manufactory['statusaudit'] = $status;

        if($status == "Y"){
            $manufactory['totalway'] = $manufactory['total'];
        }
        try {
            $manufactory->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    //已生产
    public function to_product($pro_id){

        $manufactory = new Manufactory($pro_id);

        $manufactory['prostatus'] = 'Y';

        try {
            $manufactory->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    //部分入库或者全部入库
    public function change_stasreceipt($status,$pro_id){

        $manufactory = new Manufactory($pro_id);

        $manufactory['statusreceipt'] = $status;

        try {
            $manufactory->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    //部分返工或者全部返工
    public function change_statusrefund($status,$pro_id){

        $manufactory = new Manufactory($pro_id);

        $manufactory['statusrefund'] = $status;

        try {
            $manufactory->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}


//生产单
class ProcessmanufactorySvc
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

    public function addProcess($pro_id,$oemid,$storeid,$number,$remarks){

        $processmanufactory = new Processmanufactory();

        $processmanufactory['productinfoid'] = intval($pro_id);//生产单id

        $processmanufactory['profactoryid'] = intval($oemid);//代工户id

        $processmanufactory['storeid'] = intval($storeid);//仓库id

        $processmanufactory['totalway'] = intval($number);//在途数量

        $processmanufactory['total'] = intval($number);//生产数量

        $processmanufactory['comment'] = htmlspecialchars($remarks);//备注
        
        try {
            $processmanufactory->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}

/*
生产 - 代工户商品出入库单据
 */
class FprobillSvc
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

    public function insert_store_data($storeid,$pro_order_id,$productid,$to_storecount,$uid,$status){
        
        $fprobill = new Fprobill();

        $fprobill['storeid']=intval($storeid);

        $fprobill['productinfoid']=intval($pro_order_id);

        $fprobill['productid']=intval($productid);

        $fprobill['total']=intval($to_storecount);

        $fprobill['userid']=intval($uid);

        $fprobill['storetype']=$status;

        $fprobill['actiontime']=date("Y-m-d h:i:s");

        
        try {
            $fprobill->insert();
            return $fprobill->id;
        } catch (Exception $ex) {
            return false;
        }
    }

}

/*
生产 - 代工户商品出入库明细
 */
class FprochangeSvc
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

    public function insert_store_data($infoid,$proid,$productid,$to_storenum,$remart){
        
        $fprochange = new Fprochange();

        $fprochange['infoid']=intval($infoid);

        $fprochange['profactoryid']=intval($proid);

        $fprochange['productid']=intval($productid);

        $fprochange['total']=intval($to_storenum);

        $fprochange['comment']=$remart;

        
        try {
            $fprochange->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}

// 生产 - 手动调拨原料
class AllocaterawSvc
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
    
    public function insertraw($productid,$moveoutid,$profactoryid,$staffid,$total,$comment){

        $allocateraw = new Allocateraw();
        $allocateraw['productid']=intval($productid);
        $allocateraw['moveoutid']=intval($moveoutid);
        $allocateraw['profactoryid']=intval($profactoryid);
        $allocateraw['staffid']=intval($staffid);
        $allocateraw['total']=intval($total);
        $allocateraw['comment']=htmlspecialchars($comment);

        try {
            $allocateraw->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}

// 代工库实时原料明细数量
class FstoresyncSvc
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
    
    public function insertraw($productid,$profactoryid,$total){

        $Fstoresync = new Fstoresync();

        $Fstoresync['productid']=intval($productid);
        $Fstoresync['profactoryid']=intval($profactoryid);
        $Fstoresync['total']=intval($total);


        try {
            $Fstoresync->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}

// 代工库手动减库记录-
class FstoredesSvc
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

    public function insertlog($productid,$profactoryid,$staffid,$total,$comment){

        $Fstoredes = new Fstoredes();

        $Fstoredes['productid']=intval($productid);
        $Fstoredes['profactoryid']=intval($profactoryid);
        $Fstoredes['staffid']=intval($staffid);
        $Fstoredes['total']=intval($total);
        $Fstoredes['comment']=htmlspecialchars($comment);
        $Fstoredes['actiontime']=date("Y-m-d h:i:s",time());
        
        try {
            $Fstoredes->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}


// 生产 - 代工户结算记录-
class FprosettleSvc
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

    public function insert_settle_log($pro_order_num,$account,$oemid,$req_tax,$tax_rate,$tax_brow,$noreq_tax,$comment,$uid)
    {
        
        $fprosettle = new Fprosettle();

        $fprosettle['productinfoid'] = htmlspecialchars($pro_order_num);
        $fprosettle['bankid']        = intval($account);
        $fprosettle['profactoryid']  = intval($oemid);
        $fprosettle['taxprice']      = htmlspecialchars($req_tax);
        $fprosettle['taxrate']       = htmlspecialchars($tax_rate);
        $fprosettle['taxtotal']      = htmlspecialchars($tax_brow);
        $fprosettle['taxedprice']    = htmlspecialchars($noreq_tax);
        $fprosettle['comment']       = htmlspecialchars($comment);
        $fprosettle['staffid']       = intval($uid);
        $fprosettle['actiontime']    = date("Y-m-d h:i:s",time());

        try {
            $fprosettle->insert();
            return $fprosettle->id;
        } catch (Exception $ex) {
            return false;
        }
    }
}
// 生产公用关联财务科目表
class FprofinanceSvc
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

    public function insert_link_log($type,$infoid,$faccountid,$direction,$price)
    {
        
        $Fprofinance = new Fprofinance();
        $Fprofinance['type']       = $type;
        $Fprofinance['infoid']     = intval($infoid);
        $Fprofinance['faccountid'] = intval($faccountid);
        $Fprofinance['direction']  = $direction;
        $Fprofinance['price']      = $price;

        try {
            $Fprofinance->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}

// 生产 - 日常开票记录编号-
class MakinvoicelogSvc
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
    //日常开票
    public function addinvoice_log($stamptype,$productinfoid,$taxprice,$taxrate,$taxtotal,$taxedprice,$staffid,$comment,$actiontime)
    {     
        $makinvoicelog = new Makinvoicelog();
        $makinvoicelog['stamptype']     = $stamptype;
        $makinvoicelog['productinfoid'] = htmlspecialchars($productinfoid);
        $makinvoicelog['taxprice']      = htmlspecialchars($taxprice);
        $makinvoicelog['taxrate']       = htmlspecialchars($taxrate);
        $makinvoicelog['taxtotal']      = htmlspecialchars($taxtotal);
        $makinvoicelog['taxedprice']    = htmlspecialchars($taxedprice);
        $makinvoicelog['staffid']       = intval($staffid);
        $makinvoicelog['comment']       = htmlspecialchars($comment);
        $makinvoicelog['actiontime']    = htmlspecialchars($actiontime);

        try {
            $makinvoicelog->insert();
            return $makinvoicelog->id;
        } catch (Exception $ex) {
            return false;
        }
    }

    // 补交税点
    public function insertinvoice_log($stamptype,$productinfoid,$taxprice,$taxrate,$taxtotal,$taxedprice,$staffid,$comment,$actiontime,$account)
    {     
        $makinvoicelog = new Makinvoicelog();
        $makinvoicelog['stamptype']     = $stamptype;
        $makinvoicelog['productinfoid'] = htmlspecialchars($productinfoid);
        $makinvoicelog['taxprice']      = htmlspecialchars($taxprice);
        $makinvoicelog['taxrate']       = htmlspecialchars($taxrate);
        $makinvoicelog['taxtotal']      = htmlspecialchars($taxtotal);
        $makinvoicelog['taxedprice']    = htmlspecialchars($taxedprice);
        $makinvoicelog['staffid']       = intval($staffid);
        $makinvoicelog['comment']       = htmlspecialchars($comment);
        $makinvoicelog['actiontime']    = htmlspecialchars($actiontime);
        $makinvoicelog['bankid']        = intval($account);

        try {
            $makinvoicelog->insert();
            return $makinvoicelog->id;
        } catch (Exception $ex) {
            return false;
        }
    }
}