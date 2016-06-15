<?php

require_once("init.php");

$interval = $_SERVER['DELAY_TIME'];

while(true) {
    $ebike = XDao::query("EbikeStateQuery")->getRestEbike(); // 获取所有休息的车
    foreach ($ebike as $v_sen) {
        $version = time() - strtotime("2016-01-01"); // 配置的版本号使用当前时间至2016-01-01的秒数

        $sensorconf = SensorConfSvc::ins()->getSensorConfBySensorId($v_sen['sensorid']);
        if ($sensorconf) {
            // 传感器有配置，就在自己配置的基础上延迟频率
            $f = $sensorconf['freq'] <= 60 ? $sensorconf['freq'] + 10 : $sensorconf['freq']; 
            $result = SensorConfSvc::ins()->updateSingleSensorConf($version, $v_sen['sensorid'], 
                $cf, $f, $wi, $wf);
            
            if (!$result) {
                echo ResultSet::jfail(500, "Server Error：updateSingleSensorConf Fail");
                return XNext::nothing();
            }
        } else {
            // 传感器之前没有配置，就在默认配置基础上来延长频率
            $conf = SensorConfSvc::ins()->getDefaultSensorConf();

            $collectferq = $cf ? $cf : $conf['collectfreq'];
            $freq = $conf['freq'] <=60 ? $conf['freq'] + 10 : $conf['freq'];
            $wi   = $wi ? $wi : $conf['wi'];
            $wf   = $wf ? $wf : $conf['wf'];

            $result = SensorConfSvc::ins()->addSensorConf($version, $v_sen['sensorid'], 
                $collectferq, $freq,  $wi, $wf);

            if (!$result) {
                echo ResultSet::jfail(500, "Server Error：addSensorConf Fail");
                return XNext::nothing();
            }
        }
    }

    sleep($interval);
}
