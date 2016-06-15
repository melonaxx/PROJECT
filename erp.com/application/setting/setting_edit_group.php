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
$id		= intval($_REQUEST['id']);

//---- 公司的部门 ----
$GroupInfo	= array();
$GroupList	= array();
$ThisGroup	= stdClass;
$sql	= "SELECT id, name, parent_id, sort, level, is_valid, body FROM company_staff_group WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' ORDER BY parent_id, sort";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow=mysql_fetch_object($result))
{
	if($dbRow->id == $id)
	{
		$ThisGroup	= $dbRow;
	}
	$GroupInfo[$dbRow->id]['name']		= $dbRow->name;
	$GroupInfo[$dbRow->id]['level']		= $dbRow->level;
	$GroupInfo[$dbRow->id]['parent_id']	= $dbRow->parent_id;
	$GroupInfo[$dbRow->id]['sort']		= $dbRow->sort;
	$GroupList[$dbRow->parent_id][$dbRow->id]	= $dbRow->name;
}

if(!isset($ThisGroup->id))
{
	echo "<br/><br/><br/><br/><br/><br/><center>暂无内容</center><br/><br/><br/><br/>";
	exit;
}

$permit_change	= "Y";
//---- 显示公司的部门 ----
$GroupList	= get_sort_by_array($GroupList);
foreach($GroupList as $ix => $dat)
{
	$gid	= $dat['id'];
	if($gid == $id)
	{
		//---- 不显示当前部门 ----
		continue;
	}
	$list_group			= array();
	$list_group['id']	= $gid;
	$list_group['name']	= str_repeat("　", $dat['level'] - 1).$dat['name'];
	if($ThisGroup->parent_id == $gid)
	{
		$list_group['sel']	= " selected";
	}
	$xtpl->assign("list_group", $list_group);
	$xtpl->parse("main.list_group");
	if($dat['parent_id'] == $id)
	{
		//---- 当前部门含有下属部门, 不允许变更上级部门 ----
		$permit_change	= "N";
	}
}

if(!empty($_POST['name']))
{
	$name	= trim($_POST['name'], "-");
	$name	= replace_safe($name);
	$body	= replace_safe($_POST['content']);
	//---- 选择了停用帐号 则有效性为 N ----
	$is_valid	= ($_POST['is_stop'] == "Y") ? "N" : "Y";
	$parent_id	= intval($_POST['parent_id']);
	$level		= $ThisGroup->level;
	$sort		= $ThisGroup->sort;
	if(!isset($GroupInfo[$parent_id]['name']))
	{
		$parent_id	= 0;
		if($permit_change == "N")
		{
			//---- 不允许变更 ----
			$parent_id	= $ThisGroup->parent_id;
		}
	}
	else
	{
		//---- 属于某个部门的下属 ----
		if($ThisGroup->parent_id <> $parent_id)
		{
			//---- 变更了所属部门 ----
			if($permit_change == "Y")
			{
				//---- 允许变更 ----
				$level	= $GroupInfo[$parent_id]['level'] + 1;
				foreach($GroupInfo as $pid => $dat)
				{
					if($pid == $parent_id)
					{
						$sort	= $dat['sort'] + 1;
					}
				}
			}
			else
			{
				//---- 不允许变更 ----
				$parent_id	= $ThisGroup->parent_id;
			}
		}
	}

	$sql	= "UPDATE company_staff_group SET name='$name', is_valid='$is_valid', parent_id='$parent_id', level='$level', sort='$sort', body='$body' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}


$main['id']			= $id;
$main['name']		= $ThisGroup->name;
$main['content']	= $ThisGroup->body;

if($ThisGroup->is_valid == "N")
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

if($permit_change == "N")
{
	$main['permit_change']	= " disabled";
	$xtpl->parse("main.disable_change");
}

if(isset($_SESSION["error"]))
{
	$main['error']	= "<br/><font color='red'>".$_SESSION["error"]."</font><br/>";
	unset($_SESSION["error"]);
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
