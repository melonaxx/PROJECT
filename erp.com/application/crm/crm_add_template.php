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


$company_id = $_SESSION['_application_info_']['company_id'];

if(!empty($_POST)){
	$store_type    = replace_safe($_POST['store_type']);
	$name          = replace_safe($_POST['name']);
	$content       = replace_safe($_POST['content']);
	$store_sign    = "【".replace_safe($_POST['store_sign'])."】";

	$url    = 'https://sms.yunpian.com/v1/tpl/add.json';
	$apikey = 'apikey=e98dc47dc771789eae4849090a845bc6&tpl_content='.$store_sign.$content;
    $str = sock_post($url,$apikey);
	$arr = json_decode($str,true);
    $yes = $arr['code'];
    $no  = $arr['msg'];
	$arr = $arr['template'];
    if($yes == 0){
        $sql = "INSERT INTO crm_message_template SET template_id='{$arr['tpl_id']}',company_id = '$company_id',type='$store_type',name='$name',sign='$store_sign',content='$content',status='{$arr['check_status']}' ";
        mysql_query($sql,$_mysql_link_);
    }else{
        echo "<script>alert('".$no."')</script>";
    }

	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}


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