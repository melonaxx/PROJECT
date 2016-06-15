<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class SubCompanyTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_subCompany()
    {
        $userid   = DataGenter::$USER_ID; 
        $company  = "baiduwaimai";
        $name     = "liulei".rand(0,1000);
        $passwd   = "123455";
        $username = "liulei";
        $phone    = 123455678113;

        $result = $this->client->post("/register_subcompany.php", array(
            "userid"   => $userid,
            "company"  => $company,
            "name"     => $name,
            "passwd"   => $passwd,
            "username" => $username,
            "phone"    => $phone
        ));

        $this->assertTrue($result->errno === 0);
    }
}
