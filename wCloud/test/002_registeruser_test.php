<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class RegisterUserTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_register_user()
    {
        $mobileno = mt_rand(11111111111, 99999999999);
        DataGenter::$MOBILENO = $mobileno;
        $passwd   = "123456";

        $result = $this->client->post("/register_user.php", array(
            "mobileno"  => $mobileno,
            "passwd"    => $passwd
        ));

        $this->assertTrue($result->errno === 0);
        $data = $result->data;
        DataGenter::$USER_ID = $data['userid'];
    }
}
