<?php

pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");

class GClientAltar
{
    /** 
     * @brief  获取的Redis client。这个redis库主要用来存储用户的session以及验证码
     * 
     * @return 
     */
    public static function getSessionRedisClient()
    {
        $logger = new logger("data.redis");
        $conf = new GRedisConf($_SERVER['REDIS_HOST'], $logger);
        $client = new GRedisClient($conf);

        return $client;
    }

}
