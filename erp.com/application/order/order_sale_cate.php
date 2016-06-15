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

$company_id = $_SESSION['_application_info_']['company_id'];
	
	$sql = "SELECT count(*) AS total FROM after_sale_topic WHERE company_id='$company_id' AND is_delete='N'";
	$result	= mysql_query($sql, $_mysql_link_);
	$main['total']		= mysql_result($result, 0, 'total');

	//---- 处理分页 ----
	if(!is_array($page_param)) 
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];


	$sql = "SELECT id,name FROM after_sale_topic WHERE company_id='$company_id' AND is_delete='N' ORDER BY id LIMIT ".$limit;
	$result2 = mysql_query($sql,$_mysql_link_);
	while($res = mysql_fetch_object($result2)){
		$arr = array();
		$arr['name'] = $res->name;
		$arr['id']   = $res->id;
		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");
	}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");