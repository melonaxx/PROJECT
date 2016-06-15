<?php

/*
|----------------------------------------------------------
| 激活传感器 Active Sensor Action
|----------------------------------------------------------
|
| 这个Action主要负责激活传感器,成功激活后下发系统默认
| 的配置给传感器，传感器就正式启用传送数据。
|
*/
class Action_active_sensor extends XPostAction
{
      public function _run($request, $xcontext)
    {
        $imei     = $request->imei;
        $imsi     = $request->imsi;
        $sysver   = intval($request->sysver);

        // 尝试激活传感器
        $result = SensorSvc::ins()->activateSensor($imei, $imsi);
        list($retcode, $sensorkeyOrMsg) = $result;

        if ($retcode !== 0) {  
            echo ResultSet::jfail($retcode, $sensorkeyOrMsg);
            return XNext::nothing();
        }

        // 获得默认的传感器配置
        $new_conf = SensorConfSvc::ins()->getDefaultSensorConf();
        if (!$new_conf) {
            echo ResultSet::jfail(500, "Server Error");
            return XNext::nothing();
        }
        $conf = $this->convert2Client($new_conf, $sensorkeyOrMsg['signkey']);

        echo ResultSet::jsuccess($conf);
        return XNext::nothing();
    }

    /**
     * @brief 激活成功下发的默认配置信息
     *
     * @param $new_conf 默认的传感器配置信息
     *
     * @return 传感器配置信息
     */
    private function convert2Client($new_conf, $signkey) 
    {
        $conf           = array();
        $conf['v']      = intval($new_conf['version']);
        $conf['st']     = "md5";
        $conf['sk']     = $signkey;
        $conf['cf']     = intval($new_conf['collectfreq']);
        $conf['f']      = intval($new_conf['freq']);
        $conf['wi']     = intval($new_conf['wi']);
        $conf['wf']     = intval($new_conf['wf']);	
        $conf['svtime'] = time();

        return $conf;
    }
}

/*
|----------------------------------------------------------
| 重置传感器 Close Sensor Action
|----------------------------------------------------------
|
| 这个Action主要负责重置传感器，使传感器恢复到初始化状态
| 即重新从激活流程开始走。
| 
*/
class Action_close_sensor extends XAction
{
    public function _run($request, $xcontext)
    {
        $imei   = $request->imei;
        $sensor = SensorSvc::ins()->getSensorByImei($imei); // 根据IMEI号获取传感器的信息
        if (!$sensor) {
            echo ResultSet::jfail(404, "Sensor Not Found");
            return XNext::nothing();
        }

        $new_sensor = SensorSvc::ins()->closeSensor($sensor['id']); // 重置传感器
        if (!$new_sensor) {
            echo ResultSet::jfail(500, "Server Error");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess(true);
        return XNext::nothing();
     }
}
