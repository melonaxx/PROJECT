<?php
require_once('init.php');

$filename = '/home/liulei/locate.txt';
$r = file($filename);

$jSvc = EbikeJourneySvc::ins(); 

foreach($r as $v) {
    $c = explode(";", $v);
    $imei = trim($c[1]);
    $c = json_decode($c[0], true);

    $sensor  = SensorSvc::ins()->getSensorByImei($imei);   
    $k[date('Ymd')."-".$sensor['id']] = 1;
    if(is_array($c)) {
        foreach($c as $item) {
            $a = $jSvc->addJouneryPort($sensor['id'],$item['t'],$item);
        }
    }
}

$ss = array("31553"=>1);
foreach($ss as $k=>$v) {
    $time = time()-1000;
    $site = $jSvc->getMySomeTimeJournet($k, time(), $time, time());
    var_dump($site);
}
