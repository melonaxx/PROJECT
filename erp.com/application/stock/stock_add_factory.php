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
if(!empty($_POST['send']))
{
	$ip				= $_SERVER['REMOTE_ADDR'];
	$number			= replace_safe($_POST['number']);
	$store_type		= replace_safe($_POST['store_type']);
	$name			= replace_safe($_POST['name']);
	$contact_name	= replace_safe($_POST['contact_name']);
	$mobile			= replace_safe($_POST['mobile']);
	$telphone		= replace_safe($_POST['telphone']);
	$state_id		= intval($_POST['state_id']);
	$city_id		= intval($_POST['city_id']);
	$district_id	= intval($_POST['district_id']);
	$address		= replace_safe($_POST['address']);
	$body			= replace_safe($_POST['body']);

	if(empty($number))
	{
		$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "store");
	}
	$sql = "INSERT INTO store_info SET company_id='".$_SESSION['_application_info_']['company_id']."', store_type='$store_type', ip='$ip', action_date=NOW(), number='$number', name='$name', contact_name='$contact_name', mobile='$mobile', telphone='$telphone', state_id='$state_id', city_id='$city_id', district_id='$district_id', address='$address', body='$body'";
	mysql_query($sql, $_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
