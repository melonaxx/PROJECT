<?php

pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");
pylon_include_sdk("/home/q/php/woreo_sdk", "woreo_sdk.php");
pylon_include_sdk("/home/q/php/wcloud_sdk", "wcloud_sdk.php");

class GClientAltar
{
    const DOMAIN = "apilocate.amap.com";
    const PORT   = 80;

    public static function getSessionRedisClient()
    {
        $logger = new logger("data.redis");
        $conf   = new GRedisConf($_SERVER['REDIS_HOST'], $logger);
        $client = new GRedisClient($conf);

        return $client;
    }

    public static function checkNote($caller="wCloud")
    {
        $logger = new logger("data.note");
        $conf   = new GHttpConf();
        $conf->appConf($_SERVER['WOREO_API_SVC'], $logger, null, $caller);
        $conf->port = 8060;

        return new WOreoClient($conf);
    }

    public static function getGaoDeClient($caller="wCloud")
    {
        $logger = new logger("gaode.data");
        $conf   = new GHttpConf();
        $conf->appConf(self::DOMAIN, $logger, null, $caller);
        $conf->port = self::PORT;

        return new BaseStationClient($conf);
    }

}



