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
include "../bind_type.php";
include "../function.php";

$company_id = $_SESSION['_application_info_']['company_id'];


$total	= 0;
$sql	= "SELECT s.id, s.name, s.bind_type, s.action_date, s.bank_id, b.name AS bank_name FROM company_shop AS s LEFT JOIN finance_bank AS b ON s.bank_id=b.id WHERE s.company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$list_shop	= array();
	$list_shop['id']	= $dbRow->id;
	$list_shop['name']	= $dbRow->name;
	$list_shop['date']	= $dbRow->action_date;
	$list_shop['bank']	= $dbRow->bank_name;
	$list_shop['type']	= $ERPBindInfo[$dbRow->bind_type];
	$xtpl->assign("list_shop", $list_shop);
	$xtpl->parse("main.list_shop");
	$total++;
}
$main['total']	= $total;


/*
$total	= 0;
$sql	= "SELECT related_type, user_id, action_date FROM company_related WHERE company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($RelatedInfo = mysql_fetch_object($result))
{
	$list_shop	= array();
	$shop_name	= "";
	$bind_type	= "";
	$sql	= "SELECT shop_name, bind_type FROM user_register_info WHERE id='$RelatedInfo->user_id'";
	$res	= mysql_query($sql, $_mysql_link_);
	if(mysql_num_rows($res))
	{
		$shop_name	= mysql_result($res, 0, 'shop_name');
		$bind_type	= mysql_result($res, 0, 'bind_type');
		$bind_type	= $ERPBindInfo[$bind_type];
	}
	$list_shop['name']	= $shop_name;
	$list_shop['date']	= $RelatedInfo->action_date;
	$list_shop['type']	= $bind_type;
	$xtpl->assign("list_shop", $list_shop);
	$xtpl->parse("main.list_shop");
	$total++;
}
$main['total']	= $total;
*/

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
