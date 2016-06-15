<?php 
require_once("init.php");

$geohash = new Geohash;
echo "hello,boy!\n";
set_time_limit(0);
$inteval = (5);
while (true) {
    $companyid = 24102;
    $ebike = XDao::query("CBlinkQuery")->getEbike($companyid);
    foreach ($ebike as $value) {
        $ebikeid = $value['ebikeid'];

        $latitude = mt_rand(36900000, 40100000)/1000000;
        $longitude = mt_rand(116246502, 116400000)/1000000;
        $state = EbikeStateSvc::ins()->getEbikeStateByEbikeId($ebikeid);
        if (!$state) {
            $la = $latitude;
            $lo = $longitude;
        } else {
            $la = $state['latitude'];
            $lo = $state['longitude'];
            if ($la > 39.977523 && $lo < 116.337795) {
                $la -= 0.000003;
                $lo += 0.0003;
            } else {
                $la += 0.000001;
                $lo -= 0.0001;
            } 
        }

        $ebikestate  = EbikeStateSvc::ins()->getEbikeStateByEbikeId($ebikeid);
        if ($ebikestate) {
            $lastgeohash = $geohash->encode($ebikestate['latitude'], $ebikestate['longitude']);
        } else {
            $lastgeohash = 0;
        } 
        $computed_hash = $geohash->encode($la, $lo);
        $sensorid   = $ebikeid; 
        $batpercent = mt_rand(0,100);
        $ebikestate = new EbikeState($sensorid); 
        $ebikestate->ebikeid    = $ebikeid;
        $ebikestate->latitude   = $la;
        $ebikestate->longitude  = $lo;
        $ebikestate->batpercent = $batpercent;
        $ebikestate->geohash    = $computed_hash;
        $ebikestate->lastgeohash = $lastgeohash;
        $ebikestate->indate();
    }

    sleep($inteval);
}
    


