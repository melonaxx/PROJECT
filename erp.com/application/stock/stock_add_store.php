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
$staff_id   = $_SESSION['_application_info_']['staff_id'];

//判断是否为第一个该公司第一个仓库
$sql = "SELECT name FROM store_info WHERE company_id='$company_id' AND store_status = 'Default'";
$result  = mysql_query($sql,$_mysql_link_);
$default = '';
if(mysql_num_rows($result) == 1)
{
	$default = mysql_result($result,0,0);
}
if(mysql_num_rows($result)==0)
{
	$default = 'No';
}
$main['default'] = $default;

if(!empty($_POST['data']))
{
	$name = replace_safe($_POST['data']);
	$sql = "SELECT id FROM store_info WHERE company_id='$company_id' AND name='$name' AND store_status != 'Delete'";
	$res = mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($res)==0)
	{
		echo 0;
	}else{
		echo 1;
	}
	exit;
}

$typeInfo   			= array();
$typeInfo['Sales'] 		= '销售仓';
$typeInfo['Defective'] 	= '次品仓';
$typeInfo['Customer'] 	= '售后仓';
$typeInfo['Purchase'] 	= '采购仓';
$def = array();
$def['Y'] = '是';
$def['N'] = '否';
if(!empty($_POST['send']))
{
	$ip				= $_SERVER['REMOTE_ADDR'];
	$city_id		= intval($_POST['city_id']);
	$state_id		= intval($_POST['state_id']);
	$body			= replace_safe($_POST['body']);
	$name			= replace_safe($_POST['name']);
	$mobile			= replace_safe($_POST['mobile']);
	$district_id	= intval($_POST['district_id']);
	$number			= replace_safe($_POST['number']);
	$address		= replace_safe($_POST['address']);
	$telphone		= replace_safe($_POST['telphone']);
	$store_type		= replace_safe($_POST['store_type']);
	$is_default		= replace_safe($_POST['is_default']);
	$contact_name	= replace_safe($_POST['contact_name']);
	$store_status 	= '';
	if($default=='No')
	{
		$store_status = 'Default';
	}else if(!empty($is_default)){
		if($is_default=='Y')
		{
			$store_status = 'Default';
		}else if($is_default=='N'){
			$store_status = 'Normal';
		}
		if(!isset($def[$is_default]))
		{
			$store_status = 'Normal';
		}
	}
	if(!isset($typeInfo[$store_type]))
	{
		$store_type = 'Sales';
	}
	if(empty($number))
	{
		$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "store");
	}
	if($store_status == 'Default')
	{
		$sql = "SELECT id FROM store_info WHERE company_id='$company_id' AND store_status = 'Default' ";
		$result = mysql_query($sql,$_mysql_link_);
		if(mysql_num_rows($result)>0)
		{
			$id = mysql_result($result,0,0);
			$sql = "UPDATE store_info SET store_status = 'Normal' WHERE company_id='$company_id' AND id='$id' ";
			mysql_query($sql,$_mysql_link_);
		}
	}
	$sql 	= "SELECT id FROM store_info WHERE company_id='$company_id' AND store_status = 'Delete' AND name='$name' ";
	$res 	= mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($res)>0)
	{
		$New_id 	= mysql_result($res,0,0);
		$sql = "UPDATE store_info SET store_status='$store_status' WHERE company_id='$company_id' AND id='$New_id' ";
		mysql_query($sql,$_mysql_link_);
	}else{
		$sql = "INSERT INTO store_info SET company_id='$company_id', store_type='$store_type', ip='$ip', action_date=NOW(), number='$number', name='$name', contact_name='$contact_name', mobile='$mobile', telphone='$telphone', state_id='$state_id', city_id='$city_id', district_id='$district_id', address='$address', body='$body',staff_id='$staff_id',store_status='$store_status' ";
		mysql_query($sql, $_mysql_link_);
		$New_id = mysql_insert_id($_mysql_link_);

		//为新仓库添加商品
		// $sql = "SELECT id FROM product_info WHERE company_id='$company_id' ";
		// $res = mysql_query($sql,$_mysql_link_);
		// while($dbRow = mysql_fetch_object($res))
		// {
		// 	$sql = "INSERT INTO store_product SET company_id='$company_id',store_id='$New_id',product_id='$dbRow->id' ";
		// 	mysql_query($sql,$_mysql_link_);
		// 	$sql = "INSERT INTO store_related SET company_id='$company_id',store_id='$New_id',product_id='$dbRow->id' ";
		// 	mysql_query($sql,$_mysql_link_);
		// }
	}


	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	// echo "parent.location.replace(parent.location.href);";
	echo "parent.$('#myStore ul').append('<li><div class=\"mySto\" onclick=\"mmm(".$New_id.")\" id=\"S".$New_id."\" >".$name."</div><div class=\"mySto2\"><a href=\"/stock/stock_address_match.php?store_id=".$New_id."\" target=\"_blank\" ><span class=\"setUp\">发货设置</span></a><input type=\"hidden\" name=\"store_id\" value=\"".$New_id."\"><span class=\"myMod\" onclick=\"Store_M(".$New_id.")\" ><img src=\"/images/iconfont-mod.svg\" width=\"16px;\" height=\"32px;\" /></span><span class=\"myDel\" onclick=\"Store_D(".$New_id.")\" ><img src=\"/images/iconfont-del.svg\" width=\"16px;\" height=\"32px;\" /></span></div></li>')";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
