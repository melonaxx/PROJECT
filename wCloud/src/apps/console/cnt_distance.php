<?php
require_once("init.php");
require_once("/home/q/php/hydra_sdk/hydra.php");

$conf = new  HydraConf;
$conf->host = $_SERVER['ZK_LIST'];
$conf->topic = $_SERVER['ZK_TOPIC'];
$conf->subscriber = $_SERVER['ZK_SUBSCRIBER'];

function encode($msg) {
    $t = time();
    $r = $msg->getMessage();
    $r = explode(";", $r);
    $imei = $r[1];
    $r = json_decode($r[0], true);

    $sensor  = SensorSvc::ins()->getSensorByImei($imei);   

    $jSvc = EbikeJourneySvc::ins(); 

    foreach($r as $item) {
        $k[date('Ymd')."-".$sensor['id']] = 1;
        if(is_array($item)) {
           $a = $jSvc->addJouneryPort($sensor['id'], $item['t'], $item);
        }
    }

    return true;
}


$logger = XLogKit::logger('hydra');

$hydraSvc = new HydraSvc($conf, $logger);

$hydraSvc->serv(encode, false);
