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

if (isset($_POST['send']))
{
	$name			= replace_safe($_POST['name']);
	$type			= replace_safe($_POST['type']);
	$website		= replace_safe($_POST['website']);
	$state_id		= intval($_POST['state_id']);
	$city_id		= intval($_POST['city_id']);
	$district_id	= intval($_POST['district_id']);
	$address		= replace_safe($_POST['address']);
	$post_code		= replace_safe($_POST['post_code']);
	$contact_name	= replace_safe($_POST['contact_name']);
	$phone			= replace_safe($_POST['phone']);
	$mobile			= replace_safe($_POST['mobile']);
	$email			= replace_safe($_POST['email']);
	$tax			= replace_safe($_POST['tax']);
	$fax			= replace_safe($_POST['fax']);
	$bank_info		= replace_safe($_POST['bank_name'].':'.$_POST['bank_number']);
	$content		= replace_safe($_POST['content']);
	$level			= replace_safe($_POST['level']);
	$is_delete		= 'N';
	$main_business	= '';
	
	
	$number			= replace_safe($_POST['number']);
	if(empty($number))
	{
		//---- 如果没有编码则，自动生成供应商的编码 ----
		$number	= insert_company_number($company_id, "supplier");
	}
	
	
	foreach($_POST['goods_name'] as $key => $value)
	{
		if(!empty($value))
		{
			$main_business .= $value.':'.number_format($_POST['goods_price'][$key], 2, '.', '').';';
		}
	}
	$main_business	= trim($main_business, ";");
	echo $main_business;
	$main_business	= replace_safe($main_business);

	$sql = "INSERT INTO purchase_supplier SET 
		company_id='$company_id',
		name='{$name}',
		number='{$number}',
		type='{$type}',
		website='{$website}',
		state_id='{$state_id}',
		city_id='{$city_id}',
		district_id='{$district_id}',
		address='{$address}',
		post_code='{$post_code}',
		contact_name='{$contact_name}',
		phone='{$phone}',
		mobile='{$mobile}',
		email='{$email}',
		tax='{$tax}',
		fax='{$fax}',
		bank_info='{$bank_info}',
		content='{$content}',
		level='{$level}',
		is_delete='{$is_delete}',
		main_business='{$main_business}'
	";
	mysql_query($sql, $_mysql_link_);
	
	header('Location: /crm/crm_supplier_list.php');
	exit;
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");