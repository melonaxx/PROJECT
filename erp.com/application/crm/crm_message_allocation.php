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


//设置查询条件
$chaxun 	= array();
$chaxun[] 	= "company_id = '".$company_id."'";
$chaxun[] 	= "status = 'SUCCESS'";
if(!empty($_REQUEST['send'])){
	$type 	= replace_safe($_GET['type']);
    if(!empty($type)){
        $main['type'] 		= $type;
        $chaxun[] 			= "type = '".$type."'";
        $page_param     	= array();
        $page_param['type'] = replace_safe($_GET['type'], 20, false, false);
    }
}
$where 		= '';
if(count($chaxun)>0){
	$where 	= "WHERE ".implode(' AND ',$chaxun);
}

//---分页---
$sql    = "SELECT COUNT(*) AS total FROM crm_message_template ".$where;
$result = mysql_query($sql, $_mysql_link_);
$main['total']      = mysql_result($result, 0, 'total');

$page_param         = array();
$main['page_info']  = erp_page_info($main['total'], $page, $page_param);
$limit  = ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$strategy_status = array('Y'=>'开启','N'=>'已关闭');
$strat 			 = array('D'=>'大于等于','X'=>'小于');
$type = array('Deliver'=>'发货通知','Remind'=>'短信催付','Payment'=>'已付款通知');
$no   = 1;
$sql  = "SELECT id,type,sign,content,strategy_status,strategy FROM crm_message_template ".$where." LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$strategy 						= array();
	$strategy['no']					= $no++;
	$strategy['id'] 				= $dbRow->id;
	$strategy['sign'] 				= $dbRow->sign;
	$strategy['content'] 			= $dbRow->content;
	$strategy['type'] 				= $type[$dbRow->type];
	$strategy['strategy_status'] 	= $strategy_status[$dbRow->strategy_status];
	$str 							= $dbRow->strategy;
	$arr 							= explode('-', $str);
	$arr1							= $arr[0];
	$arr1							= $strat[$arr1];
	$arr2							= $arr[1];
	$strategy['strategy']			= $arr1.$arr2;

	$xtpl->assign('strategy',$strategy);
	$xtpl->parse('main.strategy');
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");