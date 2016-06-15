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
$addon[] = "i.company_id = '$company_id'";
$addon[] = "i.is_delete = 'N'";
$addon[] = "i.have_combination = 'N'";

//将所有的商品写入product_sales（只执行一次）
// $sql = "select id from product_info where company_id=10000003 and is_delete='N' and have_combination = 'N'";
// $res = mysql_query($sql,$_mysql_link_);
// while($row = mysql_fetch_object($res)){
// 	$ppid = $row->id;
// 	$sql = "INSERT INTO product_sales SET id='$ppid'";
// 	mysql_query($sql,$_mysql_link_);
	
// }

if(isset($_POST['id'])){
	$id				= intval($_POST['id']);
	if($id>0){
		//查询商品是否正在被购买
		$sql = "SELECT order_id FROM order_product WHERE product_id='$id' AND company_id='$company_id'";
		$res = mysql_query($sql, $_mysql_link_);
		$nnm = mysql_num_rows($res);
		if($nnm>0){
			//商品在订单中，是否已完成
			$lid = 0;
			while($rows = mysql_fetch_object($res)){
				$oid = $rows->order_id;
				$sql = "SELECT id FROM order_info WHERE company_id='$company_id' AND id='$oid' AND status != 'S' ";
				$result = mysql_query($sql, $_mysql_link_);
				$muma = mysql_num_rows($result);
				if($muma>0){
					$lid++;
				}
			}
			if($lid>0){
					//商品有未完成订单，不能删除
					die(json_encode("0"));
				}else{
					//商品没有未完成订单可以删除
					$sql ="UPDATE product_info SET is_delete= 'Y' WHERE id = '$id'";
					$result = mysql_query($sql, $_mysql_link_);
					$jieguo =  mysql_affected_rows($_mysql_link_);
					$sql_product ="DELETE FROM product_detail WHERE id = '$id'";
					mysql_query($sql_product,$_mysql_link_);
					$sql_product_attrib = "DELETE FROM product_attrib_list WHERE product_id = '$id'";
					mysql_query($sql_product_attrib,$_mysql_link_);
					$sql_product_accessory = "UPDATE product_accessory SET is_delete = 'Y' WHERE id = '$id'";
					mysql_query($sql_product_accessory,$_mysql_link_);
					$sqll = "DELETE FROM product_related_info WHERE company_id='$company_id' AND product_id='$id'";
					mysql_query($sqll,$_mysql_link_);
					$sqm = "DELETE FROM store_product WHERE company_id='$company_id' AND product_id='$id'";
					mysql_query($sqm,$_mysql_link_);
					$sqa = "DELETE FROM store_related WHERE company_id='$company_id' AND product_id='$id'";
					mysql_query($sqa,$_mysql_link_);
					die(json_encode("1"));
					header('Location: /product/product_list.php');
				}
		}else{
			//不在订单中直接删除
			$sql ="UPDATE product_info SET is_delete= 'Y' WHERE id = '$id'";
			$result = mysql_query($sql, $_mysql_link_);
			$jieguo =  mysql_affected_rows($_mysql_link_);
			$sql_product ="DELETE FROM product_detail WHERE id = '$id'";
			mysql_query($sql_product,$_mysql_link_);
			$sql_product_attrib = "DELETE FROM product_attrib_list WHERE product_id = '$id' company_id='$company_id'";
			mysql_query($sql_product_attrib,$_mysql_link_);
			$sql_product_accessory = "UPDATE product_accessory SET is_delete = 'Y' WHERE id = '$id'";
			mysql_query($sql_product_accessory,$_mysql_link_);
			$sqll = "DELETE FROM product_related_info WHERE company_id='$company_id' AND product_id='$id'";
			mysql_query($sqll,$_mysql_link_);
			$sqm = "DELETE FROM store_product WHERE company_id='$company_id' AND product_id='$id'";
			mysql_query($sqm,$_mysql_link_);
			$sqa = "DELETE FROM store_related WHERE company_id='$company_id' AND product_id='$id'";
			mysql_query($sqa,$_mysql_link_);
			die(json_encode("1"));
			header('Location: /product/product_list.php');

		}	
	}
}

