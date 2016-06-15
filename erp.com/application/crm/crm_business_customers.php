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

//---- 删除单条数据 ----
if(isset($_GET['m']) && $_GET['m'] == 'delete' && isset($_GET['id']))
{
	$id				= intval($_GET['id']);
	$crm_user_id	= intval($_GET['crm_user_id']);
	// 删除客户公司对应关系表中信息
	$sql	= "DELETE FROM  crm_user_related WHERE company_id='$company_id' AND id='$id'";
	mysql_query($sql, $_mysql_link_);


	// 删除客户——未知客户信息（不需要删除）
	// $sql = "SELECT bind_id FROM crm_user_info WHERE id={$crm_user_id}";
	// $result = mysql_query($sql, $_mysql_link_);

	// $user_info = mysql_fetch_assoc($result);
	// $bind_id = $user_info['bind_id'];
	// $sql = "DELETE FROM crm_user_known
	// WHERE id='{$bind_id}'";
	// mysql_query($sql, $_mysql_link_);

	// // 删除客户信息表中信息（不需要删除）
	// $sql	= "DELETE FROM  crm_user_info WHERE id='$crm_user_id'";
	// mysql_query($sql, $_mysql_link_);

	header('Location: /crm/crm_business_customers.php');
	exit;
}

/*
//---- 删除多条数据 ----
if(isset($_GET['m']) && $_GET['m'] == 'deleteAll' && isset($_GET['idArr']))
{
	$idArr	= replace_safe($_GET['idArr']);
	$sql	= "UPDATE purchase_supplier SET is_delete='Y' WHERE company_id='$company_id' AND id IN ($idArr)";
	mysql_query($sql, $_mysql_link_);
	header('Location: /crm/crm_supplier_list.php');
	exit;
}
*/

$where = "WHERE r.company_id='$company_id'";
if($_GET['find']){
	$nick_name			= replace_safe($_GET['nick_name']);
	$crm_type			= intval($_GET['crm_type']);

	if($_GET['nick_name']){
		$where= "AND (instr(i.nick_name,'$nick_name') OR instr(r.name,'$nick_name'))";
	}

	if($_GET['crm_type'] && $crm_type!=""){
		$where .= "AND r.crm_type='$crm_type'";
	}

	$find = array();
	$find['crm_type'] = $crm_type;
	$find['nick_name']= $nick_name;

	$xtpl->assign("find",$find);
	$xtpl->parse("main.find");
}


//---- 数量 ----

$sql	= "SELECT COUNT(*) AS total  FROM  crm_user_related AS r INNER JOIN crm_user_info AS i ON i.id=r.crm_user_id ".$where;
$result	= mysql_query($sql, $_mysql_link_);

$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

if($main['total'] > 0)
{
	//---- 客户数量大于0 ----
	$sql= "SELECT r.id,r.name, r.state_id, r.city_id, r.district_id, r.mobile, r.body, i.id as crm_user_id,i.nick_name
		FROM crm_user_related AS r
		INNER JOIN crm_user_info AS i ON i.id = r.crm_user_id $where
		ORDER BY r.id DESC
		LIMIT ".$limit;
	$num = 1;
	$result		= mysql_query($sql, $_mysql_link_);
	while($CustomerInfo = mysql_fetch_object($result))
	{
		$value	= array();
		$value['id']			= $CustomerInfo->id;
		$value['crm_user_id']	= $CustomerInfo->crm_user_id;
		$value['name']			= $CustomerInfo->name;
		$value['nick_name']		= $CustomerInfo->nick_name;
		$value['mobile']		= $CustomerInfo->mobile;
		$value['body']			= $CustomerInfo->body;
		$value['num']			= $num++;


		// 查询地址信息
		//   省
		$state_id				= $CustomerInfo->state_id;
			$sql_state = "SELECT name FROM main_identity_card WHERE number=$state_id";
			$res_state   = mysql_query($sql_state, $_mysql_link_);
			$Customer_state = mysql_fetch_object($res_state);
		$value['state_id']		= $Customer_state->name;

		// 	 市
		$city_id				= $CustomerInfo->city_id;
			$sql_city 	= "SELECT name FROM main_identity_card WHERE number=$city_id";
			$res_city   = mysql_query($sql_city, $_mysql_link_);
			$Customer_city = mysql_fetch_object($res_city);
		$value['city_id']		= $Customer_city->name;

		//   县
		$district_id			= $CustomerInfo->district_id;
			$sql_district = "SELECT name FROM main_identity_card WHERE number=$district_id";
			$res_district   = mysql_query($sql_district, $_mysql_link_);
			$Customer_district = mysql_fetch_object($res_district);
		$value['district_id']		= $Customer_district->name;

		// 查询订单总数，订单总额
		$sql2 = "SELECT count(*) AS order_total,sum(o.theory_amount) AS money_total FROM finance_order AS o
			RIGHT JOIN order_source AS s ON s.id=o.order_id
			LEFT JOIN order_info AS i ON i.id=o.order_id WHERE i.status='S' AND s.crm_user_id = '$CustomerInfo->crm_user_id'";

		$result2 = mysql_query($sql2,$_mysql_link_);
		while($rows = mysql_fetch_object($result2)){
			$value['order_total'] = $rows->order_total;
			$value['money_total'] = $rows->money_total;
		}
		$xtpl->assign("customerList", $value);
		$xtpl->parse("main.customerList");
	}
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

