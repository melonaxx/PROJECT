<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class LoginUserTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_login()
    {
        $mobileno = DataGenter::$MOBILENO; 
        $data['mobileno'] = $mobileno;
        $passwd   = "123456";

        $result = $this->client->post("/login_user.php", array(
            "data"       => $data,
            "passwd"     => $passwd
        ));

        $user   = $result->data; 
        $this->assertTrue($result->errno === 0);
        DataGenter::$USER_ID = $user['id'];
    }

}