if(isset($_GET['idArr'])){
	$idArr	= replace_safe($_GET['idArr']);
	$arr    = explode(",",$idArr);
	$success = 0;

	for($i=0;$i<count($arr);$i++){
		$sql = "SELECT order_id FROM order_product WHERE product_id='$arr[$i]' AND company_id='$company_id'";
		$res = mysql_query($sql, $_mysql_link_);
		$nnm = mysql_num_rows($res);

		if($nnm>0){
			//该商品在订单中不能删除，是否已完成订单
			$lid = 0;
			while($rows = mysql_fetch_object($res)){
				$oid = $rows->order_id;
				$sql = "SELECT id FROM order_info WHERE company_id='$company_id' AND id='$oid' AND status != 'S' ";
				$result = mysql_query($sql, $_mysql_link_);
				$muma = mysql_num_rows($result);
				if($muma>0){
					$lid++;
				}
			}			

			//如果$lid==0,可以删除
			if($lid == 0){
				$sql ="UPDATE product_info SET is_delete= 'Y' WHERE id = '$arr[$i]'";
				$result = mysql_query($sql, $_mysql_link_);
				$jieguo =  mysql_affected_rows($_mysql_link_);
				$sql_product ="DELETE FROM product_detail WHERE id = '$arr[$i]'";
				mysql_query($sql_product,$_mysql_link_);
				$sql_product_attrib = "DELETE FROM product_attrib_list WHERE product_id = '$arr[$i]' company_id='$company_id'";
				mysql_query($sql_product_attrib,$_mysql_link_);
				$sql_product_accessory = "UPDATE product_accessory SET is_delete = 'Y' WHERE id = '$arr[$i]";
				mysql_query($sql_product_accessory,$_mysql_link_);
				$sqll = "DELETE FROM product_related_info WHERE company_id='$company_id' AND product_id='$arr[$i]'";
				mysql_query($sqll,$_mysql_link_);
				$sqm = "DELETE FROM store_product WHERE company_id='$company_id' AND product_id='$arr[$i]'";
				mysql_query($sqm,$_mysql_link_);
				$sqa = "DELETE FROM store_related WHERE company_id='$company_id' AND product_id='$arr[$i]'";
				mysql_query($sqa,$_mysql_link_);
				$success++;
			}else{
				$fail_id .= $arr[$i].","; 
			}


		}else{
			//该商品不在订单中可以删除
			$sql ="UPDATE product_info SET is_delete= 'Y' WHERE id = '$arr[$i]'";
			$result = mysql_query($sql, $_mysql_link_);
			$jieguo =  mysql_affected_rows($_mysql_link_);
			$sql_product ="DELETE FROM product_detail WHERE id = '$arr[$i]'";
			mysql_query($sql_product,$_mysql_link_);
			$sql_product_attrib = "DELETE FROM product_attrib_list WHERE product_id = '$arr[$i]' company_id='$company_id'";
			mysql_query($sql_product_attrib,$_mysql_link_);
			$sql_product_accessory = "UPDATE product_accessory SET is_delete = 'Y' WHERE id = '$arr[$i]";
			mysql_query($sql_product_accessory,$_mysql_link_);
			$sqll = "DELETE FROM product_related_info WHERE company_id='$company_id' AND product_id='$arr[$i]'";
			mysql_query($sqll,$_mysql_link_);
			$sqm = "DELETE FROM store_product WHERE company_id='$company_id' AND product_id='$arr[$i]'";
			mysql_query($sqm,$_mysql_link_);
			$sqa = "DELETE FROM store_related WHERE company_id='$company_id' AND product_id='$arr[$i]'";
			mysql_query($sqa,$_mysql_link_);
			$success++;

		}

	}
	$fail_id = trim($fail_id,",");
	$resu = array($success,$fail_id);
	echo json_encode($resu);
	exit;
	// header('Location: /product/product_list.php');
	// $sql = "SELECT order_id FROM order_product WHERE product_id IN ($idArr) AND company_id='$company_id'";
	// $res = mysql_query($sql, $_mysql_link_);
	// $nnm = mysql_num_rows($res);	
	// if($nnm>0){
	// 	//订单中有商品
	// 	$lid = 0;
	// 	while($rows = mysql_fetch_object($res)){
	// 		//查询是已完成还是未完成
	// 		$oid = $rows->order_id;
	// 		$sql = "SELECT id FROM order_info WHERE company_id='$company_id' AND id IN ($idArr) AND status !='S'";
	// 		$ress = mysql_query($sql, $_mysql_link_);
	// 		$mla = mysql_num_rows($ress);
	// 		if($mla == 0){
	// 			//可以删除该商品


	// 		}
	// 	}

	// }else{
	// 	//订单中没有全部商品，可以直接删除
	// 	$sql ="UPDATE product_info SET is_delete= 'Y' WHERE id IN ($idArr)";
	// 	$result = mysql_query($sql, $_mysql_link_);
	// 	$jieguo =  mysql_affected_rows($_mysql_link_);
	// 	$sql_product ="DELETE FROM product_detail WHERE id IN ($idArr)";
	// 	mysql_query($sql_product,$_mysql_link_);
	// 	$sql_product_attrib = "DELETE FROM product_attrib_list WHERE product_id IN ($idArr) company_id='$company_id'";
	// 	mysql_query($sql_product_attrib,$_mysql_link_);
	// 	$sql_product_accessory = "UPDATE product_accessory SET is_delete = 'Y' WHERE id IN ($idArr)";
	// 	mysql_query($sql_product_accessory,$_mysql_link_);
	// 	$sqll = "DELETE FROM product_related_info WHERE company_id='$company_id' AND product_id IN ($idArr)";
	// 	mysql_query($sqll,$_mysql_link_);
	// 	$sqm = "DELETE FROM store_product WHERE company_id='$company_id' AND product_id IN ($idArr)";
	// 	mysql_query($sqm,$_mysql_link_);
	// 	$sqa = "DELETE FROM store_related WHERE company_id='$company_id' AND product_id IN ($idArr)";
	// 	mysql_query($sqa,$_mysql_link_);
	// 	header('Location: /product/product_list.php');

	//}




	// $sql	= "UPDATE product_info SET is_delete='Y' WHERE id IN ($idArr)";
	// mysql_query($sql, $_mysql_link_);
	// $sqll = "DELETE FROM product_related_info WHERE company_id='$company_id' AND product_id IN ($idArr)";
	// mysql_query($sqll,$_mysql_link_);
	// header('Location: /product/product_list.php');
	// exit;
}
$SupplierType					= array();
$SupplierType['Virtual']		= "虚拟产品";
$SupplierType['Packaged']		= "套装产品";
$SupplierType['Real']			= "实体产品";
$SupplierType['Materials']		= "原材料";

