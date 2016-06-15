<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class ActivateTC extends PHPUnit_Framework_TestCase
{
   public function __construct() 
   {
       $this->client = LocalHttpClient::ins();
   }

   public function test_activate()
   {
       $id       = "1459127861518959";
       $i        = DataGenter::getIMSI(); 
       $mobileno = DataGenter::getMobileno();
       $v        = rand(1,100);
       $result   = $this->client->post("/a", array(
            "id" => $id,
            "m"  => $mobileno,
            "i"  => $i,
            "v"  => $v
        ));

       $result = $result->toArray();
       $this->assertTrue($result['ra'] === 0);
       DataGenter::$CONF     = $result['conf'];
       DataGenter::$MOBILENO = $mobileno; 
   } 


}
