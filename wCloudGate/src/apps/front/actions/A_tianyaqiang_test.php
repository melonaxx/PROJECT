<?php
class Action_cloudgatetest extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {

	    /**
		*	@brief 单个电动车路径显示
		*	@param 	seqno  电动车序列号
		* 	@return object  单个电动车多个坐标
	    */
	    $seqno = '1462603657320104';
	    $client = GClientAltar::getWCloudGateClient();
	    $result = $client->pathShow($seqno);

		/**
		* 	@brief 通过单个电动车序列号查询电车位置
		* 	@return  	单个电动车的位置
		*/
		// $seqno = '1461394071564781';
		// $client = GClientAltar::getWCloudGateClient();
  // 		$result = $client->statusShow($seqno);


		//$redis = new Redis();
  		//$redis->connect('127.0.0.1', 6379);

  		//$redis->set("mame", "jack");

	 	//echo "Stored string in redis:: " + jedis.get("name");


		/**
		* 	后台用户的登陆
		*/
		// $name = 'admin';
		// $password = 'admin';
		// $client = GClientAltar::getWCloudGateClient();
	 // 	$result = $client->adminLogin($name,$password);


		//redis 测试
		// $redisclient =  GClientAltarRedis::getSessionRedisClient();
		// $result = $redisclient->setEx(self::CODE_SERVER_KEY, 'name', self::TIME_OUT, 'jack');

		// $user = "captcha";
  		// $key = "mn";
  		// $username = "username";
  		// $a =  GClientAltarRedis::getSessionRedisClient();
  		// $result = $a->setEx(10 , $user , 10 , $username);

        // $user = "captcha";
        // $a =  GClientAltarRedis::getSessionRedisClient();
        // $result = $a->get($user , $user );

		// $xcontext->name = 'jack';

	    /**
		*	@brief 通过用户ID查询电动车序列号
		*	@param 	userid
		* 	@return object  单个电动车序列号
	    */
		// $userid = $xcontext->userid;
		// var_dump($userid);
		// $client = GClientAltar::getWCloudGateClient();
  //       $result = $client->getExceptionEbike($userid );

		//平台帐号下车辆管理->所有车辆->查看定位接口
		//平台帐号下车辆管理->异常车辆->查看定位接口(两个)
		//平台帐号下劳务方->劳务方管理->查看定位接口(两个)
		//平台帐号下首页->查看定位接口(两个)

		//劳务方帐号下骑士->查看定位接口
		//劳务方帐号下车辆管理->所有车辆->查看定位接口
		//劳务方帐号下车辆管理->异常车辆->查看定位接口
		//劳务方帐号下首页->查看定位接口(两个)

		//骑士帐号下首页->查看定位接口

		// 平台下自由车辆和其他车辆
		// $userid = $xcontext->userid;
		// echo $userid;
		// $client = GClientAltar::getLaborClient();
  //       $result = $client->showPlatformInfo($userid);

		// 劳务方下的车辆
		// $client = GClientAltar::getLaborClient();
		// $result = $client->statLabEbikeInfo($userid,$_GET['laborid']);

		// 平台下的车辆
		// $client = GClientAltar::getLaborClient();
		// $result = $client->statPlatformEbikeinfo($userid,$_GET['platformid']);


	    echo '<pre>';
	    var_dump($result);
	    die();
        return XNext::nothing();
    }
}

