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

	//关闭订单
	if(!empty($_GET['audit'])){
		$order_id = replace_safe(rtrim($_GET['audit'],","));
		$sql = "UPDATE order_info SET is_delete='Y' WHERE company_id='$company_id' AND id IN ($order_id)";
		mysql_query($sql,$_mysql_link_);
		$iid = mysql_affected_rows($_mysql_link_);
		if($iid){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}
	//恢复正常
	if(!empty($_GET['back_id'])){
		$order_id = replace_safe(rtrim($_GET['back_id'],","));
		$sql = "UPDATE order_info SET unusual_id='0' WHERE company_id='$company_id' AND id IN ($order_id)";
		mysql_query($sql,$_mysql_link_);
		$iid = mysql_affected_rows($_mysql_link_);
		if($iid){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}


	//---- 设置查询条件: ----
	$addon	= array();
	$addon[]	= "order_info.company_id='".$_SESSION['_application_info_']['company_id']."'";
	$addon[]	= "order_info.unusual_id <> '0'";
	$addon[]	= "order_info.is_delete='N'";


	if(!empty($_GET['find']))
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

	$sql = "SELECT COUNT(*) as total FROM order_info
		LEFT JOIN order_source ON order_info.id = order_source.id
		LEFT JOIN order_receiver ON order_info.id = order_receiver.id ".$where;
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
		order_info.id,
		order_source.order_text,order_source.customer_text,order_source.user_id,order_source.bind_number,
		order_receiver.name,order_receiver.mobile,order_receiver.address
		FROM  order_info
		LEFT JOIN order_source ON order_info.id = order_source.id
		LEFT JOIN order_delivery ON order_info.id = order_delivery.id
		LEFT JOIN order_receiver ON order_info.id = order_receiver.id ".$where." ORDER BY order_info.id DESC LIMIT ".$limit;
		$result	= mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result))
		{
			$list_audit	= array();

			$sql     = "SELECT store_id,sum(total) AS total FROM order_product WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result1 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result1)){
				//仓库
				$sql1 = "SELECT name FROM store_info WHERE id = '$res->store_id' AND store_status = 'Normal' AND company_id = '$company_id'";
				$result1 = mysql_query($sql1,$_mysql_link_);
				while($store = mysql_fetch_assoc($result1)){
					$list_audit['store_id']		    = $store['name'];
				}
				$list_audit['total']				= $res->total;
			}

			$sql     = "SELECT express_id FROM order_express_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				//快递
				$sql     = "SELECT name FROM company_express_info WHERE express_id = '$res->express_id'  AND company_id = '$company_id'";
				$result3 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result3)){
					$list_audit['express_id']	= $store['name'];
				}
			}

			//订单状态
			$sql	 = "SELECT is_audit, unusual_id, status FROM order_info WHERE id = '$StoreInfo->id'";
			$result5 = mysql_query($sql,$_mysql_link_);
			while($store = mysql_fetch_object($result5)){
				$arr['is_audit']    = $store->is_audit;
				$arr['unusual_id']  = $store->unusual_id;
				$arr['status']      = $store->status;
			}
			if($arr['is_audit'] == "N"){
				$list_audit['audit'] = "订单审核";
			}else{
				switch($arr['status']){
					case N:
						$list_audit['audit'] = "打单配货";break;
					case F:
						$list_audit['audit'] = "条码验货";break;
					case I:
						$list_audit['audit'] = "称重计费";break;
					case W:
						$list_audit['audit'] = "待发货";break;
					case S:
						$list_audit['audit'] = "已发货";break;
					default:$list_audit['audit'] = "";
				}
			}

			//店铺名字
			$sql4 = "SELECT shop_name FROM user_register_info WHERE id = '$StoreInfo->user_id'";
			$result1 = mysql_query($sql4,$_mysql_link_);
			while($store = mysql_fetch_assoc($result1)){
				$list_audit['shop_name']		    = $store['shop_name'];
			}

			$list_audit['id']			        = $StoreInfo->id;
			$list_audit['bind_number']			= $StoreInfo->bind_number;
			$list_audit['order_text']			= $StoreInfo->order_text;
			$list_audit['customer_text']		= $StoreInfo->customer_text;
			$list_audit['name']					= $StoreInfo->name;
			$list_audit['mobile']				= $StoreInfo->mobile;
			$list_audit['address']				= $StoreInfo->address;
			$xtpl->assign("list_audit", $list_audit);
			$xtpl->parse("main.list_audit");
		}
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");