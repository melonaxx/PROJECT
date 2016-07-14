<?php
/**
 * 订单的发货方式
 */
class OrderDeliverSvc
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

    public function add($orderid,$type,$transportid,$waybill,$realweight,$freight)
    {
        $add = new OrderDeliver();
        $add->orderid = $orderid;
        $add->type = $type;
        $add->transportid = $transportid;
        $add->waybill = $waybill;
        $add->realweight = $realweight;
        $add->freight = $freight;
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when getProductquality".$ex->getMessage());
            return false;
        }
    }

}
