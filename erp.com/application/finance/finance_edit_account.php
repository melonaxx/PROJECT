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

$company_id = $_SESSION['_application_info_']["company_id"];
//查询当前默认账户
$sql = "SELECT id,name FROM finance_bank WHERE company_id='{$company_id}' AND is_default = 'Y'";
$result = mysql_query($sql,$_mysql_link_);
$def_id  = mysql_result($result,0,0);
$default = mysql_result($result, 0, 1);
$main['def_id']  = $def_id;
$main['default'] = $default;

if(!empty($_GET['id'])){
	$id = replace_safe($_GET['id']);
	$sql = "SELECT id,name,`number`,body,balance,type,action_date,is_default FROM finance_bank WHERE company_id = '$company_id' AND status = 'Y' AND id = '$id'";
	$result = mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($result)<1){
		illegal_operation();
	}
	$finance = mysql_fetch_object($result);
	$main['id']			=	$finance->id;
	$main['name']		=	$finance->name;
	$main['body']		=	$finance->body;
	$main['number']		=	$finance->number;
	$main['balance']	= 	$finance->balance;
	$tmp				=	"store_type_".$finance->type;
	$main[$tmp]			=	" selected";
	$def				=	"store_type_".$finance->is_default;
	$main[$def]			=	"checked";
}

$defaultInfo 		= array();
$defaultInfo['Y'] 	= "是";
$defaultInfo['N'] 	= "否";
$typ 				= array();
$typ['Cashier']		= "出纳账户";
$typ['Company']		= '公司账户';
$typ['Special'] 	= '特殊账户';
$typ['Secret']		= '私密账户';
if(isset($_POST['id'])){
	$id 		= $_POST['id'];
	$name 		= replace_safe($_POST['name']);
	$type 		= replace_safe($_POST['type']);
	$number 	= replace_safe($_POST['number']);
	$body 		= replace_safe($_POST['body']);
	$is_default = replace_safe($_POST['is_default']);
	if(!isset($defaultInfo[$is_default]))
	{
		$is_default = 'N';
	}
	if(!isset($typ[$type]))
	{
		$type 		= 'Cashier';
	}
	if($is_default=='Y'){
	$sql = "UPDATE finance_bank SET is_default='N' WHERE company_id='$company_id' AND id!='$id'";
	mysql_query($sql, $_mysql_link_);
	$sql = "UPDATE finance_bank SET is_default='Y' WHERE company_id='$company_id' AND id='$id'";
	mysql_query($sql, $_mysql_link_);
	}
	if($is_default=='N' && $id==$main['def_id'])
	{
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>修改失败！<br/><br/><br/><br/></center>";
		exit;
	}
	$sql = "UPDATE finance_bank SET name='$name',type='$type',`number`='$number',body='$body',action_date=NOW(),is_default='$is_default' WHERE id='$id'";
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