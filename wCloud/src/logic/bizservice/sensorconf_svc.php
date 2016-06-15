<?php

class SensorConfSvc 
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
     * @brief 添加新的配置信息
     *
     * @param [type] $ebikeid [description]
     * @param [type] $imei     [description]
     *
     * @return
     */
    public function addSensorConf($version, $sensorid, $collectferq, $freq, $wi, $wf)
    {
       $conf               = new SensorConf();
       $conf->sensorid     = $sensorid;
       $conf->version      = $version;
       $conf->collectfreq  = $collectferq;
       $conf->freq         = $freq;
       $conf->wi           = $wi;
       $conf->wf           = $wf;

       try {
           $conf->insert();
           return $conf;
       } catch (Exception $ex) {
           $this->logger->error("exception occurs when addConf[$ebikeid]：".$ex->getMessage());
           return false;
       }
    }

    /**
     * @brief   查询传感器配置信息
     *
     * @param  [type] $sensorid [description]
     *
     * @return
     */
    public function getSensorConfBySensorId($sensorid)
    {
        $conf = new SensorConf($sensorid);
        $is_delete = SensorConf::IS_DELETE_FALSE;

        $conf->get(array("is_delete" => $is_delete, "sensorid" => $sensorid));

        if ($conf->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return null;
        }

        return $conf;
    }

    /**
     * @brief  获取原始传感器配置信息
     *
     * @param  integer $id [传感器原始id;为1]
     *
     * @return 
     */
    public function getDefaultSensorConf()
    {
       $id   = 1111;
       $conf = new SensorConf($id);
       $is_delete = SensorConf::IS_DELETE_FALSE;

       $conf->get(array("is_delete" => $is_delete));

       if ($conf->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
           return null;
       }

       return $conf;
    }

    /**
     * @brief 更新默认传感器配置信息
     *
     * @param $version 传感器配置版本号
     * @param $updatetype 配置类型
     * @param $collectferq 数据收集频率
     * @param $freq 数据传输频率
     * @param $wi 报警的时间限制
     * @param $wf 报警传输的频率
     *
     * @return
     */
    public function updateSensorConf($version, $collectferq, $freq, $wi, $wf)
    {
        try {
            $conf = $this->getDefaultSensorConf();
            if (!$conf) {
               $result = $this->createDefaultSensorConf($version, $collectferq, $freq, $wi, $wf);
            } else {
               $result = $this->updateDefaultSensorConf($conf['id'], $version, $collectferq, $freq, $wi, $wf);
            }

            if (!$result) {
                return false;
            }

            return $result;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateSensorConf" . $ex->getMessage());
            return false;
        }
    } 

    /**
     * @brief 修改单个传感器配置信息，没有就添加
     *
     * @param $version 传感器配置版本号
     * @param $updatetype 配置类型
     * @param $sensorid 传感器id
     * @param $collectferq 数据收集频率
     * @param $freq 数据传输频率
     * @param $wi 报警的时间限制
     * @param $wf 报警传输的频率
     *
     * @return
     */
    public function updateSingleSensorConf($version, $sensorid, $collectferq, $freq, $wi, $wf)
    {
        $sensorconf = new SensorConf($sensorid);
        $sensorconf->version = $version;
        $updatetype = 0;
        if ($collectferq) {
            $sensorconf->collectfreq = $collectferq;
            $updatetype += SensorConf::FLAG_COLLECTFREQ;
        }

        if ($freq) {
            $sensorconf->freq = $freq;
            $updatetype += SensorConf::FLAG_FREQ;
        }

        if ($wi) {
            $sensorconf->wi = $wi;
            $updatetype += SensorConf::FLAG_WI;
        }

        if ($wf) {
            $sensorconf->wf = $wf;
            $updatetype += SensorConf::FLAG_WF;
        }

        $sensorconf->updatetype = $updatetype;

        try {
            $sensorconf->update();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateSingleSensorConf, sensorid: $sensorid" . $ex->getMessage());
            return false;
        }
    }

    /**
     * @brief 删除单个配置信息，恢复全局配置
     *
     * @param $sensorid 传感器id
     *
     * @return
     */
    public function removeSensorConf($sensorid)
    {
        $sensorconf = new SensorConf($sensorid);
        $sensorconf->is_delete = SensorConf::IS_DELETE_TRUE;

        try {
          $result = $sensorconf->update();
          return $result;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when removeSensorConf, sensorid: $sensorid" . $ex->getMessage());
            return null;
        }
    }

    public function createDefaultSensorConf($version, $collectferq, $freq, $wi, $wf)
    {
        $sensorconf = new SensorConf();
        $sensorconf->id          = 1111;
        $sensorconf->sensorid    = 0;
        $sensorconf->version     = $version;
        $sensorconf->collectfreq = $collectferq;
        $sensorconf->freq        = $freq;
        $sensorconf->wi          = $wi;
        $sensorconf->wf          = $wf;

        try {
            $sensorconf->insert();
            return $sensorconf;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when createDefaultSensorConf" . $ex->getMessage());
            return false;
        }
    }

    public function updateDefaultSensorConf($id, $version, $collectferq, $freq, $wi, $wf)
    {
        $sensorconf          = new SensorConf($id);
        $sensorconf->version = $version;
        $updatetype          = 0;
        if ($collectferq) {
            $sensorconf->collectfreq = $collectferq;
            $updatetype += SensorConf::FLAG_COLLECTFREQ;
        }

        if ($freq) {
            $sensorconf->freq        = $freq;
            $updatetype += SensorConf::FLAG_FREQ;
        }

        if ($wi) {
            $sensorconf->wi          = $wi;
            $updatetype += SensorConf::FLAG_WI;
        }

        if ($wf) {
            $sensorconf->wf          = $wf;
            $updatetype += SensorConf::FLAG_WF;
        }

        $sensorconf->updatetype = $updatetype;

        try {
            $sensorconf->update();
            return $sensorconf;
        } catch(Exception $ex) {
            $this->logger->error("exception occurs when updatesensorconf[$updatetype]：".$ex->getMessage());
            return false;
        }
    }
}
