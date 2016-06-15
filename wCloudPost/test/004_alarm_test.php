<?php
    
pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class AlarmTC extends PHPUnit_Framework_TestCase
{
   public function __construct() 
   {
       $this->client = LocalHttpClient::ins();
   }

   public function test_alarm()
   {
       $id   = '1459130224358923';
       $sign = DataGenter::getSignAlarm();
       $s    = 1;
       $t    = DataGenter::$TIME; 

       $result   = $this->client->post("/w", array(
            "id"       => $id,
            "s"        => $s, 
            "t"        => $t,
            "sign"     => $sign,
        ));

       $result = $result->toArray(); 
       $this->assertTrue($result['rc'] === 0);
   } 

}
