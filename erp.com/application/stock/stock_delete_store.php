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
//---- 删除单条数据 ----
$id		= intval($_REQUEST['id']);
//获取默认仓库id
$sql 	= "SELECT id FROM store_info WHERE company_id='$company_id' AND store_status='Default'";
$res    = mysql_query($sql,$_mysql_link_);
$def_id = mysql_result($res,0,0);
//查看删除仓库id是否存在
$sql	= "SELECT store_status FROM store_info WHERE id='$id' AND company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	echo "<html>\n";
	echo "<head>\n";
	echo "<link type='text/css' rel='stylesheet' href='/style/bootstrap.min.css?ver=".$main['doc_version']."'/>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "该仓库不存在<br/><br/>\n";
	echo "<input type='button' class='btn btn-default btn-sm cancel' value='确定' onclick=\"parent.$('#MessageBox').modal('hide')\" />\n";
	echo "</body>\n";
	echo "</html>\n";
	exit;
}

$main['id']	= $id;

if($_POST['delete'] == 1)
{
	$sql  	= "SELECT count(id) AS total FROM store_location WHERE company_id = '$company_id' AND store_id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$total  = mysql_result($result,0,'total');
	if($total >0 ){
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		// echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<script>alert('设有库位，不能删除！');</script>";
		echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
		exit;
	}
	$sql 	= "SELECT id FROM store_product WHERE company_id='$company_id' AND store_id='$id' AND total_real>0 ";
	$res 	= mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($res)>0)
	{
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		// echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<script>alert('仓库中有商品，不能删除！');</script>";
		echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
		exit;
	}
	if($id==$def_id)
	{
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		// echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<script>alert('默认仓库，禁止删除！');</script>";
		echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
		exit;
	}
	$sql	= "UPDATE store_info SET store_status='Delete' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.$('#S".$id."').parent().remove();";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

