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

//获取快递公司
$sql = "SELECT express_id,name FROM company_express_info WHERE company_id='$company_id' ";
$res = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($res))
{
	$ress 					= array();
	$ress['name'] 			= $dbRow->name;
	$ress['express_id'] 	= $dbRow->express_id;
	$xtpl->assign('ress',$ress);
	$xtpl->parse('main.ress');
}
//获取店铺
$sql = "SELECT user_id FROM  company_related WHERE company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_shop 	= array();
	$list_shop['user_id'] = $StoreInfo->user_id;
	$sql 	= "SELECT shop_name FROM user_register_info WHERE id='$StoreInfo->user_id'";
	$query 	= mysql_query($sql,$_mysql_link_);
	$res   	= mysql_fetch_object($query);
	$list_shop['shop_name'] = $res->shop_name;
	$xtpl->assign("list_shop", $list_shop);
	$xtpl->parse("main.list_shop");
}
//设置查询条件
$chaxun 	= array();
$chaxun[] 	= "e.company_id ='".$company_id."'";

if(!empty($_GET['express']))
{
	$express_id = $_GET['express'];
	if(!empty($express_id))
	{
		$chaxun[] 			   = "e.express_id = '".$express_id."'";
		$main['express_id']    = $express_id;
		$page_param			   = array();
		$page_param['express'] = replace_safe($_GET['express'], 20, false, false);
	}
}
if(!empty($_GET['shop']))
{
	$shop_id = $_GET['shop'];
	if(!empty($shop_id))
	{
		$chaxun[] 			   = "s.user_id = '".$shop_id."'";
		$main['shop_id']       = $shop_id;
		$page_param			   = array();
		$page_param['express'] = replace_safe($_GET['shop'], 20, false, false);
	}
}

$begin_date = '';
$end_date   = '';
if(!empty($_GET['begin_date']) && empty($_GET['end_date']))
{
	$begin_date = $_GET['begin_date'];
	if(!empty($begin_date))
	{
		$main['begin_date'] 	  = $begin_date;
		$chaxun[] 				  = "Date(e.deliver_date) >= '".$begin_date."'";
		$page_param				  = array();
		$page_param['begin_date'] = replace_safe($_GET['begin_date'], 20, false, false);
	}
}
if(!empty($_GET['end_date']) && empty($_GET['begin_date']))
{
	$end_date = $_GET['end_date'];
	if(!empty($end_date))
	{
		$main['end_date'] 		= $end_date;
		$chaxun[] 				= "Date(e.deliver_date) <= '".$end_date."'";
		$page_param				= array();
		$page_param['end_date']	= replace_safe($_GET['end_date'], 20, false, false);
	}
}
if(!empty($_GET['end_date']) && !empty($_GET['begin_date']))
{
	$begin_date = $_GET['begin_date'];
	$end_date 	= $_GET['end_date'];
	if(Date($begin_date) > Date($end_date)){
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>alert('查询结束时间不能小于开始时间！');window.location.href='/finance/finance_express_cost.php';</script>";
		exit;
	}
	if(Date($begin_date) == Date($end_date))
	{
		$main['begin_date'] 	  = $begin_date;
		$main['end_date'] 		  = $end_date;
		$chaxun[] 				  = "INSTR(e.deliver_date,'$begin_date')";
		$page_param				  = array();
		$page_param['end_date']	  = replace_safe($_GET['end_date'], 20, false, false);
		$page_param['begin_date'] = replace_safe($_GET['begin_date'], 20, false, false);
	}
	if(!empty($begin_date) && !empty($end_date))
	{
		$main['begin_date'] 	  = $begin_date;
		$main['end_date'] 		  = $end_date;
		$chaxun[] 				  = "Date(e.deliver_date) <= '".$end_date."'";
		$chaxun[] 				  = "Date(e.deliver_date) >= '".$begin_date."'";
		$page_param				  = array();
		$page_param['end_date']	  = replace_safe($_GET['end_date'], 20, false, false);
		$page_param['begin_date'] = replace_safe($_GET['begin_date'], 20, false, false);
	}
}
if(empty($_GET['begin_date']) && empty($_GET['end_date']))
{
	$end_date 			= date('Y-m-d',strtotime('-1 day'));
	$begin_date 		= date('Y-m-d',strtotime('-7 day'));
	$chaxun[] 			= "Date(e.deliver_date) <= '".$end_date."'";
	$chaxun[] 			= "Date(e.deliver_date) >= '".$begin_date."'";
	$main['end_date'] 	= $end_date;
	$main['begin_date'] = $begin_date;
}


$where = '';
if(count($chaxun)>0)
{
	$where = "WHERE ".implode(' AND ',$chaxun);
}
//获取总的运费，快递成本以及毛利
$sql = "SELECT e.freight_buyer,e.freight_seller FROM order_express_paper AS e LEFT JOIN order_source AS s ON e.order_id = s.id ".$where;
$res = mysql_query($sql,$_mysql_link_);
$freight_buyer_z = '';
$freight_seller_z = '';
$profit_z  = '';
while($dbRow = mysql_fetch_object($res)){
	$freight_buyer_z += number_format($dbRow->freight_buyer,2);
	$freight_seller_z += number_format($dbRow->freight_seller,2);
	$profit_z += number_format($dbRow->freight_buyer - $dbRow->freight_seller,2);
}
$main['freight_buyer_z'] = number_format($freight_buyer_z,2);
$main['freight_seller_z'] = number_format($freight_seller_z,2);
$main['profit_z'] = number_format($profit_z,2);


//---分页---
$sql	= "SELECT COUNT(*) AS total FROM order_express_paper AS e LEFT JOIN order_source AS s ON e.order_id=s.id ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//快递公司名
$sql = "SELECT express_id,name FROM company_express_info WHERE company_id='$company_id' ";
$res = mysql_query($sql,$_mysql_link_);
$expressInfo = array();
while($dbRow = mysql_fetch_object($res))
{
	$expressInfo[$dbRow->express_id] = $dbRow->name;
}
//店铺名
$sql = "SELECT id,shop_name FROM user_register_info ";
$res = mysql_query($sql,$_mysql_link_);
$shopInfo   = array();
while($dbRow=mysql_fetch_object($res))
{
	$shopInfo[$dbRow->id] = $dbRow->shop_name;
}

$sql = "SELECT e.number,e.deliver_date,e.freight_buyer,e.freight_seller,e.express_id,s.bind_number,s.user_id FROM order_express_paper AS e LEFT JOIN order_source AS s ON e.order_id = s.id ".$where." ORDER BY e.deliver_date DESC LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
$no = 1;
while($dbRow = mysql_fetch_object($result))
{
	$express 					= array();
	$express['no']				= $no++;
	$express['number'] 			= $dbRow->number;
	$express['bind_number'] 	= $dbRow->bind_number;
	$express['deliver_date'] 	= $dbRow->deliver_date;
	$express['freight_buyer'] 	= $dbRow->freight_buyer;
	$express['freight_seller'] 	= $dbRow->freight_seller;
	$express['shop_id'] 		= $shopInfo[$dbRow->user_id];
	$express['express_id'] 		= $expressInfo[$dbRow->express_id];
	$express['profit']			= number_format($dbRow->freight_buyer - $dbRow->freight_seller,2);

	$xtpl->assign('express',$express);
	$xtpl->parse('main.express');
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
