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
if(!empty($_GET['store_id']))
{
	$store_id = intval($_GET['store_id']);
	//获取该仓库可发货地区列表
	$sql = "SELECT city_id FROM store_address WHERE company_id='$company_id' AND store_id='$store_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$city = array();
	while($dbRow = mysql_fetch_object($result))
	{

		// $city .= $dbRow->city_id.',';
		$city[] = $dbRow->city_id;
	}
	// $main['city'] = $city;
	//获取所有城市列表
	$sql = "SELECT number,name FROM  main_identity_card WHERE level=1";
	$result = mysql_query($sql,$_mysql_link_);

	while($provinceInfo = mysql_fetch_object($result)){
		$provinceList = array();
		$provinceList['id'] = $provinceInfo->number;
		$provinceList['name'] = $provinceInfo->name;

		$sql2 = "SELECT number,name FROM main_identity_card WHERE parent='$provinceInfo->number'";
		$result2 = mysql_query($sql2,$_mysql_link_);
		$num 	= 0;//已设置为可发市的数量
		$total 	= array();//该省所包括市的数量
		while($cityInfo = mysql_fetch_object($result2)){
			$cityList = array();
			$cityList['id'] 	= $cityInfo->number;
			$cityList['name'] 	= $cityInfo->name;
			$total[] = $cityInfo->number;
			//判断如果该市在可发地区数组内，则默认选中
			if(in_array($cityInfo->number,$city)){
				$cityList['checked'] = 'checked';
				$num++;
			}else{
				$cityList['checked'] = '';
			}
			$xtpl->assign("cityList", $cityList);
			$xtpl->parse("main.provinceList.cityList");
		}
		//判断如果该省拥有的市数量与设置可发市数量相等，设置该省默认选中
		if(count($total) == $num)
		{
			$provinceList['checked'] = 'checked';
		}else{
			$provinceList['checked'] = '';
		}
		$xtpl->assign("provinceList", $provinceList);
		$xtpl->parse("main.provinceList");
	}

}


if(!empty($_POST))
{
	$shi = $_POST['shi'];
	if(count($shi)>0)
	{
		$sql = "DELETE FROM store_address WHERE company_id='$company_id' AND store_id='$store_id' ";
		mysql_query($sql,$_mysql_link_);
		for($i=0;$i<count($shi);$i++)
		{
			$city_id = intval($shi[$i]);
			$sql = "INSERT INTO store_address SET company_id='$company_id',store_id='$store_id',city_id='$city_id' ";
			mysql_query($sql,$_mysql_link_);
		}
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>打回完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");