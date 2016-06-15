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
include "../bind_type.php";
include "../function.php";

if($_SESSION['_application_info_']['company_id'] < 1000)
{
	header("Location: /home/");
	exit;
}

if(!empty($_POST['bind_type']))
{
	$type	= $_POST['bind_type'];
	if(strlen($ERPBingLogin[$type]) > 1)
	{
		$code	= md5(time().$_SESSION['_application_info_']['company_id']);
		$sql	= "UPDATE company_name SET code='$code' WHERE id='".$_SESSION['_application_info_']['company_id']."'";
		mysql_query($sql, $_mysql_link_);
		header("Location: ".$ERPBingLogin[$type]."?join_request=".$code);
		exit;
	}
	echo "<html><body>非法操作</body></html>";
	exit;
}

foreach($ERPBindInfo as $type => $name)
{
	$list_type	= array();
	if($ERPBingLogin[$type] == "")
	{
		continue;
	}
	$list_type['id']	= $type;
	$list_type['name']	= $name;
	$xtpl->assign("list_type", $list_type);
	$xtpl->parse("main.list_type");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
