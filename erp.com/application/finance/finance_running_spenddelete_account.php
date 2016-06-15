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

//---- 删除单条数据 ----
$id		= intval($_REQUEST['id']);
$sql	= "SELECT status FROM finance_spending_topic WHERE id='$id' AND company_id='".$_SESSION['_application_info_']['company_id']."'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	echo "<html>\n";
	echo "<head>\n";
	echo "<link type='text/css' rel='stylesheet' href='/style/bootstrap.min.css?ver=".$main['doc_version']."'/>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "该科目不存在<br/><br/>\n";
	echo "<input type='button' class='btn btn-default btn-sm cancel' value='确定' onclick=\"parent.$('#MessageBox').modal('hide')\" />\n";
	echo "</body>\n";
	echo "</html>\n";
	exit;
}

$main['id']	= $id;

if($_POST['delete'] == 1)
{
	header("Content-Type: text/html; charset=UTF-8");
	$id = intval($_POST['id']);
	
	//判断是否有子分类或有商品
	$sql = "SELECT COUNT(*) AS total FROM finance_spending_topic WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' AND parent_id='{$id}' AND status='Y'";
	$result	= mysql_query($sql, $_mysql_link_);
	$total = mysql_result($result,0,'total');
	if ($total > 0) {
		//不能删
		echo "<script>alert('含有子科目，不能删除！');window.location.href='/finance/finance_running_spend_account.php';</script>";
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
	} else {
		//删
		$sql = "UPDATE finance_spending_topic SET status='D' WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' AND id='{$id}'";
		mysql_query($sql, $_mysql_link_);
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
	}
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

