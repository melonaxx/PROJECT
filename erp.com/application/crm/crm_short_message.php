<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/xml; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";

$company_id = $_SESSION['_application_info_']['company_id'];
//查看短信剩余条数
$sql = "SELECT message_remain FROM company_name WHERE id='$company_id' ";
$res = mysql_query($sql,$_mysql_link_);
if($res)
{
    $message_remain         = mysql_result($res,0,0);
    $main['message_remain'] = $message_remain;
}


//查看公司短信模板是否有审核中的状态
$sql = "SELECT id,status FROM crm_message_template WHERE company_id='$company_id' ";
$result = mysql_query($sql,$_mysql_link_);
$status = array();
while($dbRow = mysql_fetch_object($result))
{
    $status[] = $dbRow->status;
}
//判断如果有审核中的模板，发送请求到短信服务商获取审核状态，对本地数据进行修改
if(in_array('CHECKING',$status))
{
    $url = 'https://sms.yunpian.com/v1/tpl/get.json';
    $apikey = 'apikey=e98dc47dc771789eae4849090a845bc6';
    $str = sock_post($url,$apikey);
    $arr = json_decode($str,true);
    $arr = $arr['template'];
    $short = array();
    if($arr){
        foreach ($arr as $k => $v) {
            $short['id']       = $v['tpl_id'];
            $short['content']  = $v['tpl_content'];
            $short['status']   = $v['check_status'];
            $short['reason']   = $v['reason'];
            $sql = "UPDATE crm_message_template SET status = '{$short['status']}',reason = '{$short['reason']}' WHERE company_id = '$company_id' AND template_id = '{$short['id']}' ";
            mysql_query($sql,$_mysql_link_);
        }
    }
}

//设置查询条件
$chaxun = array();
$chaxun[] = "company_id = '".$company_id."'";
//判断是否有搜索内容
if(!empty($_GET['send'])){
    $type = replace_safe($_GET['type']);
    if(!empty($type)){
        $main['type']           = $type;
        $chaxun[]               = "type = '".$type."'";
        $page_param             = array();
        $page_param['type']     = replace_safe($_GET['type'], 20, false, false);
    }
}
$where = '';
if(count($chaxun) > 0){
    $where = "WHERE ".implode(" AND ",$chaxun);
}

//---分页---
$sql    = "SELECT COUNT(*) AS total FROM crm_message_template ".$where;
$result = mysql_query($sql, $_mysql_link_);
$main['total']      = mysql_result($result, 0, 'total');

$page_param         = array();
$main['page_info']  = erp_page_info($main['total'], $page, $page_param);
$limit  = ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$type = array('Deliver'=>'发货通知','Remind'=>'短信催付','Payment'=>'已付款通知');
$status = array('CHECKING'=>'审核中','SUCCESS'=>'已通过','FAIL'=>'未通过');
//获取短信模板信息
$sql = "SELECT template_id,name,type,content,sign,status,reason FROM crm_message_template ".$where." ORDER BY id DESC LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
$no = 1;
$checking = array();
while($dbRow = mysql_fetch_object($result)){
	$stock 				     = array();
	$stock['no'] 		     = $no++;
	$stock['name'] 		     = $dbRow->name;
	$stock['sign'] 		     = $dbRow->sign;
    $stock['reason']         = $dbRow->reason;
    $stock['content']        = $dbRow->content;
    $stock['type']           = $type[$dbRow->type];
    $stock['template_id']    = $dbRow->template_id;
	$stock['status'] 	     = $status[$dbRow->status];
    $checking[]              = $dbRow->status;

	$xtpl->assign('stock',$stock);
	$xtpl->parse('main.stock');
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