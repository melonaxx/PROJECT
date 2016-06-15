<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class ChoiceRoleTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_choice_role()
    {
        $userid = DataGenter::$USER_ID;
        $roleid = 5;

        $result = $this->client->post("/choice_role.php", array(
            "userid" => $userid,
            "roleid" => $roleid
        ));

        $this->assertTrue($result->errno === 0);
    }

}
