<?php  

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");

class SensorTC extends PHPUnit_Framework_TestCase 
{

    public function __construct()
    {
        $this->client = LocalHttpClient::ins();
    }

    public function test_register_dev()
    {
        $imei  = DataGenter::genSensorIMEI(); 
        $mobel = "36V,12A";
        $seqno = DataGenter::genSeqno();

        $result = $this->client->post("/register_dev.php", array(
            "imei"  => $imei,
            "mobel" => $mobel,
            "seqno" => $seqno
        ));

        $data = $result->data;
        $this->assertTrue($result->errno === 0);
        DataGenter::$SEQNO = $seqno;
        DataGenter::$IMEI  = $imei;
        $ebikeid = $data['ebikeid'];
        DataGenter::$EBIKE_ID = $ebikeid;
    }

}
