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

$company_id	= $_SESSION['_application_info_']['company_id'];
//---- 删除单条数据 ----
if(isset($_GET['m']) && $_GET['m'] == 'delete' && isset($_GET['id']))
{
	$id		= intval($_GET['id']);
	$sql	= "UPDATE company_express_info SET status = 'D' WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
	mysql_query($sql, $_mysql_link_);
	header('Location: /setting/setting_express_list.php');
	exit;
}
// ---- 设置查询条件 ----
$addon = array();
$addon[] = "i.company_id = '{$_SESSION['_application_info_']['company_id']}'";
$addon[] = "i.status<>'D'";
if(!empty($_REQUEST['find'])){
	// ---- 查询快递名称 ----
	$find = replace_safe($_REQUEST['find'],20);
	if(!empty($find)){
		// ---- 设置查询条件 :只允许查询快递名称和联系人 ----
		$addon[] = "(INSTR(i.name, '$find') OR INSTR(i.contact_name, '$find'))";
		$main['find'] = $find;
		$page_param		= array();
		$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
	}
}
//---- 处理查询条件 ----
$where  = "";
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}

// ---- 数量 ----
$sql = "SELECT COUNT(*) as total FROM  company_express_info AS i ".$where;
//var_dump($sql);
$result = mysql_query($sql,$_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

// ---- 处理分页 ----

if(!is_array($page_param)){
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];


//--- 数量大于0 ---
if($main['total'] > 0){
	// ---- 查询 ----

	$sql = "SELECT i.id,i.name,i.express_id,i.contact_name,i.telphone,i.state_id ,i.city_id ,i.district_id ,i.address,i.body,t.id AS template_id,t.name AS template_name FROM company_express_info AS i LEFT JOIN company_express_template_info AS t ON t.express_id=i.express_id AND t.is_default='Y' AND t.company_id='$company_id' $where ORDER BY id LIMIT ".$limit;
	$num = 1;
	$result = mysql_query($sql,$_mysql_link_);
	while($CompanyInfo = mysql_fetch_object($result)){
		$company_express_info = array();
		$sql_2 = "SELECT name FROM main_identity_card WHERE number = '{$CompanyInfo -> state_id}'";
		$result_2 = mysql_query($sql_2,$_mysql_link_);
		while($CompanyExpress = mysql_fetch_object($result_2)){
			$company_express_info['state_id']	=	$CompanyExpress -> name;
		}
		$sql_3 = "SELECT name FROM main_identity_card WHERE number = '{$CompanyInfo -> city_id}'";
		$result_3 = mysql_query($sql_3,$_mysql_link_);
		while($CompanyExpress = mysql_fetch_object($result_3)){
			$company_express_info['city_id']	=	$CompanyExpress -> name;
		}
		$sql_4 = "SELECT name FROM main_identity_card WHERE number = '{$CompanyInfo -> district_id }'";
		$result_4 = mysql_query($sql_4,$_mysql_link_);
		while($CompanyExpress = mysql_fetch_object($result_4)){
			$company_express_info['district_id']	=	$CompanyExpress -> name;
		}


		$company_express_info['id']				= $CompanyInfo -> id;
		$company_express_info['name'] 			= $CompanyInfo -> name;
		$company_express_info['template_id'] 			= $CompanyInfo -> template_id;
		$company_express_info['template_name'] 			= $CompanyInfo -> template_name;
		$company_express_info['template_name'] 			= $CompanyInfo -> template_name;
		$company_express_info['express_id'] 	= $CompanyInfo -> express_id;
		$company_express_info['contact_name']	= $CompanyInfo -> contact_name;
		$company_express_info['telphone']		= $CompanyInfo -> telphone;
		$company_express_info['address']		= $CompanyInfo -> address;
		$company_express_info['body']			= $CompanyInfo -> body;
		$company_express_info['num']			= $num++;
		$xtpl->assign("company_express_info",$company_express_info);
		$xtpl->parse("main.company_express_info");
	}
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");