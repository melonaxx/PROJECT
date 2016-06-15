<?php

pylon_include_sdk("/home/q/php/sdk_base"   , "sdk_base.php");
pylon_include_sdk("/home/q/php/woreo_sdk" , "woreo_sdk.php");
pylon_include_sdk("/home/q/php/wcloud_sdk" , "wcloudgate_sdk.php");
pylon_include_sdk("/home/q/php/wcloud_sdk" , "platform_sdk.php");
pylon_include_sdk("/home/q/php/wcloud_sdk" , "labor_sdk.php");

/**
 * @brief   验证码、手机动态码、mysql
 */
class GClientAltar
{
	
	public static function getWCloudGateClient($caller="wCloudGate")
	{
		$logger = new logger("data");
		$conf = new GHttpConf();
		$conf->appConf($_SERVER['WCLOUT_API_SVC'] , $logger , null , $caller);
		$conf->port = 8060;

		return new WCloudGateClient($conf);
	}

	public static function getWOreoClient($caller="wOreo")                   
	{                                                                         
	    $logger = new logger("data");                                         
	    $conf = new GHttpConf();                                              
	    $conf->appConf($_SERVER['WOREO_API_SVC'] , $logger , null , $caller); 
	    $conf->port = 8060;                                                   
	                                                                           
	    return new WOreoClient($conf);                                        
    }

    public static function getPlatformClient($caller="wCloudGate")  
    {
		$logger = new logger("data");
		$conf = new GHttpConf();
		$conf->appConf($_SERVER['WCLOUT_API_SVC'] , $logger , null , $caller);
		$conf->port = 8060;

		return new PlatformClient($conf);
    }

    public static function getLaborClient($caller="wCloudGate")  
    {
		$logger = new logger("data");
		$conf = new GHttpConf();
		$conf->appConf($_SERVER['WCLOUT_API_SVC'] , $logger , null , $caller);
		$conf->port = 8060;

		return new LaborClient($conf);
    }
    
}

 
