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

$company_id = $_SESSION['_application_info_']['company_id'];

$sql = "SELECT name FROM finance_bank WHERE company_id='{$company_id}' AND is_default = 'Y'";
$result  = mysql_query($sql,$_mysql_link_);
$default = mysql_result($result, 0, 0);
$main['default'] = $default;

$def   	  = array();
$def['Y'] = '是';
$def['N'] = '否';
$typ   			= array();
$typ['Cashier'] = '出纳账户';
$typ['Company'] = '公司账户';
$typ['Special'] = '特殊账户';
$typ['Secret']  = '私密账户';
if(!empty($_POST['made'])){
	$name 		= replace_safe($_POST['name']);
	$type 		= replace_safe($_POST['type']);
	$is_default = replace_safe($_POST['is_default']);
	$number 	= replace_safe($_POST['number']);
	$body 		= replace_safe($_POST['body']);

	if(!isset($def[$is_default]))
	{
		$is_default	= "N";
	}
	if(!isset($typ[$type]))
	{
		$type		= "Cashier";
	}

	$sql = "INSERT INTO finance_bank SET company_id='$company_id',type='$type',name='$name',number='$number',body='$body',is_default='$is_default',action_date = NOW()";
	mysql_query($sql,$_mysql_link_);
	$id = mysql_insert_id($_mysql_link_);
	if($is_default=="Y"){
		$sql = "UPDATE finance_bank SET is_default='N' WHERE company_id='$company_id' AND id!=$id";
		mysql_query($sql,$_mysql_link_);
	}
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
