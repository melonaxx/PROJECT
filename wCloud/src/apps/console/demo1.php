<?php 
require_once("init.php");


set_time_limit(0);
$inteval = (1);
while (true) {
    $mobel        = "36V,12A";
    $seqno        = time() . rand(100000, 999999);
    $ebike        = new Ebike();
    $ebike->mobel = $mobel;
    $ebike->seqno = $seqno;
    $ebike->insert();
    
    $imei = time() . rand(100000,999999);
//    $prefix = array(138, 155, 158, 137, 132);
//    $i = rand(0, 4);
//    $suffix = rand(10000000, 99999999);
//    $mobileno = $prefix[$i] . $suffix;
    $sensor           = new Sensor();
    $sensor->imei     = $imei;
//    $sensor->mobileno = $mobileno;
    $sensor->insert();

    $link = new Link();
    $link->ebikeid = $ebike['id'];
    $link->sensorid = $sensor['id'];
    $link->insert();

    sleep($inteval);
}
    

