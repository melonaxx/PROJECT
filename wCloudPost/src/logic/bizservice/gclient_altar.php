<?php

pylon_include_sdk("/home/q/php/sdk_base", "sdk_base.php");
pylon_include_sdk("/home/q/php/wcloud_sdk/", "wcloud_sdk.php");

class GClientAltar
{
	public static function getWCloudClient($caller="wCloudPost")
	{
		$logger = new logger("data");
		$conf   = new GHttpConf();
		$conf->appConf($_SERVER["WCLOUD_API_SVC"], $logger, null, $caller);
		$conf->port = 8060;

		return new WCloudClient($conf);
    }
}
