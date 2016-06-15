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

if(!empty($_REQUEST['id']))
{
	$id		= intval($_REQUEST['id']);
	//查询当前默认仓库
	$sql = "SELECT id,name FROM store_info WHERE company_id='$company_id' AND store_status='Default' ";
	$result = mysql_query($sql,$_mysql_link_);
	// $main['default_id'] 	= mysql_result($result,0,0);
	// $main['default_name']	= mysql_result($result,0,1);
	while($rows = mysql_fetch_object($result)){
		$main['default_id'] 	= $rows->id;
		$main['default_name']	= $rows->name;
	}

	//---- 查询该仓库信息 ----
	$sql	= "SELECT id, store_type, number, name, contact_name, mobile, telphone, state_id, city_id, district_id, address, body FROM store_info WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
	$result	= mysql_query($sql, $_mysql_link_);
	if(mysql_num_rows($result) < 1)
	{
		//---- 仓库不存在 或者 属于其它公司 ----
		illegal_operation();
	}
	$StoreInfo				= mysql_fetch_object($result);
	$main['id']				= $StoreInfo->id;
	$main['number']			= $StoreInfo->number;
	$main['name']			= $StoreInfo->name;
	$main['telphone']		= $StoreInfo->telphone;
	$main['contact_name']	= $StoreInfo->contact_name;
	$main['mobile']			= $StoreInfo->mobile;
	$main['state_id']		= $StoreInfo->state_id;
	$main['city_id']		= $StoreInfo->city_id;
	$main['district_id']	= $StoreInfo->district_id;
	$main['address']		= $StoreInfo->address;
	$main['body']			= $StoreInfo->body;
	$tmp					= "store_type_".$StoreInfo->store_type;
	$main[$tmp]				= " selected";
	}
	$typeInfo   			= array();
	$typeInfo['Sales'] 		= '销售仓';
	$typeInfo['Defective'] 	= '次品仓';
	$typeInfo['Customer'] 	= '售后仓';
	$typeInfo['Purchase'] 	= '采购仓';

if(isset($_POST['made']))
{
	$city_id		= intval($_POST['city_id']);
	$state_id		= intval($_POST['state_id']);
	$body			= replace_safe($_POST['body']);
	$name			= replace_safe($_POST['name']);
	$district_id	= intval($_POST['district_id']);
	$number			= replace_safe($_POST['number']);
	$mobile			= replace_safe($_POST['mobile']);
	$address		= replace_safe($_POST['address']);
	$telphone		= replace_safe($_POST['telphone']);
	$store_type		= replace_safe($_POST['store_type']);
	$is_default 	= replace_safe($_POST['is_default']);
	$contact_name	= replace_safe($_POST['contact_name']);

	if(empty($number))
	{
		//---- 如果没有编码则，自动生成仓库的编码 ----
		$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "store");
	}
	if(!isset($typeInfo[$store_type]))
	{
		$store_type = 'Sales';
	}
	$store_status = '';
	if($is_default=='Y')
	{
		$store_status = 'Default';
	}else{
		$store_status = 'Normal';
	}
	if($is_default=='Y')
	{
		$sql = "SELECT id FROM store_info WHERE company_id='$company_id' AND store_status='Default' ";
		$res = mysql_query($sql,$_mysql_link_);
		$def_id = mysql_result($res,0,0);
		$sql = "UPDATE store_info SET store_status='Normal' WHERE company_id='$company_id' AND id='$def_id' ";
		mysql_query($sql,$_mysql_link_);
	}
	if($is_default=='N' && $id==$main['default_id'])
	{
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>修改失败！<br/><br/><br/><br/></center>";
		exit;
	}
	$sql = "UPDATE store_info SET store_type='$store_type', number='$number', name='$name', contact_name='$contact_name', mobile='$mobile', telphone='$telphone', state_id='$state_id', city_id='$city_id', district_id='$district_id', address='$address', body='$body',store_status='$store_status' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
