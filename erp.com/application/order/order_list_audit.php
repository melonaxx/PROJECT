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

	//---- 验证仓库中的商品数量并审核订单 ----
	if (!empty($_GET['storeId']) && !empty($_GET['orderId'])) {
		$storeId = $_GET['storeId'];
		$orderId = $_GET['orderId'];
		$ExistStore = array();
		$audit = array();
		foreach ($orderId as $key => $value) {
			$audit[] = $value;
			if (empty($storeId[$key])) {
				$ExistStore[] = $value;
			}
		}
		if (count($ExistStore)>0) {
			echo json_encode($ExistStore);
			die();
		}
		//确认审核
		$audit = implode(',',$audit);
		$sql = "UPDATE order_info SET is_audit = 'Y' WHERE company_id = '$company_id' AND id IN ($audit)";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);
		if($id){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		die();
	}




	//---- 设置查询条件: ----
	$addon	= array();
	$addon[]	= "order_info.company_id='".$_SESSION['_application_info_']['company_id']."'";
	$addon[]	= "order_info.is_audit='N'";
	$addon[]	= "order_info.is_delete='N'";
	$addon[]	= "order_info.unusual_id='0'";

	//判断是线下还是线上
	// if(!empty($_GET['type'])){
	// 	$type = replace_safe($_REQUEST['type']);
	// 	$main['type']	= $type;
	// 	$page_param['type']		= replace_safe($_REQUEST['type']);
	// }else{
	// 	$type = replace_safe($_REQUEST['type']);
	// 	$main['type']	= $type;
	// }

	//---- 这是查询条件 ----
	if(!empty($_GET['find']))
	{
		// 判断是线上还是线下
		// if (!empty($_GET['type'])) {
		// 	$type = replace_safe($_REQUEST['type']);
		// }

		$find	= replace_safe($_REQUEST['find'], 20);
		$find   = str_replace('/[\s]*/', '', $find);
		if(!empty($find))
		{
			$addon[]		= "( INSTR(order_source.bind_number,'$find') OR INSTR(order_receiver.name,'$find') )";
			$main['find']	= $find;
			// $main['type']	= $type;
			$page_param		= array();
			$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
			// if (!empty($_GET['type'])) {
			// 	$page_param['type']		= replace_safe($_REQUEST['type']);
			// }
		}
	}

	$where  = "";
	if(count($addon) > 0)
	{
		// 判断线上还是线下
		// if($type == 'System'){
		// 	$where	= "WHERE ".implode(" AND ", $addon).'AND order_source.bind_type="System"';
		// }elseif($type == ''){
		// 	$where	= "WHERE ".implode(" AND ", $addon);
		// }elseif($type == 'online'){
		// 	$where	= "WHERE ".implode(" AND ", $addon).'AND order_source.bind_type <> "System"';
		// }
		$where	= "WHERE ".implode(" AND ", $addon).'AND order_source.bind_type <> "System"';
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
		order_info.id,order_info.order_date,
		order_source.order_text,order_source.customer_text,order_source.user_id,order_source.bind_number,order_source.bind_type,
		order_receiver.name,order_receiver.mobile,order_receiver.address
		FROM  order_info
		LEFT JOIN order_source ON order_info.id = order_source.id
		LEFT JOIN order_receiver ON order_info.id = order_receiver.id ".$where." ORDER BY order_info.id DESC LIMIT ".$limit;
		$result	= mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result))
		{
			$list_audit	= array();

			$sql     = "SELECT store_id,sum(total) AS total FROM order_product WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result1 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result1)){

				//仓库
				$sql1 = "SELECT name FROM store_info WHERE id = '$res->store_id' AND company_id = '$company_id'";
				$result1 = mysql_query($sql1,$_mysql_link_);
				while($store = mysql_fetch_assoc($result1)){
					$list_audit['store_id']		    = $store['name'];
					$list_audit['storeId']		    = $res->store_id;
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


			$sql     = "SELECT shop_name FROM user_register_info WHERE id = '$StoreInfo->user_id'";
			$result4 = mysql_query($sql,$_mysql_link_);
			while($store = mysql_fetch_assoc($result4)){
				$list_audit['shop_name']		    = $store['shop_name'];
			}
			//用户昵称
			$nickSql= "SELECT nick_name FROM crm_user_info WHERE bind_id = '$StoreInfo->user_id' AND bind_type <> 'System'";
			$nickRes = mysql_query($nickSql,$_mysql_link_);
			while($store = mysql_fetch_assoc($nickRes)){
				$list_audit['nick_name']		    = $store['nick_name'];
			}

			//订单的拆合状态
			$chsql = "SELECT split_status FROM order_split WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$chres = mysql_query($chsql,$_mysql_link_);

			while($chdata = mysql_fetch_object($chres)){
				if($chdata->split_status == 'Split'){
					$list_audit['split'] = '拆';
				}elseif($chdata->split_status == 'Merge'){
					$list_audit['merge'] = '合';
				}
			}

			//快递单的状态
			$psql = "SELECT express_id,number,print_status FROM order_express_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$presult = mysql_query($psql,$_mysql_link_);
			while($pres = mysql_fetch_object($presult)){
				if($pres->print_status == 'Y')
				{
					$list_audit['mark_express'] = "快";
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
					$list_audit['mark_deliver'] = "发";
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
					$list_audit['mark_order'] = "配";
				}else{
					$list_audit['mark_order'] = "";
				}
			}

			$list_audit['id']			        = $StoreInfo->id;
			$list_audit['bind_number']			= $StoreInfo->bind_number;
			$list_audit['order_text']			= $StoreInfo->order_text;
			$list_audit['customer_text']		= $StoreInfo->customer_text;
			$list_audit['name']					= $StoreInfo->name;
			$list_audit['bind_type']			= $StoreInfo->bind_type;

			$list_audit['mobile']				= $StoreInfo->mobile;
			$list_audit['address']				= $StoreInfo->address;

			$xtpl->assign("list_audit", $list_audit);
			$xtpl->parse("main.list_audit");
		}
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");