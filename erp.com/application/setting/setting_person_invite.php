<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";


if($_POST['tomail']){
	
	$mailHeader = "From: service@imihuan.com\nContent-Type:text/html;charset=utf-8;\n";
	$subject = "=?UTF-8?B?".base64_encode("米欢科技邀请函")."?=";
	$state =  mail($_POST['tomail'], $subject, "  您的好友-".$_POST['name']."，邀请您加入米欢科技！点击链接加入,<a href='https://www.imihuan.com'>www.imihuan.com</a>", $mailHeader);

	if($state == true){
		echo 1; //成功
	}else{
		echo 0; 
	}
	exit;
}

if($_POST['tomobile']){
	$apikey = "e98dc47dc771789eae4849090a845bc6";
	$mobile = $_POST['tomobile'];
	$url = 'www.imihuan.com';
	$text = "【米欢科技】".$url.'欢迎登录米欢科技！';
	$str = send_sms($apikey,$text, $mobile);

	//解析短信请求返回值
	$arr = json_decode($str,true);
	//判断是否发送成功code=0 为成功；
	if($arr['code']=="0"){
		echo 1;
	}else{
		echo 0;
	}
	exit;
}


// 发送短信
function send_sms($apikey, $text, $mobile){
    $url="http://yunpian.com/v1/sms/send.json";
    $encoded_text = urlencode("$text");
    $mobile = urlencode("$mobile");
    $post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
    return sock_post($url, $post_string);
}

// function tpl_send_sms($apikey, $tpl_id, $tpl_value, $mobile){
//     $url="http://yunpian.com/v1/sms/tpl_send.json";
//     $encoded_tpl_value = urlencode("$tpl_value");  //tpl_value需整体转义
//     $mobile = urlencode("$mobile");
//     $post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
//     return sock_post($url, $post_string);
// }


function sock_post($url,$query){
    $data = "";
    $info=parse_url($url);
    $fp=fsockopen($info["host"],80,$errno,$errstr,30);
    if(!$fp){
        return $data;
    }
    $head="POST ".$info['path']." HTTP/1.0\r\n";
    $head.="Host: ".$info['host']."\r\n";
    $head.="Referer: http://".$info['host'].$info['path']."\r\n";
    $head.="Content-type: application/x-www-form-urlencoded\r\n";
    $head.="Content-Length: ".strlen(trim($query))."\r\n";
    $head.="\r\n";
    $head.=trim($query);
    $write=fputs($fp,$head);
    $header = "";
    while ($str = trim(fgets($fp,4096))) {
        $header.=$str;
    }
    while (!feof($fp)) {
        $data .= fgets($fp,4096);
    }
    return $data;
}	
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


