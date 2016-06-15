<?php

//pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");
//
//class AuthenticateTC extends PHPUnit_Framework_TestCase 
//{
//
//    public function __construct()
//    {
//        $this->client = LocalHttpClient::ins();
//    }
//
//    public function test_authenticate()
//    {
//        $imei = DataGenter::$IMEI;
//        $v    = 2;
//        $m    = 0;
//        $data = DataGenter::getData();
//        DataGenter::$DATA = $data;
//        $sign = DataGenter::getSign();
//
//        $result = $this->client->post("/authenticate_sensor.php", array(
//            "imei"   => $imei,
//            "v"      => $v,
//            "m"      => $m,
//            "data"   => $data,
//            "sign"   => $sign
//        ));
//
//        
//        $this->assertTrue($result->errno === 0);
//    }
//}
