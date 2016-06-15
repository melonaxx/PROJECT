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
	$location_id = intval($_GET['location_id']);
	//通过货位id获取货架id及所属仓库id
	$sql = "SELECT store_id,parent_id FROM store_location WHERE company_id='$company_id' AND id='$location_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$sto_id = mysql_result($result,0,0);
	$shelves_id = mysql_result($result,0,1);
	//判断,如果收到的仓库id 与 收到的货位id所属仓库id不相当 说明传递过来的货位id不属于传递过来的仓库 .禁止添加商品
	if($sto_id != $store_id)
	{
		echo "<script>\n";
		echo "alert('仓库不存在该货位!');";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>请重新添加！<br/><br/><br/><br/></center>";
		exit;
	}
	//通过货架id获取库区id
	$sql = "SELECT parent_id FROM store_location WHERE company_id='$company_id' AND id='$shelves_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$area_id = mysql_result($result,0,0);
}
//搜索商品
if(!empty($_POST['value']))
{
	$value = replace_safe($_POST['value']);
	$bb = explode(",",replace_safe($_POST['bb']));
	$chaxun = array();
	$chaxun[]	= "product_info.company_id = '$company_id'";
	$chaxun[]	= "product_info.is_delete='N'";
	$chaxun[]	= "INSTR(product_info.name,'$value')";
	for($i=0;$i<count($bb);$i++)
	{
		if($bb[$i])
		{
			$chaxun[] = "product_info.id != '".$bb[$i]."'";
		}
	}
	$where  	= "WHERE ".implode(" AND ", $chaxun);
	$sql = "SELECT id,body FROM product_format_value WHERE company_id='$company_id' ";
	$res = mysql_query($sql,$_mysql_link_);
	$format_value = array();
	while($dbRow = mysql_fetch_object($res))
	{
		$format_value[$dbRow->id] = $dbRow->body;
	}

	$sql = "SELECT product_info.id,product_info.name,product_info.image,product_detail.parts_id ,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
		FROM product_info
		LEFT JOIN product_detail on product_info.id = product_detail.id ".$where." LIMIT 15";
	$this = mysql_query($sql,$_mysql_link_);
	$arr = array();
	while($StoreInfo = mysql_fetch_object($this)){
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result);
		$value_1 = $format_value[$StoreInfo->value_id_1];
		$value_2 = $format_value[$StoreInfo->value_id_2];
		$value_3 = $format_value[$StoreInfo->value_id_3];
		$value_4 = $format_value[$StoreInfo->value_id_4];
		$value_5 = $format_value[$StoreInfo->value_id_5];
		$format  = ",".$value_1.",".$value_2.",".$value_3.",".$value_4.",".$value_5;
		$arr[] = array(
		'name' 		     => $StoreInfo->name,
		'id'   		     => $StoreInfo->id,
		'image' 		 => $StoreInfo->image,
		'part_name'	   	 => trim($res->name,' '),
		'format'  		 => rtrim($format,',')
		);
	}
	echo json_encode($arr);
	exit;
}
//商品改变
if(!empty($_POST['guige'])){
	$guige = replace_safe($_POST['guige']);
	$sql = "SELECT product_info.id,product_info.name,product_info.image,product_detail.parts_id FROM product_info LEFT JOIN product_detail ON product_info.id = product_detail.id WHERE product_info.id= '$guige' AND product_info.company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$arr = array();
	while($StoreInfo = mysql_fetch_object($result)){
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result2 = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result2);
		$arr['id'] 				= $StoreInfo->id;
		$arr['name'] 			= $StoreInfo->name;
		$arr['image'] 			= $StoreInfo->image;
		$arr['unit'] 			= $res->name;
	}
	echo json_encode($arr);
	exit;
}

if($_POST['send'])
{
	$product_arr = $_POST['product_id'];
	for($i=0;$i<count($product_arr);$i++)
	{
		$product_id = intval($product_arr[$i]);
		$sql = "SELECT id FROM store_related WHERE company_id='$company_id' AND store_id='$store_id' AND location_id='$location_id' AND product_id='$product_id' ";
		$result = mysql_query($sql,$_mysql_link_);
		if(mysql_num_rows($result)==0)
		{
			$sql = "INSERT INTO store_related SET company_id='$company_id',store_id='$store_id',area_id='$area_id',shelves_id='$shelves_id',location_id='$location_id',product_id='$product_id' ";
			mysql_query($sql,$_mysql_link_);
		}

	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");