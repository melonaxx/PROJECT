<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class PerfectInformTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_perfect_inform()
    {
        $userid   = 20822;
        $name     = 'zhangsan';
        $data['name']    = $name;

        $result = $this->client->post("/perfect_inform.php", array(
            "userid"   => $userid,
            "data"     => $data
        ));

        $this->assertTrue($result->errno === 0);
    }

}
