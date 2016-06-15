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


	//关闭售后单
	if(!empty($_GET['id'])){
		$order_id = rtrim(replace_safe($_GET['id']),",");
		$sql = "UPDATE after_sale_info SET status = 'D' WHERE id IN ($order_id) AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);
		if($id){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//已解决
	if(!empty($_GET['after_id'])){
		$after_id = rtrim(replace_safe($_GET['after_id']),",");
		$sql = "UPDATE after_sale_info SET status = 'Y' WHERE company_id='$company_id' AND id IN ($after_id)";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);
		if($id){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}


	//查询条件
	$addon	 = array();
	$addon[] = "i.company_id='$company_id'";
	$addon[] = "i.is_delete='N'";
	$addon[] = "i.status='N'";

	if(!empty($_GET['find']))
	{
		$find	= replace_safe($_GET['find'], 20);
		if(!empty($find))
		{
			$addon[]		= "INSTR(ad.name,'$find') OR INSTR(r.bind_number,'$find')";
			$main['find']	= $find;
			$page_param		= array();
			$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
		}
	}
	//获取最近七天的时间
	$date = array();
	$date[] = date('Y-m-d');
	$date[] = date('Y-m-d',strtotime('-1 day'));
	$date[] = date('Y-m-d',strtotime('-2 day'));
	$date[] = date('Y-m-d',strtotime('-3 day'));
	$date[] = date('Y-m-d',strtotime('-4 day'));
	$date[] = date('Y-m-d',strtotime('-5 day'));
	$date[] = date('Y-m-d',strtotime('-6 day'));
	if(!empty($_GET['date']) && $_GET['date']=='AA')
	{
		$addon[] = "INSTR(i.action_date,'$date[0]')";
	}
	if(!empty($_GET['date']) && $_GET['date']=='BB')
	{
		$addon[] = "(INSTR(i.action_date,'$date[1]') OR INSTR(i.action_date,'$date[2]'))";
	}
	if(!empty($_GET['date']) && $_GET['date']=='CC')
	{
		$addon[] = "(INSTR(i.action_date,'$date[3]') OR INSTR(i.action_date,'$date[4]') OR INSTR(i.action_date,'$date[5]'))";
	}
	if(!empty($_GET['date']) && $_GET['date']=='DD')
	{
		$addon[] = "Date(i.action_date) <= '$date[6]'";
	}



	$where  = "";
	if(count($addon) > 0)
	{
		$where	= "WHERE ".implode(" AND ", $addon);
	}


	$sql = "SELECT count(*) AS total FROM  after_sale_info AS i
		LEFT JOIN after_sale_address AS ad ON i.id = ad.after_sale_id
		LEFT JOIN order_source AS r ON i.order_id=r.id ".$where;
	$result2	= mysql_query($sql, $_mysql_link_);
	$main['total']		= mysql_result($result2, 0, 'total');

	//---- 处理分页 ----
	if(!is_array($page_param))
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

	$sql = "SELECT i.id,i.order_id,i.service_type,i.payment,i.topic_id,i.action_date,i.express_id,i.number,i.staff_id,ad.name,ad.phone
		FROM  after_sale_info AS i
		LEFT JOIN after_sale_address AS ad ON i.id = ad.after_sale_id
		LEFT JOIN order_source AS r ON i.order_id=r.id ".$where." ORDER BY i.id DESC limit ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	while($res = mysql_fetch_object($result)){
		$arr = array();

		// 快递名称的查询
		$esql = "SELECT name FROM main_express_info WHERE id=$res->express_id";
		$eres = mysql_query($esql,$_mysql_link_);
		$edata = mysql_fetch_assoc($eres);
		$e_name = $edata['name'];
		$arr['e_name'] = $e_name;

		$sql1 = "SELECT shop_name FROM user_register_info LEFT JOIN order_source ON order_source.user_id=user_register_info.id WHERE order_source.id = '$res->order_id'";
		$result1 = mysql_query($sql1,$_mysql_link_);
		while($store = mysql_fetch_assoc($result1)){
			$arr['shop_name']		    = $store['shop_name'];
		}

		if($res->staff_id){
			$sql2 = "SELECT name FROM company_staff_info  WHERE id = '$res->staff_id' AND company_id='$company_id'";
			$result2 = mysql_query($sql2,$_mysql_link_);
			while($store = mysql_fetch_assoc($result2)){
				$arr['staff_name']		= $store['name'];
			}
		}

		$sql = "SELECT name FROM after_sale_topic WHERE id='$res->topic_id' AND company_id='$company_id' AND is_delete='N'";
		$this = mysql_query($sql,$_mysql_link_);
		$re = mysql_fetch_object($this);
		$arr['topic_name']   = $re->name;
		$arr['order_id']     = $res->order_id;
		$arr['id']           = $res->id;
		$arr['service_type'] = $res->service_type;
		$arr['payment']      = number_format($res->payment);
		$arr['name']         = $res->name;
		$arr['phone']        = $res->phone;
		$arr['number']       = $res->number;
 		switch($arr['service_type']){
			case "Refunds":
				$arr['service_name'] = "仅退款";break;
			case "Return":
				$arr['service_name'] = "退货退款";break;
			case "Exchange":
				$arr['service_name'] = "换货";break;
			case "Repair":
				$arr['service_name'] = "维修";break;
			case "Delivery":
				$arr['service_name'] = "补发货";break;
			case "Unknown":
				$arr['service_name'] = "其它未知";break;
		}
		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");