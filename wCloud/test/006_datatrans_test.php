<?php

//pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");
//
//class StateTC extends PHPUnit_Framework_TestCase 
//{
//
//    public function __construct()
//    {
//        $this->client = LocalHttpClient::ins();
//    }
//
//    public function test_datatrans()
//    {
//        $imei       = DataGenter::$IMEI;
//        $data       = DataGenter::getData1();
//        $version    = 2;
//
//        $result = $this->client->post("/data_trans.php", array(
//            "imei"       => $imei,
//            "data"       => $data,
//            "version"    => $version
//        ));
//
//        $this->assertTrue($result->errno === 0);
//    }
//}
