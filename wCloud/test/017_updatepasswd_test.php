<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class UpdatePasswdTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_update_passwd()
    {
        $userid = 42;
        $passwd = '88888888';

        $result = $this->client->post("/update_passwd.php", array(
            "userid" => $userid,
            "passwd" => $passwd
        ));

        $this->assertTrue($result->errno === 0);
    }

}
