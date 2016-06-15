
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
	$company_id=$_SESSION['_application_info_']['company_id'];

	//----- 快递公司 ----
	$express = array();
	$sql = "SELECT express_id,name FROM company_express_info WHERE status = 'Y' AND company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$express['express_id']     = $StoreInfo->express_id;
		$express['name']			= $StoreInfo->name;
		$xtpl->assign("express", $express);
		$xtpl->parse("main.express");
	}
	$order_id = rtrim(replace_safe($_REQUEST['id']),",");
	if(isset($_POST['submit'])){
		$arr = explode(",",$order_id);
		$level = intval($_POST['level']);
		for($i=0;$i<count($arr);$i++){
			$sql = "UPDATE order_express_paper SET express_id = '$level' WHERE order_id='$arr[$i]' AND company_id = '$company_id'";
			mysql_query($sql,$_mysql_link_);
			echo "<script>\n";
			echo "parent.$('#MessageBox').modal('hide');\n";
			echo "parent.location.replace(parent.location.href);";
			echo "</script>\n";
			echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
		}
		
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");




