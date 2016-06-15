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
$sql	= "SELECT name FROM product_parts_name WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	illegal_operation();
	exit;
}
$main['id']		= $id;
$main['name']	= mysql_result($result, 0, 'name');

if(!empty($_POST['name']))
{
	$name	= replace_safe($_POST['name']);
	//---- 查询数据库中是否已经存在 ----
	$sql	= "SELECT id, is_delete FROM product_parts_name WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND name='$name'";
	$result	= mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($result))
	{
		$old_id		= mysql_result($result, 0, 'id');
		$is_delete	= mysql_result($result, 0, 'is_delete');
		if($old_id == $id)
		{
			//---- 是当前ID 名称没有改变 不需要操作 ----
		}
		else
		{
			//---- 其他ID 有同名现象 ----
			if($is_delete == 'Y')
			{
				//---- 如果已经删除 则交换 当前名称和已经删除的名称 ----
				$old_name	= replace_safe($main['name']);
				$sql	= "UPDATE product_parts_name SET name='".time()."' WHERE id='$old_id'";
				mysql_query($sql,$_mysql_link_);

				$sql	= "UPDATE product_parts_name SET name='$name' WHERE id='$id'";
				mysql_query($sql,$_mysql_link_);

				$sql	= "UPDATE product_parts_name SET name='$old_name' WHERE id='$old_id'";
				mysql_query($sql,$_mysql_link_);
			}
			else
			{
				//---- 修改失败 其他同名的属性正在使用中 ----
			}
		}
	}
	else
	{
		//---- 不存在同名 则直接修改名称 ----
		$sql	= "UPDATE product_parts_name SET name='$name' WHERE id='$id'";
		mysql_query($sql,$_mysql_link_);
	}

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

