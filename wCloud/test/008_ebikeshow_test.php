<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class EbikeShowTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_ebikeShow()
    {
        $userid   = 42;

        $result = $this->client->post("/ebike_show.php", array(
            "userid"   => $userid,
        ));

        $this->assertTrue($result->errno === 0);
    }
}
