<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class LocateDataTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_locateData()
    {
        //$seqno = DataGenter::$SEQNO;
        $seqno = "1459130287868894";

        $result = $this->client->post("/status_show.php", array(
            "seqno" => $seqno
        ));

        $this->assertTrue($result->errno === 0);
    }
}
