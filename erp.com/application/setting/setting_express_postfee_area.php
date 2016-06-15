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


$sql = "SELECT number,name FROM  main_identity_card WHERE level=1";
$result = mysql_query($sql,$_mysql_link_);
while($provinceInfo = mysql_fetch_object($result)){
	$provinceList = array();
	$provinceList['id'] = $provinceInfo->number;
	$provinceList['name'] = $provinceInfo->name;
	
	$sql2 = "SELECT number,name FROM main_identity_card WHERE parent='$provinceInfo->number'";
	$result2 = mysql_query($sql2,$_mysql_link_);
	while($cityInfo = mysql_fetch_object($result2)){
		$cityList = array();
		$cityList['id'] 	= $cityInfo->number;
		$cityList['name'] 	= $cityInfo->name;

		$xtpl->assign("cityList", $cityList);
		$xtpl->parse("main.provinceList.cityList");
	}
	$xtpl->assign("provinceList", $provinceList);
	$xtpl->parse("main.provinceList");	
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");