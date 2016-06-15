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

$template_id = $_GET['template_id'];
$sql = "SELECT id,template_id,type,name,content,sign FROM crm_message_template WHERE company_id = '$company_id' AND template_id = '$template_id' ";
$result         = mysql_query($sql,$_mysql_link_);
$dbRow          = mysql_fetch_object($result);
$res            = array();
$res['type']    = $dbRow->type;
$res['name']    = $dbRow->name;
$res['content'] = $dbRow->content;
$res['sign']    = str_replace('【','',str_replace('】', '', $dbRow->sign));
$xtpl->assign('res',$res);
$xtpl->parse('main.res');


if(!empty($_POST)){
    $type = replace_safe($_POST['type']);
    $name = replace_safe($_POST['name']);
    $content = replace_safe($_POST['content']);
    $sign = "【".replace_safe($_POST['sign'])."】";
    $url = 'https://sms.yunpian.com/v1/tpl/update.json';
    $apikey = 'apikey=e98dc47dc771789eae4849090a845bc6&tpl_id='.$template_id.'&tpl_content='.$sign.$content;
    $str = sock_post($url,$apikey);
    $arr = json_decode($str,true);
    $arr = $arr['template'];
    $sql = "UPDATE crm_message_template SET type='$type',name='$name',sign='$sign',content='$content',status='{$arr['check_status']}',reason='{$arr['reason']}' WHERE company_id = '$company_id' AND template_id = '$template_id' ";
    mysql_query($sql,$_mysql_link_);
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