<?php
require_once("init.php");
require_once("SSDB.php");
require_once("/home/q/php/hydra_sdk/hydra.php");

$conf = new  HydraConf;
$conf->host = '192.168.1.40:2181';
$conf->topic = 'test1';
$conf->subscriber = 'dy';
$ssdb = new SimpleSSDB('192.168.1.38', 8888);

function encode($msg) {
    $t = time();
    $r = $msg->getMessage();
    $r = explode(";", $r);
    $item = json_decode($r[0], true);
    $imei = $r[1];
    $sensor  = SensorSvc::ins()->getSensorByImei($imei);   
    $ebikeid = LinkSvc::ins()->getEbikeIdBySensorId($sensor['id']);     
    foreach ($item as $key => $v) {
        if ($v['c'] == 1) {
            $item = json_encode();


        } else {
            $lac  = explode("|", $v['lac']);                                                                 
            $cell = explode("|", $v['cell']);                                                                
            $q    = explode("|", $v['q']);
            $bts  = $v['mcc'] . "," . $v['mnc'] . "," . $lac[0] . "," . $cell[0] . "," . $q[0];          
            $nearbts = array();
            foreach ($lac as $key1 => $v1) {                                                                       
                foreach ($cell as $key2 => $v2) {                                                                
                    foreach ($q as $key3 => $v3) {                                                               
                        if ($key1 == $key2 && $key2 == $key3 && $key1 != 0) {                                      
                            $nearbts[] = $v['mcc'] . "," . $v['mnc'] . "," . $v1 . "," . $v2 . "," . $v3; 
                        }   
                    }   
                }   
            }   

            $nearbts     = implode("|", $nearbts);                                                                 
            $client      = GClientAltar::getGaoDeClient();                                                         
            $result      = $client->getLocation($imei, $bts, $nearbts);                                            
            $data        = json_decode($result[1], true);
            $location    = $data['result']['location'];
            $location    = explode(",", $location);
            $latitude    = $location[1];
            $longitude   = $location[0];
            $batpercent  = $v['b'];
            $voltage     = $v['u'];
            $electricity = $v['a'];
            $new_ebikestate = EbikeStateSvc::ins()->addEbikeState($sensor['id'], $ebikeid, $latitude, $longitude, $batpercent, $voltage, $electricity);
            if (!$new_ebikestate) {
                echo ResultSet::jfail(500, "Server Error");
                return XNext::nothing();
            }
        }
    }
    if ($t % 10 == 0)
        return false;
    else
        return true;
}

$logger = XLogKit::logger('hydra');

$hydraSvc = new HydraSvc($conf, $logger);

$hydraSvc->serv(encode, false);

