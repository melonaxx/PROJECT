<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class SensorActivateTC extends PHPUnit_Framework_TestCase 
{
    
    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_activate_sensor()
    {
        $imei     = DataGenter::$IMEI;
        $mobileno = DataGenter::getMobileno();
        $imsi     = DataGenter::getIMSI();

        $result = $this->client->post("/active_sensor.php", array(
            "imei"     => $imei,
            "mobileno" => $mobileno,
            "imsi"     => $imsi
        ));

        $this->assertTrue($result->errno === 0);
        DataGenter::$CONF = $result->data;
    }

}
