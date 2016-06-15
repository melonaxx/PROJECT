<?php
require_once("init.php");
require_once("/home/q/php/hydra_sdk/hydra.php");

$conf = new  HydraConf;
$conf->host = '192.168.1.40:2181';
$conf->topic = 'test1';
$conf->subscriber = 'dy';

function encode($msg) {
    $t = time();
    $r = base64_encode($msg);
    var_dump($msg);
    if ($t % 10 == 0)
        return false;
    else
        return true;
}

$logger = XLogKit::logger('hydra');

$hydraSvc = new HydraSvc($conf, $logger);

$hydraSvc->serv(encode, false);

