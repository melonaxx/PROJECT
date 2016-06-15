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

$id		= intval($_REQUEST['id']);
$sql	= "SELECT name, bind_type, bank_id FROM company_shop WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	illegal_operation();
	exit;
}
$main['id']		= $id;
$main['name']	= mysql_result($result, 0, 'name');
$bind_type		= mysql_result($result, 0, 'bind_type');
$old_bank		= mysql_result($result, 0, 'bank_id');
$main['bind_type']	= $ERPBindInfo[$bind_type];
if($ERPBingLogin[$bind_type])
{
	$hidden	= array();
	$hidden['bind_type']	= $bind_type;
	$xtpl->assign("hidden", $hidden);
	$xtpl->parse("main.hidden");
}

$BankInfo	= array();
$BankInfo[0]	= "-- 暂无 --";
$sql	= "SELECT id, name FROM finance_bank WHERE company_id='".$_SESSION['_application_info_']['company_id']."'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$BankInfo[$dbRow->id]	= $dbRow->name;
}

foreach($BankInfo as $b_id => $b_name)
{
	$bank	= array();
	$bank['id']		= $b_id;
	$bank['name']	= $b_name;
	if($old_bank == $b_id)
	{
		$bank['sel']	= " selected";
	}
	$xtpl->assign("bank", $bank);
	$xtpl->parse("main.bank");
}

if(!empty($_POST['name']))
{
	$name	= replace_safe($_POST['name']);
	$bank	= intval($_POST['bank_id']);
	if(!isset($BankInfo[$bank]))
	{
		$bank	= 0;
	}
	$sql	= "UPDATE company_shop SET name='$name', bank_id='$bank' WHERE id='$id'";
	mysql_query($sql,$_mysql_link_);

	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

