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
		if($is_delete == 'Y')
		{
			//---- 如果已经删除 则恢复其正常状态 ----
			$sql	= "UPDATE product_parts_name SET is_delete='N' WHERE id='$old_id'";
			mysql_query($sql,$_mysql_link_);
		}
	}
	else
	{
		$sql	= "INSERT INTO product_parts_name SET company_id='".$_SESSION['_application_info_']['company_id']."', name='$name'";
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

