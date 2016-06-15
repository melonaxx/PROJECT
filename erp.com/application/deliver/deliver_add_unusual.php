
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

	$company_id = $_SESSION['_application_info_']['company_id'];

	$sql = "SELECT id,name FROM company_unusual WHERE company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($res = mysql_fetch_object($result)){
		$arr = array();
		$arr['id']   = $res->id;
		$arr['name'] = $res->name;
		$xtpl->assign("arr",$arr);
		$xtpl->parse("main.arr");
	}

	$order_id = intval($_GET['id']);
	if(isset($_POST['submit'])){
		$level = intval($_POST['level']);
		$content = replace_safe($_POST['content']);
		$sql = "UPDATE order_info SET unusual_id='$level' WHERE id='$order_id' AND company_id='$company_id' AND is_delete='N'";
		mysql_query($sql,$_mysql_link_);
		$sql = "UPDATE order_source SET customer_text = concat(customer_text,'$content') WHERE id='$order_id' AND company_id = '$company_id'";
		mysql_query($sql,$_mysql_link_);
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");