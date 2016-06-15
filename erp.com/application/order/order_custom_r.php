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
	$company_id=$_SESSION['_application_info_']['company_id'];
	//---- 确认情况 ----
	if(!empty($_GET['id'])){
		$id = rtrim(replace_safe($_GET['id']),",");
		$sql = "UPDATE order_custom SET status ='Y' WHERE order_id IN ($id) AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
		$mid = mysql_affected_rows($_mysql_link_);
		if($mid){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}
	//---- 打回订单 ----
	if(!empty($_POST['dahui'])){
		$id = rtrim(replace_safe($_POST['dahui']),",");
		$sql = "UPDATE order_custom SET status ='N' WHERE order_id IN ($id) AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
		$mid = mysql_affected_rows($_mysql_link_);
		if($mid){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//查询条件
	$addon	= array();
	$addon[]	= "order_custom.company_id='".$_SESSION['_application_info_']['company_id']."'";
	$addon[]	= "order_custom.status='R'";
	$addon[]	= "order_info.unusual_id='0'";



	if(!empty($_REQUEST['find']))
	{
		$find	= replace_safe($_REQUEST['find'], 20);
		if(!empty($find))
		{
			$addon[]		= "( INSTR(order_source.bind_number,'$find') OR INSTR(order_receiver.name,'$find') )";
			$main['find']	= $find;
			$page_param		= array();
			$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
		}
	}

	$where  = "";
	if(count($addon) > 0)
	{
		$where	= "WHERE ".implode(" AND ", $addon);
	}

	$sql = "SELECT COUNT(*) as total FROM order_custom
		LEFT JOIN order_source ON order_custom.order_id = order_source.id
		LEFT JOIN order_info ON order_custom.order_id = order_info.id
		LEFT JOIN order_receiver ON order_custom.order_id = order_receiver.id ".$where;
	$result	= mysql_query($sql, $_mysql_link_);
	$main['total']		= mysql_result($result, 0, 'total');

	//---- 处理分页 ----
	if(!is_array($page_param))
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

	//---- 数量大于0 ----
	if($main['total'] > 0)
	{
		$sql = "SELECT
		order_custom.order_id,order_custom.body,
		order_source.order_text,order_source.customer_text,order_source.user_id,order_source.bind_number,
		order_receiver.name,order_receiver.mobile,order_receiver.address
		FROM  order_custom
		LEFT JOIN order_source ON order_custom.order_id = order_source.id
		LEFT JOIN order_info ON order_custom.order_id = order_info.id
		LEFT JOIN order_delivery ON order_custom.order_id = order_delivery.id
		LEFT JOIN order_receiver ON order_custom.order_id = order_receiver.id ".$where."  ORDER BY order_custom.order_id DESC LIMIT ".$limit;
		$result	= mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result))
		{
			$sql     = "SELECT store_id,sum(total) AS total FROM order_product WHERE company_id='$company_id' AND order_id='$StoreInfo->order_id'";
			$result1 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result1)){
				//仓库
				$sql = "SELECT name FROM store_info WHERE id = '$res->store_id' AND store_status = 'Normal' AND company_id = '$company_id'";
				$result5 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result5)){
					$list_audit['store_id']		    = $store['name'];
				}
				$list_audit['total']				= $res->total;
			}

			$sql     = "SELECT express_id FROM order_express_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->order_id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				//快递
				$sql     = "SELECT name FROM company_express_info WHERE express_id = '$res->express_id'  AND company_id = '$company_id'";
				$result3 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result3)){
					$list_audit['express_id']	= $store['name'];
				}
			}


			$sql     = "SELECT shop_name FROM user_register_info WHERE id = '$StoreInfo->user_id'";
			$result4 = mysql_query($sql,$_mysql_link_);
			while($store = mysql_fetch_assoc($result4)){
				$list_audit['shop_name']		    = $store['shop_name'];
			}


			$list_audit['id']			        = $StoreInfo->order_id;
			$list_audit['bind_number']			= $StoreInfo->bind_number;
			$list_audit['order_text']			= $StoreInfo->order_text;
			$list_audit['customer_text']		= $StoreInfo->customer_text;
			$list_audit['name']					= $StoreInfo->name;
			$list_audit['body']					= $StoreInfo->body;

			$list_audit['mobile']				= $StoreInfo->mobile;
			$list_audit['address']				= $StoreInfo->address;
			$xtpl->assign("list_audit", $list_audit);
			$xtpl->parse("main.list_audit");
		}
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");