if(!empty($_REQUEST['find']))
{
	//---- 查询仓库 ----
	$find	= replace_safe($_REQUEST['find'], 20);
	if(!empty($find))
	{
		//---- 设置查询条件: 只允许查询仓库名称或编码 ----
		$addon[]		= "(INSTR(i.name, '$find'))";
		$main['find']	= $find;
		$page_param		= array();
		$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
	}
}


$where = "";
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}

if(!empty($_GET['sales_status']))
{	
	if($_GET['sales_status']=="All"){
		$whe = $where;
	}else{
		$whe = $where." AND s.sales_status='{$_GET['sales_status']}'";
	}

	$type = replace_safe($_REQUEST['sales_status']);
	$main['sell_state']	= $type;
	$page_param['sales_status']		= replace_safe($_REQUEST['sales_status']);
}else{
	$whe = $where." AND s.sales_status='Onsale'";	
}

$sql	= "SELECT COUNT(*) as total FROM product_info AS i LEFT JOIN  product_sales AS s ON i.id=s.id $whe";
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');
//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}


$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];
$sql="SELECT id,name FROM product_format_name";
	$result=mysql_query($sql, $_mysql_link_);
	while($x = mysql_fetch_object($result)) {
		$FormatName[$x->id]	=$x->name;

	}
$sql="SELECT id,body FROM product_format_value";
	$result=mysql_query($sql, $_mysql_link_);
	while ($x = mysql_fetch_object($result)) {
		$FormatValue[$x->id]	=$x->body;

	}



