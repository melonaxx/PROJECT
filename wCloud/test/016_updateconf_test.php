<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class UpdateConfTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_update_sensorconf()
    {
        $freq            = 30;
        $wi              = 80;
        $wf              = 20;  
        $version         = 2;
        $data['f']       = $freq;
        $data['ver']     = $version;
        $data['wi']      = $wi;
        $data['wf']      = $wf;

        $result = $this->client->post("/update_sensorconf.php", array(
            "data" => $data,
        ));

        $this->assertTrue($result->errno === 0);
    }

}
