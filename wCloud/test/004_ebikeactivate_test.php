<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class EbikeActivateTC extends PHPUnit_Framework_TestCase 
{
    
    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_activate_ebike()
    {
        $userid   = DataGenter::$USER_ID;
        //$userid   = 42;
        $seqno[]  = DataGenter::$SEQNO;


        $result = $this->client->post("/active_ebike.php", array(
            "userid"   => $userid,
            "seqno"    => $seqno,
        ));

        $this->assertTrue($result->errno === 0);
    }

}
