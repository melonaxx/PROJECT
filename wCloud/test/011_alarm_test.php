<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class AlarmTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_alarm()
    {
        $imei       = DataGenter::$IMEI;

        $result = $this->client->post("/alarm.php", array(
            "imei" => $imei
        ));

        $this->assertTrue($result->errno === 0);
    }
}
