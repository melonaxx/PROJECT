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
//---- 科目 ----
$GroupInfo	= array();
$GroupList	= array();
$ThisGroup	= stdClass;
$sql = "SELECT id,name,level,sort,parent_id FROM finance_spending_topic WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' AND status='Y' ORDER BY parent_id, sort";
$result		= mysql_query($sql, $_mysql_link_);

while($dbRow=mysql_fetch_object($result))
{
	if($dbRow->id == $id)
	{
		$ThisGroup	= $dbRow;
	}
	$GroupInfo[$dbRow->id]['name']				= $dbRow->name;
	$GroupInfo[$dbRow->id]['level']				= $dbRow->level;
	$GroupInfo[$dbRow->id]['parent_id']			= $dbRow->parent_id;
	$GroupInfo[$dbRow->id]['sort']				= $dbRow->sort;
	$GroupList[$dbRow->parent_id][$dbRow->id]	= $dbRow->name;
}
if(!isset($ThisGroup->id))
{
	echo "<br/><br/><br/><br/><br/><br/><center>暂无内容</center><br/><br/><br/><br/>";
	exit;
}
$permit_change	= "Y";
$GroupList	= get_sort_by_array($GroupList);
foreach($GroupList as $ix => $dat)
{
	$gid	= $dat['id'];
	if($gid == $id)
	{
		//---- 不显示当前科目 ----
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
		//---- 当前科目含有子科目, 不允许变更上级科目 ----
		$permit_change	= "N";
	}
}

$main['id']			= $id;
$main['name']		= $ThisGroup->name;
$main['content']	= $ThisGroup->body;

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

if(!empty($_POST['name']))
{
	$name	= trim($_POST['name'], "-");
	$name	= replace_safe($name);
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
	}else{
		//---- 属于某个科目的子科目 ----
		if($ThisGroup->parent_id <> $parent_id)
		{
			//---- 变更了所属科目 ----
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
	$sql	= "UPDATE finance_spending_topic SET name='$name', parent_id='$parent_id', level='$level', sort='$sort' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);
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
