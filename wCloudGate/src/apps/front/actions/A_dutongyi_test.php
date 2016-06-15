<?php
class Action_ddddd extends XAction
{
    public function _run($request , $xcontext)
    {
        echo "<pre>";
$a = "123456789@1.01234511";
$b = preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $a) ;
var_dump($b);



// var_dump( XParamFilter::htmlSpecial($a) );

// var_dump(htmlspecialchars($a) );
// var_dump(is_array($a));

        // $string = 6;
        // $cbin = decbin($string);
        // $arr[] = substr($cbin , 2  , 1);
        // for($i = 0; $i < strlen($cbin); $i++) {
            
        // }

        // var_dump( strrev($cbin));
        // var_dump( $arr);


// $p = "3.1.2";
// $a = new Permission($p);
// $b = array(

//     array(1,2),

// );

 
    // for($i=0;$i<count($b);$i++){
    //     if($a->allow($b[$i][0] , $b[$i][1])) {
    //         $c[$i][$b[$i][0]] = $b[$i][1];
    //         $c[$i]['state'] = 1;
    //     }else{
    //         $c[$i][$b[$i][0]] = $b[$i][1];
    //         $c[$i]['state'] = 0;
    //     }
        
    // }
    // var_dump($c);

    //     for($i=0;$i<count($b);$i++){
    //         $c = $a->add($b[$i][0] , $b[$i][1 ]);
    //         $d = $c->toString();
    //     }
    // var_dump($d);


    

        // $a = "11.2.2";
        // $b = new HavePermission($a);
        // $c = $b->toString();
        // var_dump($c);

        // $a = "011111111111111111111111111111";
        // $b = preg_match("/^[^\s]{30}$/", $a);
        // $b = preg_match("/^[\u4E00-\u9FA5\uF900-\uFA2Da-zA-Z0-9]{1,}$/", $a);
        // echo $b;

// echo time() + 608400;
        // echo md5("bsbg");

        // $ustate = new UserStateSvc();
        // $umsg = $ustate->userstatemsg(1 );
        // var_dump($umsg);

        // $a = "a";
        // $b = VerifyCaptcha::ins()->getVerifyNoteCaptcha($a);
        // var_dump($b);
        
        // $charnum  = new SimpleNum();
        // $c = $charnum->getsimplenum();
        // echo $c;

        // echo $_SERVER['REQUEST_METHOD'];

        // echo $stateip = VerifyIp::ins()->getVerifyIp($_SERVER['REMOTE_ADDR']);

        // $a = "qreq#wewq";
        // var_dump( VerifyIp::listarr($a));



   
        // echo md5($_SERVER['REMOTE_ADDR']);

        //sudo vim /var/log/messages
        // $logger = new logger("biz");
        // $err = $logger->error("wuwuwuwuw" );
        // var_dump($err);


        // $a=1234;$b="1234";echo strcasecmp($a, $b);


        //===================================================reids

        // $redisclient = GClientAltarRedis::getSessionRedisClient();
        // $red = $redisclient->ttl(VerifyIp::CAPTCHA_SERVER_KEY, md5($_SERVER['REMOTE_ADDR']));
        // echo $red;

        // $user = "captcha";
        // $key = 10;
        // $username = "username";
        // $a =  GClientAltarRedis::getSessionRedisClient();
        // $result = $a->setEx($user , $user , $key , $username);        

        // $user = "captcha";
        // $key = 10;
        // $username = "username";
        // $a =  GClientAltarRedis::getSessionRedisClient();
        // $result = $a->set($user , $user  , $username);

        // $user = "b93800f6b9521dcd3895475df3431abb";
        // $a =  GClientAltarRedis::getSessionRedisClient();
        // $result = $a->get($user , $user );
        // var_dump($result);        

        // $user = "mobileno";
        // $user1 = array("15" => "1" , "16" => "2" ,"17" => "3" );
        // $a =  GClientAltarRedis::getSessionRedisClient();
        // $result = $a->hMset($user , $user , $user1 );
        // var_dump($result);



