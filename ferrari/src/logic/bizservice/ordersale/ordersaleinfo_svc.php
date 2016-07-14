<?php
/**
 * 订单中售后信息
 */
class OrderSaleInfoSvc
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

	//添加订单售后ordersaleinfo
    public function addasaleinfo($saletype ,$orderid ,$cateid ,$backbankid ,$backpay ,$contents ,$backexpress ,$number ,$freight ,$backfee)
    {
        $saleres = new OrderSaleInfo();
		$saleres->saletype    =$saletype;
		$saleres->orderid     =$orderid;
		$saleres->cateid      =$cateid;
		$saleres->backbankid  =$backbankid;
		$saleres->backpay     =$backpay;
		$saleres->contents    =$contents;
		$saleres->backexpress =$backexpress;
		$saleres->number      =$number;
		$saleres->freight     =$freight;
		$saleres->backfee     =$backfee;

		//操作人
		$cookuid          = $_COOKIE['U'];
		$uidarr           = explode('=',$cookuid);
		$saleres->staffid = $uidarr['2'];

        try {
            $saleres->insert();
            return $saleres->getKeyQuery();
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addasaleinfo".$ex->getMessage());
            return false;
        }
    }

}