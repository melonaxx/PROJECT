<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function_extra.php";

//echo intval(0001);
//print_r($_SESSION);
$companyId = $_SESSION['_application_info_']['company_id'];

if (!empty($_POST)) {
	$name = str_processing($_POST['name']);
	$number = str_processing($_POST['number']);
	if (empty($number)) {//按数据库查询自动递增
		$sql = "SELECT number FROM  company_number_info WHERE company_id='{$companyId}' AND name='supplier' ORDER BY id DESC LIMIT 1";
		$data = get_all_data($sql);
		if (empty($data)) {
			$intoNumber = 1;
		} else {
			$intoNumber = $data[0]['number'] + 1;
		}
		$sql = "INSERT INTO company_number_info (company_id,name,number) VALUES ('{$companyId}','supplier','{$intoNumber}')";
		_query($sql);
		$number = sprintf("%04d", $intoNumber);
	}
	
	$type = str_processing($_POST['type']);
	$website = str_processing($_POST['website']);
	$state_id = intval($_POST['state_id']);
	$city_id = intval($_POST['city_id']);
	$district_id = intval($_POST['district_id']);
	$address = intval($_POST['address']);
	$post_code = str_processing($_POST['post_code']);
	$contact_name = str_processing($_POST['contact_name']);
	$phone = str_processing($_POST['phone']);
	$mobile = str_processing($_POST['mobile']);
	$email = str_processing($_POST['email']);
	$tax = str_processing($_POST['tax']);
	$fax = str_processing($_POST['fax']);
	$bank_info = str_processing($_POST['bank_name']).str_processing($_POST['bank_number']);
	$content = str_processing($_POST['content']);
	$level = str_processing($_POST['level']);
	$is_delete = 'N';
	
	$main_business = '';
	foreach ($_POST['goods_name'] as $key=>$value) {
		if (!empty($value)) {
			$main_business .= str_processing($value).':'.intval($_POST['goods_price']).';';
		}
	}
	if (!empty($main_business)) {
		$main_business = substr($main_business, 0, -1);
	}
	
	$sql = "INSERT INTO purchase_supplier (
			company_id,
			name,
			number,
			type,
			website,
			state_id,
			city_id,
			district_id,
			address,
			post_code,
			contact_name,
			phone,
			mobile,
			email,
			tax,
			fax,
			bank_info,
			content,
			level,
			is_delete,
			main_business
			) VALUES (
			'{$companyId}',
			'{$name}',
			'{$number}',
			'{$type}',
			'{$website}',
			'{$state_id}',
			'{$city_id}',
			'{$district_id}',
			'{$address}',
			'{$post_code}',
			'{$contact_name}',
			'{$phone}',
			'{$mobile}',
			'{$email}',
			'{$tax}',
			'{$fax}',
			'{$bank_info}',
			'{$content}',
			'{$level}',
			'{$is_delete}',
			'{$main_business}'
			)";
	_query($sql);
	
	header('Location: /crm/crm_supplier_list.php');
	exit;
}
//print_r($_POST);
// $xtpl		= new xtpl();
// $xtpl->set_file("../../tpl/crm_supplier_add.html");

// get_site_application_menu($_SESSION['_application_info_']['site_menu'], "/crm/crm_supplier_add.php", $xtpl, $main);

// $sql	= "SELECT body FROM main_channel_info WHERE url='/crm/crm_supplier_add.php' LIMIT 1";
// $result	= mysql_query($sql, $_mysql_link_);
// if(mysql_num_rows($result))
// {
// 	$main['html_body']	= mysql_result($result, 0, 'body');
// }



