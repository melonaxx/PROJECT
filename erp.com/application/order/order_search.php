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

	$sql = "SELECT COUNT(*) as total FROM order_info WHERE company_id='$company_id'";
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
		order_source.id,order_source.order_text,order_source.customer_text,order_source.user_id,order_source.bind_number
		FROM  order_source WHERE order_source.company_id='$company_id' ORDER BY order_source.id DESC LIMIT ".$limit;
		$result	= mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result))
		{
			$list_audit	= array();
			$sql = "SELECT order_receiver.name,order_receiver.mobile,order_receiver.address,
			order_express_paper.express_id FROM order_receiver
			LEFT JOIN order_express_paper ON order_receiver.id = order_express_paper.order_id WHERE order_receiver.company_id='$company_id' AND order_receiver.id='$StoreInfo->id'";
			$result1 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result1)){

				//快递公司
				$sql = "SELECT name FROM company_express_info WHERE express_id = '$res->express_id'  AND company_id = '$company_id'";
				$result2 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result2)){
					$list_audit['express_id']		    = $store['name'];
				}

				$list_audit['name']					= $res->name;
				$list_audit['mobile']				= $res->mobile;
				$list_audit['address']				= $res->address;
			}
			//快递单的状态
			$psql = "SELECT express_id,number,print_status FROM order_express_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$presult = mysql_query($psql,$_mysql_link_);
			while($pres = mysql_fetch_object($presult)){
				if($pres->print_status == 'Y')
				{
					$list_audit['mark_express'] = "快 ";
				}else{
					$list_audit['mark_express'] = "";
				}
			}
			//发货单的状态
			$sql     = "SELECT print_status FROM order_deliver_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				if($res->print_status == 'Y')
				{
					$list_audit['mark_deliver'] = "发 ";
				}else{
					$list_audit['mark_deliver'] = "";
				}
			}
			// 配货单的状态
			$sql     = "SELECT print_status FROM order_list_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				if($res->print_status == 'Y')
				{
					$list_audit['mark_order'] = "配 ";
				}else{
					$list_audit['mark_order'] = "";
				}
			}
			//商品数量和发货仓库
			$sql = "SELECT store_id,SUM(total) AS total FROM order_product WHERE order_id = '$StoreInfo->id' AND company_id='$company_id'";
			$this = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($this)){
				$sql = "SELECT name FROM store_info WHERE id = '$res->store_id' AND company_id = '$company_id'";
				$result3 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result3)){
					$list_audit['store_name']		    = $store['name'];
				}
				$list_audit['total']    = $res->total;
			}




			//店铺名字
			$sql     = "SELECT shop_name FROM user_register_info WHERE id = '$StoreInfo->user_id'";
			$result4 = mysql_query($sql,$_mysql_link_);
			while($store = mysql_fetch_assoc($result4)){
				$list_audit['shop_name'] = $store['shop_name'];
			}

			//订单状态
			$sql	 = "SELECT is_audit, unusual_id, status FROM order_info WHERE id = '$StoreInfo->id'";
			$result5 = mysql_query($sql,$_mysql_link_);
			while($store = mysql_fetch_object($result5)){
				$arr['is_audit']    = $store->is_audit;
				$arr['unusual_id']  = $store->unusual_id;
				$arr['status']      = $store->status;
			}
			if($arr['unusual_id'] != '0'){
				$list_audit['audit'] = "异常订单";
			}else{
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
			}


			$list_audit['id']			        = $StoreInfo->id;
			$list_audit['bind_number']			= $StoreInfo->bind_number;
			$list_audit['customer_text']		= $StoreInfo->customer_text;

			$xtpl->assign("list_audit", $list_audit);
			$xtpl->parse("main.list_audit");
		}
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
