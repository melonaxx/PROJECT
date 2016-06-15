<?php
	
class SensorkeySvc 
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
     * @brief 生成一个签名密钥
     * 
     * @param $sensorid 
     * @param $imei 
     * @param $ismi 
     * 
     * @return 
     */
    public function indateSignKey($sensorid, $imei, $ismi)
    {
        $sensorkey = new SensorKey();
        $sensorkey->sensorid = $sensorid;
        $sensorkey->signkey = md5($imei . $this->getRandomSuffix()) . md5($ismi . $this->getRandomSuffix());

        try {
            $sensorkey->indate();
            return $sensorkey;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addSignKey[$sensorid]：".$ex->getMessage());
            return false;
        }
    }

    private function getRandomSuffix()
    {
        return "@" . microtime() . "%" . rand();
    }

    /**
     * @brief  根据sensorid获取密钥
     *
     * @param  [int] $sensorid [传感器id]
     *
     * @return 
     */
    public function getSensorKeyBySensorId($sensorid)
    {
        $sensorkey = new SensorKey($sensorid);
        $sensorkey->get();

        if ($sensorkey->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return null;
        }

        return $sensorkey;
    }
}
