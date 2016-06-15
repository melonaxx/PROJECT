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


//---- 收入科目 ----
$GroupInfo	= array();
$GroupList	= array();
$sql = "SELECT id,name,level,sort,parent_id FROM finance_income_topic WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' AND status='Y' ORDER BY parent_id, sort";
$result	= mysql_query($sql, $_mysql_link_);

while($dbRow=mysql_fetch_object($result))
{
	$GroupInfo[$dbRow->id]['name']	= $dbRow->name;
	$GroupInfo[$dbRow->id]['level']	= $dbRow->level;
	$GroupInfo[$dbRow->id]['sort']	= $dbRow->sort;
	$GroupList[$dbRow->parent_id][$dbRow->id] = $dbRow->name;
}

$GroupList	= get_sort_by_array($GroupList);
foreach($GroupList as $ix => $dat)
{
	$list_group		= array();
	$list_group['id']	= $dat['id'];
	$list_group['name']	= str_repeat("　", $dat['level'] - 1).$dat['name'];
	$xtpl->assign("list_group", $list_group);
	$xtpl->parse("main.list_group");
}

if(!empty($_POST['name']))
{
	$name	= trim($_POST['name'], "-");
	$name	= replace_safe($name);
	$parent_id	= intval($_POST['parent_id']);
	$level		= 1;
	if(!isset($GroupInfo[$parent_id]['name']))
	{
		$parent_id	= 0;
	}else{
		//---- 属于某个科目的子科目 ----
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
	$sql	= "INSERT INTO finance_income_topic SET name='$name', company_id='".$_SESSION['_application_info_']["company_id"]."',sort='$sort', level='$level',  parent_id='$parent_id'";
	mysql_query($sql, $_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}


if(isset($_SESSION["error"]))
{
	$main['error']	= "<br/><font color='red'>".$_SESSION["error"]."</font><br/>";
	unset($_SESSION["error"]);
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
