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

if(!empty($_REQUEST['box'])){
	$box   =  $_REQUEST['box'];
	$ware  =  replace_safe($_REQUEST['ware']);
}

if(!empty($_REQUEST['made'])){
	$box		 	= explode(',',$box);
	$product_id		= array();
	for($i=0;$i<count($box);$i++){
		$product_id	=	intval($box[$i]);
		//修改上限值；
		$sql = "UPDATE store_product SET `upper` = -1 WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$product_id' ";
		mysql_query($sql,$_mysql_link_);
		//查看是否上下限都处于未设置状态，是在预警状态设置为未设置预警N
		$sql = "SELECT lower,upper FROM store_product WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$product_id' ";
		$res = mysql_query($sql,$_mysql_link_);
		$lower = mysql_result($res,0,0);
		$upper = mysql_result($res,0,1);
		if($lower==-1 && $upper==-1)
		{
			$sql = "UPDATE store_product SET is_warning='N' WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$product_id' ";
			mysql_query($sql,$_mysql_link_);
		}
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>设置成功！<br/><br/><br/><br/></center>";
	exit;
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
