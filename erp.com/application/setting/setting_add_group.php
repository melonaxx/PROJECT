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
$sql	= "SELECT id, name, parent_id, level, sort FROM company_staff_group WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' ORDER BY parent_id, sort";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow=mysql_fetch_object($result))
{
	$GroupInfo[$dbRow->id]['name']	= $dbRow->name;
	$GroupInfo[$dbRow->id]['level']	= $dbRow->level;
	$GroupInfo[$dbRow->id]['sort']	= $dbRow->sort;
	$GroupList[$dbRow->parent_id][$dbRow->id]	= $dbRow->name;
}

if(!empty($_POST['name']))
{
	$name	= trim($_POST['name'], "-");
	$name	= replace_safe($name);
	$body	= replace_safe($_POST['content']);
	//---- 选择了停用帐号 则有效性为 N ----
	$is_valid	= ($_POST['is_stop'] == "Y") ? "N" : "Y";
	$parent_id	= intval($_POST['parent_id']);
	$level		= 1;
	if(!isset($GroupInfo[$parent_id]['name']))
	{
		$parent_id	= 0;
	}
	else
	{
		//---- 属于某个部门的下属 ----
		$level	= $GroupInfo[$parent_id]['level'] + 1;
		$sort	= 1;
		foreach($GroupInfo as $pid => $dat)
		{
			if($pid == $parent_id)
			{
				$sort	= $dat['sort'] + 1;
			}
		}
	}
	$sql	= "INSERT INTO company_staff_group SET name='$name', company_id='".$_SESSION['_application_info_']["company_id"]."', is_valid='$is_valid', parent_id='$parent_id', sort='$sort', level='$level', body='$body'";
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
