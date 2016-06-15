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

//---- 设置查询条件: ----
$type   = replace_safe($_GET['type']);
	if(!empty($_GET['find']))
	{
		$type   = replace_safe($_GET['type']);
		$find	= replace_safe($_GET['find'], 20);
		if(!empty($find))
		{

			$main['find']	= $find;
			$main['type']   = $type;
			$page_param		= array();
			$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
			$page_param['type']		= replace_safe($_REQUEST['type']);

		}
	}
	if($type == "bian"){
		$sql = "SELECT COUNT(*) as total FROM order_source LEFT JOIN order_info ON order_source.id=order_info.id WHERE order_source.company_id='$company_id' AND order_info.is_delete='N' AND INSTR(order_source.bind_number,'$find')";

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
			FROM  order_source LEFT JOIN order_info ON order_source.id=order_info.id WHERE order_source.company_id='$company_id' AND order_info.is_delete='N' AND INSTR(bind_number,'$find') ORDER BY id DESC LIMIT ".$limit;
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
					$sql = "SELECT name FROM company_express_info WHERE express_id = '$StoreInfo->express_id'  AND company_id = '$company_id'";
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
					$sql = "SELECT name FROM store_info WHERE id = '$res->store_id' AND store_status = 'Normal' AND company_id = '$company_id'";
					$result3 = mysql_query($sql,$_mysql_link_);
					while($store = mysql_fetch_assoc($result3)){
						$list_audit['store_name']		    = $store['name'];
					}
					$list_audit['total']    = $res->total;
				}



				//店铺名字
				$sql = "SELECT shop_name FROM user_register_info WHERE id = '$StoreInfo->user_id'";
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
	}elseif($type=="shou"){
		$sql = "SELECT COUNT(*) as total FROM order_receiver LEFT JOIN order_info ON order_receiver.id=order_info.id WHERE order_receiver.company_id='$company_id' AND order_info.is_delete='N' AND INSTR(order_receiver.name,'$find')";

		$result	= mysql_query($sql, $_mysql_link_);
		$main['total']	= mysql_result($result, 0, 'total');
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
			$sql = "SELECT order_receiver.id,name,mobile,address FROM order_receiver LEFT JOIN order_info ON order_receiver.id=order_info.id WHERE order_receiver.company_id='$company_id' AND order_info.is_delete='N' AND INSTR(order_receiver.name,'$find') ORDER BY id LIMIT ".$limit;
			$result	= mysql_query($sql, $_mysql_link_);
			while($StoreInfo = mysql_fetch_object($result))
			{
				$list_audit	= array();

				$sql = "SELECT order_text,customer_text,user_id,bind_number FROM  order_source WHERE company_id='$company_id' AND id='$StoreInfo->id'";
				$res = mysql_query($sql,$_mysql_link_);
				while($dnRow = mysql_fetch_object($res)){
					$list_audit['order_text']			= $dnRow->order_text;
					$list_audit['bind_number']			= $dnRow->bind_number;
					$list_audit['customer_text']		= $dnRow->customer_text;
					//店铺名字
					$sql1 = "SELECT shop_name FROM user_register_info WHERE id = '$dnRow->user_id'";
					$result1 = mysql_query($sql1,$_mysql_link_);
					while($store = mysql_fetch_assoc($result1)){
						$list_audit['shop_name'] = $store['shop_name'];
					}
				}

				//快递公司
				$sql = "SELECT express_id FROM order_express_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
				$result2 = mysql_query($sql,$_mysql_link_);
				while($res = mysql_fetch_object($result2)){

					$sql = "SELECT name FROM company_express_info WHERE express_id = '$StoreInfo->express_id'  AND company_id = '$company_id'";
					$result3 = mysql_query($sql,$_mysql_link_);
					while($store = mysql_fetch_assoc($result3)){
						$list_audit['express_id']		    = $store['name'];
					}
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
					$sql = "SELECT name FROM store_info WHERE id = '$res->store_id' AND store_status = 'Normal' AND company_id = '$company_id'";
					$result4 = mysql_query($sql,$_mysql_link_);
					while($store = mysql_fetch_assoc($result4)){
						$list_audit['store_name']		    = $store['name'];
					}
					$list_audit['total']    = $res->total;
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
				$list_audit['name']					= $StoreInfo->name;
				$list_audit['mobile']				= $StoreInfo->mobile;
				$list_audit['address']				= $StoreInfo->address;

				$xtpl->assign("list_audit", $list_audit);
				$xtpl->parse("main.list_audit");
			}
		}
	}elseif($type=="kuai"){
		$sql = "SELECT COUNT(*) as total FROM order_express_paper WHERE company_id='$company_id' AND INSTR(number,'$find')";
		$result	= mysql_query($sql, $_mysql_link_);
		$main['total']	= mysql_result($result, 0, 'total');
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

			$sql = "SELECT order_id, express_id FROM order_express_paper WHERE company_id='$company_id' AND INSTR(number,'$find') ORDER BY id LIMIT ".$limit;
			$result	= mysql_query($sql, $_mysql_link_);
			while($StoreInfo = mysql_fetch_object($result)){

				$list_audit	= array();

				$sql = "SELECT name,mobile,address FROM order_receiver WHERE company_id='$company_id' AND id='$StoreInfo->id'";
				$result6	= mysql_query($sql, $_mysql_link_);
				while($dbRow = mysql_fetch_object($result6)){
					$list_audit['name']					= $StoreInfo->name;
					$list_audit['mobile']				= $StoreInfo->mobile;
					$list_audit['address']				= $StoreInfo->address;
				}

				$sql = "SELECT order_text,customer_text,user_id,bind_number FROM  order_source WHERE company_id='$company_id' AND id='$StoreInfo->id'";
				$res = mysql_query($sql,$_mysql_link_);
				while($dnRow = mysql_fetch_object($res)){
					$list_audit['order_text']			= $dnRow->order_text;
					$list_audit['bind_number']			= $dnRow->bind_number;
					$list_audit['customer_text']		= $dnRow->customer_text;
					//店铺名字
					$sql1 = "SELECT shop_name FROM user_register_info WHERE id = '$dnRow->user_id'";
					$result1 = mysql_query($sql1,$_mysql_link_);
					while($store = mysql_fetch_assoc($result1)){
						$list_audit['shop_name'] = $store['shop_name'];
					}
				}

				//快递公司
				$sql = "SELECT name FROM company_express_info WHERE express_id = '$StoreInfo->express_id'  AND company_id = '$company_id'";
				$result3 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result3)){
					$list_audit['express_id']		    = $store['name'];
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
					$sql = "SELECT name FROM store_info WHERE id = '$res->store_id' AND store_status = 'Normal' AND company_id = '$company_id'";
					$result4 = mysql_query($sql,$_mysql_link_);
					while($store = mysql_fetch_assoc($result4)){
						$list_audit['store_name']		    = $store['name'];
					}
					$list_audit['total']    = $res->total;
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

				$list_audit['id']			        = $StoreInfo->order_id;

				$xtpl->assign("list_audit", $list_audit);
				$xtpl->parse("main.list_audit");
			}
		}
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
