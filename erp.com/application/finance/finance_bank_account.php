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
include "../function.php";


//---- 删除单条数据 ----
if(isset($_GET['m']) && $_GET['m'] == 'delete' && isset($_GET['id']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$id	= intval($_GET['id']);
	$sql = "SELECT is_default FROM finance_bank WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
	$result = mysql_query($sql,$_mysql_link_);
	$res = mysql_result($result,0,0);
	if($res == "Y"){
		echo("<script>alert('默认账户，不能删除！');window.location.href='/finance/finance_bank_account.php';</script>");
	}else{
	$sql = "UPDATE finance_bank SET status='Delete' WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
	mysql_query($sql, $_mysql_link_);
	header('Location: /finance/finance_bank_account.php');
	exit;
	}
}

$company_id = $_SESSION['_application_info_']['company_id'];
//---分页---
$sql	= "SELECT COUNT(*) AS total FROM finance_bank WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND status = 'Y'";

$result	= mysql_query($sql, $_mysql_link_);

$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$a = 1;//定义序号变量
$sql = "SELECT id,name,balance,`number`,body,action_date,is_default FROM finance_bank WHERE company_id = '$company_id' AND status = 'Y' LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);

while($store = mysql_fetch_object($result)){
	$finance 					= array();
	$finance['num']				=	$a++;
	$finance['id']				=	$store->id;
	$finance['name']			=	$store->name;
	$finance['body']			=	$store->body;
	$finance['number']			=	$store->number;
	$finance['balance']			=	$store->balance;
	$finance['is_default'] 		= 	$store->is_default;
	$finance['action_date'] 	= 	$store->action_date;
	$xtpl->assign("finance", $finance);
	$xtpl->parse("main.finance");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");