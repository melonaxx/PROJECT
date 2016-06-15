<?php

class RegisterDevSvc 
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

    /**
     * @brief 电车-传感器建立关联事务关系
     *
     * @param [type] $imei  [传感器IMEI号]
     * @param [type] $mobel [电车型号]
     * @param [type] $seqno [电车序列号]
     *
     * @return
     */
    public function RegisterDev($imei, $mobel, $seqno, $brand, $remarks, $companyid=0)
    {
        $writer = XDao::dwriter("DWriter");
        $writer->beginTrans();

        try {
			$sensor = SensorSvc::ins()->getSensorByImei($imei);
			if (!$sensor) {
				$writer->rollback();
				return;
			}

	 		$ebike = EbikeSvc::ins()->addEbike($mobel, $seqno, $brand, $remarks);
			if (!$ebike) {
				$writer->rollback();
				return;
			} 

			$new_link = LinkSvc::ins()->addLink($sensor['id'], $ebike['id']);
			if (!$new_link) {
				$writer->rollback();
				return;
			}

            if ($companyid) {
                $result = CBlinkSvc::ins()->activateEbike($companyid, $ebike['id']); 
                if (!$result) {
                    $writer->rollback();
                    return;
                }
            }

			$writer->commit();
			
			return $ebike;
		} catch (Exception $ex) {
			$writer->rollback();
			$this->logger->error("exception occurs when registerdev[$imei]：".$ex->getMessage());
			return false;
		}
    }

}