        // var_dump(get_class_methods(XNext));
        // echo "<pre>";
        // var_dump($b);

 

        return XNext::nothing();
    }
}
//测试
class Action_test12 extends XAction
{
    public function _run($request,$xcontext)
    {
        echo "<pre>";

        // $app = "l";
        // $client = GClientAltar::getWOreoClient();
        // $result =  $client->createCode($app);
        // var_dump($result);

        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->labourinfo(9512 );
        // var_dump($result);

        // $result1 = $client->ebikeManage(9512 );
        // var_dump($result1);

        $id = 9572;
        // $arrseqno[0] = 1461042272848692;
        // $result2 = $client->ebikeUnWrap( $id , $arrseqno );
        
        // $result2 = $client->getExceptionEbike($id );

        $mobileno = 15737179731;
        // $result2 = $client->getUserInfoByMobileno( $mobileno );
        
        $a = 14802;$b=15222;
        $client = GClientAltar::getPlatformClient();
        $result = $client->delEmployee($a , $b );


        var_dump($result);
    }

}

//测试
class Action_test11 extends XAction
{
    public function _run($request,$xcontext)
    {
        echo "<pre>";

//         $userid = 9572;
//         $abnormal = 0;//(-1 , 1)
//         $labourid = 11752;
//         $allocation = 0;//(1,2,3)
//         $carsearch = 1461042290414891;
//         $data = array(
//                 'distribute' => $abnormal,
//                 // 'laborid' => $labourid,
//                 // 'exception' => $allocation,
//                 // 'seqno' => $carsearch
//             );
//         $client = GClientAltar::getWCloudGateClient();
//         $result = $client->searchEbike($userid , $data);
        

//         // $result = $client->ebikeManage($userid );
// var_dump($result);
        // $results1 = $client->getlabExceptionEbike($userid , $labourid);
        // var_dump($results1);



        $aa= 9602;
        $ci[0] = 1461042271339792;
        $ci[1] = 1461042270700124;
        // // 激活
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->activeEbike($ci , $aa );
        // echo "<pre>";var_dump($result);

        // //解绑        
        $client3 = GClientAltar::getWCloudGateClient();
        $result3 = $client3->ebikeUnWrap($aa , $ci);
        echo "<pre>";var_dump($result3);

       

        // //注册
        // $i1 = "15737175521";
        // $password1 = "111111";
        // $client1 = GClientAltar::getWCloudGateClient();
        // $result1 = $client1->registeruser($i1 , $password1  );
        // var_dump($result1);

        // // 登录
        // $data = "15737175521";
        // $password = "111111";
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->dologin($data , $password ); 
        // var_dump($result);

        //注册后完善信息
        // $id = 5132;
        // $name = "lisi";
        // $email = '1@1.1';
        // $qq = "11";
        // $wechat = "11"; 
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->completeuserinfo($id , $name , $email , $qq , $wechat );
        // echo "<pre>";var_dump($result);

        
        // $city = "秦皇岛市";
        // $name = "111";
        // $linkman = "zhangsan";
        // $mobileno = '11111';
        // $email = '1@1.1';        
        // $registerid = "123456";
        // $licence = "111111";
        // $companytype = "1";
        // $userid = "5142";
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->companyauth($city , $name , $linkman , $mobileno , $email , $registerid , $licence , $companytype , $userid );
        // echo "<pre>";var_dump($result);

        // $uid = 5462;$ip = "1";
        // $client1 = GClientAltar::getWCloudGateClient();
        // $result1 = $client1->encryptuserinfo($uid ,  $ip);
        // var_dump($result1);    

        // $token = $result1->data;
        //           // sSbTEG79PcGpfZS/V/S aQ==
        // $u = $_COOKIE['U'];
        // parse_str($u, $uinfo);
        // $userid = intval($uinfo[UserSvc::COOKIE_U_UID]);
        // $token = $uinfo[UserSvc::COOKIE_U_TOKEN];
        // // $token = "sSbTEG79PcGpfZS+1N3N7w==";
        // // $uid = 5462;
        // $client2 = GClientAltar::getWCloudGateClient();
        // $result2 = $client2->decryptuserinfo($userid ,  $token);
        // var_dump($result2);
        // $data = $result2->data;
        // $info = explode("\n" , $data);
        // $userid = $info[0];
        // $time = $info[1];
        // $ip = $info[2];
        // echo $userid.".".$time.".".$ip.".";

        // $data = "15737179731";
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->checkMobileno($data );
        // var_dump($result);


        // $id = "a8053948ac4d655aa1f7f332f0ab1305";
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->getsession($id );
        // var_dump($result);

    }
}   




