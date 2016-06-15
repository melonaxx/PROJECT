<?php

class SensorSvc 
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
     * @brief 传感器注册
     *
     * @param [varchar] $imei  [传感器IMEI号]
     * @param string $mobileno [匹配手机号]
     *
     * @return
     */
    public function addSensor($imei, $mobileno="")
    {

        $sensor           = new Sensor();
        $sensor->imei     = $imei;
        $sensor->mobileno = $mobileno;

        try {
            $sensor->insert();
            return $sensor;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when addSensor[$imei]：".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 获取当前的传感器信息
     * 
     * @param 传感器的IMEI号
     *
     * @return 对应IMEI号当前传感器信息
     */
    public function getSensorByImei($imei)
    {
        $sensor = new Sensor();
        $sensor->get(array("imei" => $imei));

        if ($sensor->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getSensorByImei, imei: $imei");
            return null;
        }

        return $sensor;
    }

    public function getSensorById($id)
    {
        $sensor = new Sensor($id);
        $sensor->get();

        if ($sensor->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getSensorById, id: $id");
            return null;
        }

        return $sensor;
    }

    /** 
     * @brief 激活传感器。使用事务
     * 
     * @param $imei
     * @param $mobileno 
     * @param $imsi 
     * 
     * @return 
     */
    public function activateSensor($imei, $imsi)
    {
        $trans = new Transaction();

        // 开启事务
        if ($trans->begin()) {
            try {
                // 首先获取sensor的状态
                $sensor = XDao::dwriter("SensorWriter")->getSensorByIMEIForUpdate($imei);
                if (!$sensor) {
                    $trans->rollback();
                    return array(404, "Sensor Not Found");
                }

                // 已被激活过了，不能重复激活
                if ($sensor['flag'] == Sensor::FLAG_ACTIVED) {
                    $trans->rollback();
                    return array(406, "Sensor Actived");
                }

                $sensorid = $sensor['id'];

                $this->setSensorActivating($sensorid, $imsi);

                // 生成签名的密钥
                $sensorkey = SensorkeySvc::ins()->indateSignKey($sensorid, $imei, $imsi);

                $trans->commit();

                return array(0, $sensorkey);
            } catch (Exception $ex) {
                $trans->rollback();
                $this->logger->error("exception occurs when activateSensor[$sensorid,$imsi]：".$ex->getMessage());
                return array(-1, "Server Error");
            }
        } else {
            return array(-1, "Server Error");
        }
    }

    /** 
     * @brief 将传感器的状态置为 激活中
     * 
     * @param $sensorid 
     * @param $mobileno 
     * @param $imsi 
     * 
     * @return 
     */
    public function setSensorActivating($sensorid, $imsi)
    {
        $sensor           = new Sensor($sensorid);
        $sensor->imsi     = $imsi;
        $sensor->flag     = Sensor::FLAG_ACTIVATING;

        $sensor->update();

        return $sensor;
    }

    /**
     * @brief  启用传感器，数据可以传输
     *
     * @param  [int] $sensorid  [传感器id]
     *
     * @return [type]           [description]
     */
    public function enableSensor($sensorid)
    {
        $sensor = new Sensor($sensorid);
        $sensor->flag = Sensor::FLAG_ACTIVED;

        try {
            $sensor->update();
            return true;            
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when enableSensor[$sensorid]：".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 更新手机号
     *
     * @param $sensorid 传感器ID
     * @param $mobile 手机号
     * @param $imsi IMSI
     *
     * @return 更新成功返回 true, 失败返回 false
     */
    public function updateMobileNo($sensorid, $imsi)
    {
        $sensor = new Sensor($sensorid);

        $sensor->imsi = $imsi ? $imsi : 0;

        try {
            $sensor->update();
            return true;            
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateMobileNo[$sensorid]：".$ex->getMessage());
            return false;
        }
    }

    public function updateSensorNextVer($imei, $nextver)
    {
        $sensor = new Sensor();
        $sensor->nextver = $nextver;
        $result = $sensor->update(array("imei" => $imei));

        if ($sensor->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return 0;
        }

        return $result;
    }

    /** 
     * @brief 根据sensorid关闭传感器
     * 
     * @param $sensorid 
     * 
     * @return 成功关闭返回 true , 失败返回 false 
     */
    public function closeSensor($sensorid)
    {
        $sensor = new Sensor($sensorid);
        $sensor->flag = 0;

        try {
            $sensor->update();
            return true;            
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when closeSensor[$sensorid]：".$ex->getMessage());
            return false;
        }
    }
}


