<?php
/**
 * 订单中售后信息
 */
class ASaleProductSvc
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

	//添加订单售后退货明细 asaleproduct
    public function addasaleproduct($asaleid ,$productid ,$total ,$price)
    {
        $saleres = new ASaleProduct();
		$saleres->asaleid    =$asaleid;
		$saleres->productid  =$productid;
		$saleres->total      =$total;
		$saleres->price      =$price;

		//操作人
        $cookuid          = $_COOKIE['U'];
        $uidarr           = explode('=',$cookuid);
        $saleres->staffid =$uidarr['2'];
        try {
            $flag = $saleres->insert();
            return $flag;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addasaleproduct".$ex->getMessage());
            return false;
        }
    }

}