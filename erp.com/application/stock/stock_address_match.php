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
if(!empty($_GET['store_id']))
{
	$store_id = intval($_GET['store_id']);
	$main['store_id'] = $store_id;
	//获取城市名称
	$sql 	= "SELECT `number`,name FROM main_identity_card ";
	$res 	= mysql_query($sql,$_mysql_link_);
	$name 	= array();
	while($dbRow = mysql_fetch_object($res))
	{
		$name[$dbRow->number] = $dbRow->name;
	}
	$sql = "SELECT a.city_id,c.parent FROM store_address AS a LEFT JOIN main_identity_card AS c ON a.city_id = c.number WHERE a.company_id='$company_id' AND a.store_id='$store_id' ";
	$result 	 = mysql_query($sql,$_mysql_link_);
	$city_id 	 = array();
	while($dbRow = mysql_fetch_object($result))
	{
		$city_id[$dbRow->parent].=$name[$dbRow->city_id]."，";
	}
	$no = 1;
	$city = array();
	foreach ($city_id as $key => $value) {
		$city['no']    	= $no++;
		$city['sheng'] 	= $name[$key];
		$city['shi'] 	= mb_substr($value,0,-1,'utf-8');
		$xtpl->assign('city',$city);
		$xtpl->parse('main.city');
	}
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
