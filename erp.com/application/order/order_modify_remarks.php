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


$company_id=$_SESSION['_application_info_']['company_id'];

	$order_id = rtrim(replace_safe($_REQUEST['id']),",");
	if(isset($_POST['submit'])){
		$arr = explode(",",$order_id);
		$content = replace_safe($_POST['content']);
		if(intval($_POST['level'])){
			for($i=0;$i<count($arr);$i++){
				$sql = "UPDATE order_source SET customer_text = concat(customer_text,'$content') WHERE id='$arr[$i]' AND company_id = '$company_id'";
				mysql_query($sql,$_mysql_link_);echo "<script>\n";
				echo "parent.$('#MessageBox').modal('hide');\n";
				echo "parent.location.replace(parent.location.href);";
				echo "</script>\n";
				echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
			}
		}else{
			for($i=0;$i<count($arr);$i++){
				$sql = "UPDATE order_source SET customer_text = '$content' WHERE id='$arr[$i]' AND company_id = '$company_id'";
				mysql_query($sql,$_mysql_link_);
				echo "<script>\n";
				echo "parent.$('#MessageBox').modal('hide');\n";
				echo "parent.location.replace(parent.location.href);";
				echo "</script>\n";
				echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
			}
		}
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");