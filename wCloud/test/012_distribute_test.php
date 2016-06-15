<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class DistrbuteTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_distribute()
    {
        $ebikeid  = DataGenter::$EBIKE_ID;
        $username = "liulei";
        $phone    = mt_rand(11111111111,99999999999);

        $result = $this->client->post("/distribute.php", array(
            "ebikeid"  => $ebikeid,
            "username" => $username,
            "phone"    => $phone
        ));

        $this->assertTrue($result->errno === 0);
    }
}
