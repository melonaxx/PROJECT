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

	//---- 查询条件 ----
	$addon = array();
	$addon[] = "s.company_id='$company_id'";


	if (!empty($_GET['find'])) {
		$find = replace_safe($_GET['find'],20);
		if (!empty($find)) {
			$addon[] = "(INSTR(o.bind_number,'$find') OR INSTR(s.after_sale_id,'$find'))";
			$main['find']	= $find;
			$page_param		= array();
			$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
		}
	}

	$where  = "";
	if(count($addon) > 0)
	{
		$where	= " WHERE ".implode(" AND ", $addon);
	}

	$sql = "SELECT count(distinct s.after_sale_id) as total FROM after_sale_input AS s
	LEFT JOIN order_source AS o ON s.order_id=o.id ".$where."ORDER BY s.id DESC";
	$query1 = mysql_query($sql,$_mysql_link_);
	$rr 	= mysql_fetch_object($query1);
	$main['total']		= $rr->total;
	//---- 处理分页 ----
	if(!is_array($page_param))
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

	// $sql = "SELECT order_id,store_id,product_id,shop_id,total_finish,total_wait,after_sale_id,action_date,body FROM after_sale_input WHERE company_id='$company_id' GROUP BY after_sale_id ORDER BY id DESC LIMIT ".$limit;
	$sql = "SELECT s.order_id,s.store_id,s.product_id,s.shop_id,s.total_finish,s.total_wait,s.after_sale_id,s.action_date,s.body,o.bind_number FROM after_sale_input AS s
		LEFT JOIN order_source AS o ON s.order_id=o.id ".$where." GROUP BY s.after_sale_id ORDER BY s.id DESC LIMIT ".$limit;
		// var_dump($sql);
	$result = mysql_query($sql,$_mysql_link_);
	while($list = mysql_fetch_object($result)){
		$arr = array();
		//订单编号

		$arr['bind_number']    = $list->bind_number;
		// 仓库信息
		$sql = "SELECT name FROM store_info WHERE company_id='$company_id' AND id='$list->store_id'";
		$query = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($query)){
			$arr['store'] = $res->name;
		}

		$sql    = "SELECT shop_name FROM user_register_info WHERE id = '$list->shop_id'";
		$query2 = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($query2)){
			$arr['shop_name'] = $res->shop_name;
		}
		$arr['product_id']   = $list->product_id;
		$arr['action_date']   = $list->action_date;
		$arr['body']          = $list->body;
		$arr['total_wait']    = $list->total_wait;
		$arr['total_finish']  = $list->total_finish;
		$arr['after_id']      = $list->after_sale_id;

		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