$sqll= "SELECT i.image,i.id,d.format_id_1,d.format_id_2,i.category_id,i.brand_id,d.format_id_3,d.format_id_4,d.format_id_5,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5,d.content,d.price_display,i.name FROM product_detail AS d RIGHT JOIN product_info AS i ON i.id=d.id LEFT JOIN product_sales AS s ON i.id=s.id $whe ORDER BY i.id DESC LIMIT ".$limit;
	$msql=mysql_query($sqll, $_mysql_link_);
	//var_dump($sqll);
	while($SupplierInfo = mysql_fetch_object($msql)){
			$value	= array();
			$value['image']				= 	$SupplierInfo->image;
			$value['weight']			=	$SupplierInfo->weight;
			$value['content']			=	$SupplierInfo->content;
			$value['price_display']		=	$SupplierInfo->price_display;
			$value['price_purchase']	=	$SupplierInfo->price_purchase;
			$value['product_type']		=	$SupplierType[$SupplierInfo->product_type];
			$value['format_1']			=	empty($FormatName[$SupplierInfo->format_id_1])?$FormatName[$SupplierInfo->format_id_1]:$FormatName[$SupplierInfo->format_id_1].":";
			$value['format_2']			=	empty($FormatName[$SupplierInfo->format_id_2])?$FormatName[$SupplierInfo->format_id_2]:$FormatName[$SupplierInfo->format_id_2].":";
			$value['format_3']			=	empty($FormatName[$SupplierInfo->format_id_3])?$FormatName[$SupplierInfo->format_id_3]:$FormatName[$SupplierInfo->format_id_3].":";
			$value['format_4']			=	empty($FormatName[$SupplierInfo->format_id_4])?$FormatName[$SupplierInfo->format_id_4]:$FormatName[$SupplierInfo->format_id_4].":";
			$value['format_5']			=	empty($FormatName[$SupplierInfo->format_id_5])?$FormatName[$SupplierInfo->format_id_5]:$FormatName[$SupplierInfo->format_id_5].":";
			$value['value_1']			=	empty($FormatValue[$SupplierInfo->value_id_1])?$FormatValue[$SupplierInfo->value_id_1]:$FormatValue[$SupplierInfo->value_id_1].";";
			$value['value_2']			=	empty($FormatValue[$SupplierInfo->value_id_2])?$FormatValue[$SupplierInfo->value_id_2]:$FormatValue[$SupplierInfo->value_id_2].";";
			$value['value_3']			=	empty($FormatValue[$SupplierInfo->value_id_3])?$FormatValue[$SupplierInfo->value_id_3]:$FormatValue[$SupplierInfo->value_id_3].";";
			$value['value_4']			=	empty($FormatValue[$SupplierInfo->value_id_4])?$FormatValue[$SupplierInfo->value_id_4]:$FormatValue[$SupplierInfo->value_id_4].";";
			$value['value_5']			=	empty($FormatValue[$SupplierInfo->value_id_5])?$FormatValue[$SupplierInfo->value_id_5]:$FormatValue[$SupplierInfo->value_id_5].";";

			$sql = "SELECT name FROM product_category WHERE id = '{$SupplierInfo->category_id}' AND is_delete = 'N' AND company_id = '$company_id'";
			$result = mysql_query($sql,$_mysql_link_);
			while($CompanyExpress = mysql_fetch_object($result)){
				$value['category_id'] = $CompanyExpress->name;
			}
			$sql = "SELECT name FROM product_brand WHERE id = '{$SupplierInfo->brand_id}' AND is_delete = 'N' AND company_id = '$company_id'";
			$result = mysql_query($sql,$_mysql_link_);
			while($CompanyExpress = mysql_fetch_object($result)){
				$value['brand_id']	= $CompanyExpress->name;
			}
			$value['id']					=	$SupplierInfo->id;
			$value['number']				=	$SupplierInfo->number;
			$value['name']					=	$SupplierInfo->name;
			$value['bar_code']				=	$SupplierInfo->bar_code;
			$xtpl->assign("supplierList", $value);
			$xtpl->parse("main.supplierList");

	}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
