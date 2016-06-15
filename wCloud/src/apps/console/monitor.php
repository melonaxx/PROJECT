<?php

require_once("init.php");

$interval = $_SERVER['INTERVAL'];

while(true) {
    
    $time  = date("Y-m-d H:i:s", time()-600);
    $ebike = XDao::query("EbikeQuery")->getLostEbike($time);
    $excep['exp'] = Ebike::EXCEPTION_LOST; 
    if ($ebike) {
        foreach($ebike as $v_id) {
            $sensorid = LinkSvc::ins()->getSensorIdByEbikeId($v_id);

            $result = EbikeSvc::ins()->updateExceptionByEbikeId($v_id, $excep);

            $ecs    = EbikeJourneySvc::ins()->addExceptionLog($sensorid, time(), $excep);       
        }
    }

    sleep($interval);
}
