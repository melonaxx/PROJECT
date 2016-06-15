<?php

class CheckSignSvc 
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

    public static function ins($logger =null)
    {
        if (self::$_ins== null) {
            $cls        = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }

    /**
     * @brief  验证签名启用传感器
     *
     * @param  [array] $sensor [传感器信息]
     *
     * @return [type]          [description]
     */
    public function checkSign($sensorid, $sign, $data)
    {
        $sensorkey = SensorkeySvc::ins()->getSensorKeyBySensorId($sensorid);
        $new_sign = md5($data .":". $sensorkey['signkey']);

        try {      
            if (strcasecmp($new_sign, $sign) != 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when checkSign[$sensorid]：".$ex->getMessage());
            return false;
        }
    }

}