//测试
class Action_test extends XAction
{
    public function _run($request,$xcontext)
    {

        // $sid = 692;
        // //传感器单个配置删除
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->delSensorConf($sid);
        // echo "<pre>";var_dump($result);

        // $id = 25092;
        //传感器历史记录删除
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->delConfLog($id);
        // echo "<pre>";var_dump($result);

        //传感器历史记录
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->getConfLog();
        // echo "<pre>";var_dump($result);


        // //传感器修改 添加
        // $ebikeid = "65311";
        // $version = "1";
        // $collectfreq = "3";
        // $f = "1";
        // $url = "http://yun.waimaiw.com/sen.php";
        // $wi = "1";
        // $wf = "1";
        // $wurl = "http://yun.waimaiw.com/alar.php";
        // $data = array("ebikeid"=> $ebikeid , "ver" => $version, "cf" => $collectfreq, "f" => $f, "url" =>$url , "wi" => $wi, "wf" => $wf, "wurl" => $wurl);
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->updateSensorConf($data);
        // echo "<pre>";var_dump($result);


        // $i = 532;
        // // //传感器单查询
        // $client1 = GClientAltar::getWCloudGateClient();
        // $result1 = $client1->getSingleSensorConf($i);
        // echo "<pre>";var_dump($result1);


        //传感器多查询
        // $client1 = GClientAltar::getWCloudGateClient();
        // $result1 = $client1->getSensorConf();
        // echo "<pre>";var_dump($result1);


        //密码修改
        // $i = 19472;
        // $p = "111111";
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->updatePasswd($i , $p );
        // echo "<pre>";var_dump($result);

        // echo "<pre>";var_dump($_SESSION['data']);



        //用户信息修改
        // $id = "19452";
        // $data = array("mobileno" => "11111111111");
        // $mobileno $name $email  $company $domain $logo
        // $client1 = GClientAltar::getWCloudGateClient();
        // $result1 = $client1->perfectInform($id , $data);
        // echo "<pre>";var_dump($result1);



        //外麦平台 外麦店
        // $rid = 2;
        // $id = 32512;
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->choiceRole($rid , $id);
        // echo "<pre>";var_dump($result);


        // 通过点击手机号获取验证码时
        //判断是否是正常用户
        // $data = array("mobileno" => "15737179731");
        // $password = "111111";
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->checkMobileno($data );
        // echo "<pre>";var_dump($result);

        // // 登录
        // $data = "11111111111";
        // $password = "111111";
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->dologin($data , $password);
        // echo "<pre>";var_dump($result);


        // //注册
        // $i = "13434343434";
        // $p = "111111";
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->registeruser($i , $p );
        // echo "<pre>";var_dump($result);

        // $a = 857
        // 电动车 id 坐标 序列号 电池量
        // $a = 1457070315637135;
        // $a = 1456999472243012;
        // $b = 310100;
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->statusShow($a , $b);
        // echo "<pre>";var_dump($result);

        //总数 总计
        // $aa= 42;
        // $client1 = GClientAltar::getWCloudGateClient();
        // $result1 = $client1->ebikeShow($aa);
        // echo "<pre>";var_dump($result1);

        // $city = 110100;
        // $client2 = GClientAltar::getWCloudGateClient();
        // $result2 = $client2->ebikestat($aa , $city);
        // echo "<pre>";var_dump($result2);
        
        // $aa= 9572;
        // $ci[0] = 1461042272848692;
        // $ci[1] = 1461042290414891;
        // // // // 激活
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->activeEbike($ci , $aa );
        // echo "<pre>";var_dump($result);

        // //解绑        
        // $client3 = GClientAltar::getWCloudGateClient();
        // $result3 = $client3->ebikeUnWrap($aa , $ci);
        // echo "<pre>";var_dump($result3);        

        // $lnglat = $result->data;
        // $x = $lnglat['status']['latitude'];
        // $y = $lnglat['status']['longitude'];

        // $id = 533;
        // $name = 'lis';
        // $phone = 1322;
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->ebikeDistribute($id , $name , $phone);
        // echo "<pre>";var_dump($result);

        // $id = 1;
        // $company = 'baidu';
        // $name = '张三1';
        // $password = "123456";
        // $uname = 'baidu_haidian';
        // $phone = 1231;
        // $client = GClientAltar::getWCloudGateClient();
        // $result = $client->registerSubCompany($id , $company , $name , $password , $uname , $phone);
        // echo "<pre>";var_dump($result);
        // return XNext::nothing();



        //云片短信接口
        //
        // $num = 9009;
        // $apikey = "65088ee6b4c4ad2d695d2f4d8be2a4f6";
        // //ÐÞ¸ÄÎªÄúµÄapikey(https://www.yunpian.com)µÇÂ½¹ÙÍøºó»ñÈ¡
        // $mobile = "18317714209"; //ÇëÓÃ×Ô¼ºµÄÊÖ»úºÅ´úÌæ
        // $text="¡¾ÍâÂóÍõÔÆÆ½Ì¨¡¿ÊÖ»ú¶ÌÐÅÑéÖ¤ÂëÎª ".$num." £¬Ê®·ÖÖÓÄÚÊäÈëÓÐÐ§£¬Èç·Ç±¾ÈË²Ù×÷ºöÂÔ´Ë¶ÌÐÅ¡£";
        // $ch = curl_init();

        // /* ÉèÖÃÑéÖ¤·½Ê½ */

        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));

        // /* ÉèÖÃ·µ»Ø½á¹ûÎªÁ÷ */
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // /* ÉèÖÃ³¬Ê±Ê±¼ä*/
        // curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // /* ÉèÖÃÍ¨ÐÅ·½Ê½ */
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // // ·¢ËÍÄ£°å¶ÌÐÅ
        // // ÐèÒª¶Ôvalue½øÐÐ±àÂë
        // $data=array(
        //     'tpl_id' => '1298781',
        //     'tpl_value' => '',
        //     'text' => $text,
        //     'apikey' => $apikey,
        //     'mobile' => $mobile
        // );
        // print_r ($data);
        // $json_data = Noteinterface::tpl_send($ch,$data);
        // $array = json_decode($json_data,true);
        // echo '<pre>';print_r($array);

        // //È¡µÃÓÃ»§ÐÅÏ¢
        // $json_data = Noteinterface::get_user($ch,$apikey);
        // $array = json_decode($json_data,true);
        // echo '<pre>';print_r($array);
        // //·¢ËÍ¶ÌÐÅ
        // $data=array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
        // $json_data = Noteinterface::send($ch,$data);
        // $array = json_decode($json_data,true);
        // echo '<pre>';print_r($array);



        // // ·¢ËÍÓïÒôÑéÖ¤Âë
        // $data=array('code'=>'9876','apikey'=>$apikey,'mobile'=>$mobile);
        // $json_data = Noteinterface::voice_send($ch,$data);
        // $array = json_decode($json_data,true);
        // echo '<pre>';print_r($array);

        // curl_close($ch);




    }
}

