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
$id		= intval($_REQUEST['id']);
//---- 查询该供应商信息 ----
$sql	= "SELECT id, number, name, type, website, state_id, city_id, district_id, address, post_code, contact_name, phone, mobile, email, tax, fax, bank_info, content, level, main_business FROM purchase_supplier WHERE company_id='$company_id' AND id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	//---- 该供应商不存在 或者 属于其它公司 ----
	illegal_operation();
}
$SupplierInfo	= mysql_fetch_object($result);
//print_r($SupplierInfo);

if (isset($_POST['send'])) {
	$name			= replace_safe($_POST['name']);
	$number			= replace_safe($_POST['number']);
	if(empty($number))
	{
		//---- 如果没有编码则，自动生成供应商的编码 ----
		$number	= insert_company_number($company_id, "supplier");
	}
	
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
	foreach($_POST['goods_name'] as $key => $value)
	{
		if(!empty($value))
		{
			$main_business .= $value.':'.number_format($_POST['goods_price'][$key], 2, '.', '').';';
		}
	}
	$main_business	= trim($main_business, ";");
	$main_business	= replace_safe($main_business);

	$sql = "UPDATE purchase_supplier SET 
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
	WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);
	
	header('Location: /crm/crm_supplier_list.php');
	exit;
}

//---- 供应商基本信息 ----
$main['id']				= $SupplierInfo->id;
$main['type']			= $SupplierInfo->type;
$main['level']			= $SupplierInfo->level;
$main['number']			= $SupplierInfo->number;
$main['name']			= $SupplierInfo->name;
$main['website']		= $SupplierInfo->website;
$main['state_id']		= $SupplierInfo->state_id;
$main['city_id']		= $SupplierInfo->city_id;
$main['district_id']	= $SupplierInfo->district_id;
$main['address']		= $SupplierInfo->address;
$main['post_code']		= $SupplierInfo->post_code;
$main['contact_name']	= $SupplierInfo->contact_name;
$main['phone']			= $SupplierInfo->phone;
$main['mobile']			= $SupplierInfo->mobile;
$main['email']			= $SupplierInfo->email;
$main['tax']			= $SupplierInfo->tax;
$main['fax']			= $SupplierInfo->fax;
// $main['bank_name']		= $SupplierInfo->bank_name;
// $main['bank_number']	= $SupplierInfo->bank_number;
$main['bank_info']		= $SupplierInfo->bank_info;
$main['content']		= $SupplierInfo->content;

$bankInfo	= explode(':', $main['bank_info']);
$main['bank_name']		= $bankInfo[0];
$main['bank_number']	= $bankInfo[1];

//---- 主营商品及价格 ----
$mainBusiness			= explode(';', $SupplierInfo->main_business);
foreach($mainBusiness as $dat)
{
	$tmp	= explode(':', $dat);
	$list_product['name']	= $tmp[0];
	$list_product['price']	= $tmp[1];
	$xtpl->assign("list_product", $list_product);
	$xtpl->parse("main.list_product");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");