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
	illegal_operation();
	exit;
}
$id		= intval($_REQUEST['id']);
$sql	= "SELECT group_id, role_id, is_admin, name, passwd, mobile, number, nick, register_date, ip, is_valid, body FROM company_staff_info WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' AND id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	echo "<br/><br/><br/><br/><br/><br/><center>暂无内容</center><br/><br/><br/><br/>";
	exit;
}
$StaffInfo	= mysql_fetch_object($result);

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

if(!empty($_POST['name']))
{
	$addon	= "";
	$name	= replace_safe($_POST['name']);
	$pass	= stripslashes($_POST['password']);
	if(strlen($name) < 3)
	{
		$_SESSION['error']	= "登录账号太短，请设置3-20位的登录账号";
		header("Location: /setting/setting_edit_staff.php?id=".$id);
		exit;
	}
	if(strlen($pass) > 0)
	{
		//---- 管理员重新设置了密码 ----
		if(strlen($pass) < 5)
		{
			$_SESSION['error']	= "密码太短，请设置6-20位的密码";
			header("Location: /setting/setting_edit_staff.php?id=".$id);
			exit;
		}
		$passwd	= md5($pass);
		$addon	= ", passwd='$passwd'";
	}
	//---- 选择了停用帐号 则有效性为 N ----
	$is_valid	= ($_POST['is_stop'] == "Y") ? "N" : "Y";
	$nick		= replace_safe($_POST['nick']);
	$mobile		= replace_safe($_POST['mobile']);
	$number		= replace_safe($_POST['number']);
	$body		= replace_safe($_POST['content']);
	$group_id	= intval($group_id);
	if(!isset($GroupInfo[$group_id]))
	{
		//---- 无此部门 ----
		$group_id	= 0;
	}
	if($number == "")
	{
		//---- 没有编号, 采用自动编号 ----
		$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "staff");
	}
	$sql	= "UPDATE company_staff_info SET name='$name', is_valid='$is_valid', nick='$nick', mobile='$mobile', number='$number', group_id='$group_id', body='$body' $addon WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}


$main['id']			= $id;
$main['name']		= $StaffInfo->name;
$main['mobile']		= $StaffInfo->mobile;
$main['number']		= $StaffInfo->number;
$main['nick']		= $StaffInfo->nick;
$main['ip']			= $StaffInfo->ip;
$main['date']		= $StaffInfo->register_date;
$main['content']	= $StaffInfo->body;
if($main['last_date'] == "0000-00-00 00:00:00")
{
	$main['last_date']	= "<i class='brief'>暂无</i>";
}

if($StaffInfo->is_valid == "N")
{
	//---- 不是有效帐号，停用 ----
	$main['is_stop']		= "Y";
	$main['is_stop_chk']	= "checked";
	$main['is_stop_box']	= "_ok";
}
else
{
	$main['is_stop']		= "N";
}
if($StaffInfo->is_admin == "Y")
{
	$main['type']	= "管理员";
}
else
{
	$main['type']	= "普通员工";
}

$GroupList	= get_sort_by_array($GroupList);
foreach($GroupList as $ix => $dat)
{
	$list_group			= array();
	$list_group['id']	= $dat['id'];
	$list_group['name']	= str_repeat("　", $dat['level'] - 1).$dat['name'];
	if($dat['id'] == $StaffInfo->group_id)
	{
		$list_group['selected']	= "selected";
	}
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
