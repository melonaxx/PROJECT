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

//---- 没有登录 ----
if($_SESSION['_application_info_']["admin_id"] == 0 || $_SESSION['_application_info_']["company_id"] == 0)
{
	echo "<br/><br/><br/><br/><br/><br/><center>您无权访问该页面</center><br/><br/><br/><br/>";
	exit;
}
//---- 公司的部门 ----
$GroupInfo	= array();
$GroupList	= array();
$sql	= "SELECT id, name, parent_id FROM company_staff_group WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' ORDER BY parent_id, sort";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow=mysql_fetch_object($result))
{
	$GroupInfo[$dbRow->id]	= $dbRow->name;
	$GroupList[$dbRow->parent_id][$dbRow->id]	= $dbRow->name;
}

//---- 自动生成员工编号 ----
$main['number']	= get_company_number($_SESSION['_application_info_']["company_id"], "staff");

if(!empty($_POST['name']))
{
	$name	= replace_safe($_POST['name']);
	$pass	= stripslashes($_POST['password']);
	if(strlen($name) < 3)
	{
		$_SESSION['error']	= "登录账号太短，请设置3-20位的登录账号";
		header("Location: /setting/setting_add_staff.php");
		exit;
	}
	if(strlen($pass) < 5)
	{
		$_SESSION['error']	= "密码太短，请设置6-20位的密码";
		header("Location: /setting/setting_add_staff.php");
		exit;
	}
	$passwd	= md5($pass);
	//---- 选择了停用帐号 则有效性为 N ----
	$is_valid	= ($_POST['is_stop'] == "Y") ? "N" : "Y";
	$nick		= replace_safe($_POST['nick']);
	$mobile		= replace_safe($_POST['mobile']);
	$number		= replace_safe($_POST['number']);
	$body		= replace_safe($_POST['content']);
	if($number == $main['number'] || $number == "")
	{
		//---- 采用了自动编号，或者没有编号 ----
		$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "staff");
	}
	$group_id	= intval($group_id);
	if(!isset($GroupInfo[$group_id]))
	{
		//---- 无此部门 ----
		$group_id	= 0;
	}
	$sql	= "INSERT INTO company_staff_info SET name='$name', passwd='$passwd', company_id='".$_SESSION['_application_info_']["company_id"]."', is_valid='$is_valid', nick='$nick', mobile='$mobile', number='$number', group_id='$group_id', register_date=NOW(), body='$body'";
	mysql_query($sql, $_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}
//---- 公司的部门 ----
$GroupList	= get_sort_by_array($GroupList);

foreach($GroupList as $ix => $dat)
{
	$list_group			= array();
	$list_group['id']	= $dat['id'];
	$list_group['name']	= str_repeat("　", $dat['level'] - 1).$dat['name'];
	$xtpl->assign("list_group", $list_group);
	$xtpl->parse("main.list_group");
}

if(isset($_SESSION["error"]))
{
	$main['error']	= "<br/><font color='red'>".$_SESSION["error"]."</font><br/>";
	unset($_SESSION["error"]);
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


