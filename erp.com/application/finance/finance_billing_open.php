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
//设置查询条件
$addon	= array();
	$addon[]	= "order_receiver.company_id='".$company_id."'";
	// $addon[]    = "order_delivery.delivery_status='Finish'";
	$addon[]    = "order_receiver.need_invoice='Y'";
	$addon[]    = "order_receiver.tax_status='Y'";


	if(!empty($_REQUEST['find']))
	{
		$find	= replace_safe($_REQUEST['find'], 20);
		if(!empty($find))
		{
			$addon[]		= "INSTR(order_source.bind_number,'$find')";
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

	$sql = "SELECT COUNT(*) as total FROM order_receiver
	LEFT JOIN order_source ON order_receiver.id = order_source.id
	 ".$where;
	$result	= mysql_query($sql, $_mysql_link_);
	$main['total']		= mysql_result($result, 0, 'total');

	//---- 处理分页 ----
	if(!is_array($page_param))
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$tax_type = array('Normal'=>'普通发票','VAT'=>'增值税发票');

$sql = "SELECT
		order_receiver.id,order_source.user_id,order_source.bind_number,
		order_receiver.name,order_receiver.mobile,
		order_receiver.tax_type,order_receiver.tax_title,order_receiver.tax_text
		FROM  order_receiver
		LEFT JOIN order_source ON order_receiver.id = order_source.id
		 ".$where." LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
$no = 1;
while($dbRow = mysql_fetch_object($result)){
	$tax = array();
	$tax['id'] = $dbRow->id;
	$sql2 = "SELECT shop_name FROM user_register_info WHERE id = '$dbRow->user_id'";
		$result2 = mysql_query($sql2,$_mysql_link_);
		while($store = mysql_fetch_assoc($result2)){
			$tax['user_id']        = $store['shop_name'];
		}
	$tax['bind_number'] = $dbRow->bind_number;
	$tax['name'] = $dbRow->name;
	$tax['mobile'] = $dbRow->mobile;
	$tax['tax_type'] = $tax_type[$dbRow->tax_type];
	$tax['tax_title'] = $dbRow->tax_title;
	$tax['tax_text'] = $dbRow->tax_text;
	// $tax['delivery_status'] = $dbRow->delivery_status;

	$xtpl->assign('tax',$tax);
	$xtpl->parse('main.tax');

}









$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
