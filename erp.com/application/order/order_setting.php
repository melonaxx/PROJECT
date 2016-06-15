<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/xml; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";


$company_id = $_SESSION['_application_info_']['company_id'];

	$sql = "SELECT id,name FROM company_unusual WHERE company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$num = 1;
	while($res = mysql_fetch_object($result)){
		$arr = array();
		$arr['num']  = $num++;
		$arr['name'] = $res->name;
		$arr['id']   = $res->id;
		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");
	}

	if(!empty($_GET['id'])){
		$id = intval($_GET['id']);
		$sql = "DELETE FROM company_unusual WHERE id='$id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
		$mid = mysql_affected_rows($_mysql_link_);
		if($mid){
			header("Location: order_setting.php");
			exit;
		}
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

