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
$crm_user_id  	= intval($_REQUEST['crm_user_id']);

//---- 查询该客户信息 ----
$sql= "SELECT r.* ,i.nick_name
	FROM crm_user_related AS r
	INNER JOIN crm_user_info AS i ON r.crm_user_id=i.id
	WHERE r.company_id='$company_id' AND r.id='$id'";
$result	= mysql_query($sql, $_mysql_link_);

if(mysql_num_rows($result) < 1)
{
	//---- 该客户不存在-------
	illegal_operation();
}
$CustomerInfo	= mysql_fetch_object($result);
// var_dump($CustomerInfo);
//---- 客户基本信息 ----
$main['id']				= $CustomerInfo->id;
$main['crm_user_id']	= $CustomerInfo->crm_user_id;
$main['crm_type']		= $CustomerInfo->crm_type;
$main['name']			= $CustomerInfo->name;
$main['nick_name']		= $CustomerInfo->nick_name;
$main['company_name']	= $CustomerInfo->company_name;
$main['state_id']		= $CustomerInfo->state_id;
$main['city_id']		= $CustomerInfo->city_id;
$main['district_id']	= $CustomerInfo->district_id;
$main['address']		= $CustomerInfo->address;
$main['telphone']		= $CustomerInfo->telphone;
$main['mobile']			= $CustomerInfo->mobile;
$main['email']			= $CustomerInfo->email;
$main['post_code']		= $CustomerInfo->post_code;
$main['qq']				= $CustomerInfo->qq;
$main['body']		 	= $CustomerInfo->body;
$main['tax_title']		= $CustomerInfo->tax_title;
$main['tax_number']		= $CustomerInfo->tax_number;
$main['tax_address']	= $CustomerInfo->tax_address;
$main['tax_telphone']	= $CustomerInfo->tax_telphone;
$main['tax_bank_name']	= $CustomerInfo->tax_bank_name;
$main['tax_bank_number']= $CustomerInfo->tax_bank_number;

//---- 数据的修改 ----s
if (!empty($_POST['send'])) {
	$crm_type		= intval($_POST['crm_type']);
	$name			= replace_safe($_POST['name']);
	$nick_name		= replace_safe($_POST['nick_name']);
	$company_name	= replace_safe($_POST['company_name']);
	$state_id		= intval($_POST['state_id']);
	$city_id		= intval($_POST['city_id']);
	$district_id	= intval($_POST['district_id']);
	$address		= replace_safe($_POST['address']);
	$post_code		= replace_safe($_POST['post_code']);
	$telphone		= replace_safe($_POST['telphone']);
	$mobile			= replace_safe($_POST['mobile']);
	$email			= replace_safe($_POST['email']);
	$qq				= replace_safe($_POST['qq']);
	$body			= replace_safe($_POST['body']);
	$tax_title		= replace_safe($_POST['tax_title']);
	$tax_number		= replace_safe($_POST['tax_number']);
	$tax_address	= replace_safe($_POST['tax_address']);
	$tax_telphone	= replace_safe($_POST['tax_telphone']);
	$tax_bank_name	= replace_safe($_POST['tax_bank_name']);
	$tax_bank_number= replace_safe($_POST['tax_bank_number']);

	// var_dump($_POST);die;
	// $main_business	= '';
	// foreach($_POST['goods_name'] as $key => $value)
	// {
	// 	if(!empty($value))
	// 	{
	// 		$main_business .= $value.':'.number_format($_POST['goods_price'][$key], 2, '.', '').';';
	// 	}
	// }
	// $main_business	= trim($main_business, ";");
	// $main_business	= replace_safe($main_business);

	// 修改客户与公司对应关系表
	$sql = "UPDATE crm_user_related SET
		-- crm_type		= '{$crm_type}',//这个字段数据库中是没有的
		name			= '{$name}',
		state_id		= '{$state_id}',
		city_id			= '{$city_id}',
		district_id		= '{$district_id}',
		address 		= '{$address}',
		telphone 		= '{$telphone}',
		mobile 			= '{$mobile}',
		company_name 	= '{$company_name}',
		email 			= '{$email}',
		post_code 		= '{$post_code}',
		qq 				= '{$qq}',
		body 			= '{$body}',
		tax_title  		= '{$tax_title}',
		tax_number 		= '{$tax_number}',
		tax_address 	= '{$tax_address}',
		tax_telphone	= '{$tax_telphone}',
		tax_bank_name 	= '{$tax_bank_name}',
		tax_bank_number = '{$tax_bank_number}'
	WHERE id='$id'";

	mysql_query($sql, $_mysql_link_);

	// 修改客户信息表(这个表的信息不要改动)
	// $sql = "UPDATE crm_user_info SET
	// 		nick_name = '{$nick_name}'
	// WHERE id='{$crm_user_id}'";
	// mysql_query($sql, $_mysql_link_);

	// 修改未知客户信息表(这个表的信息不要改动)
	// $sql = "SELECT bind_id FROM crm_user_info WHERE id={$crm_user_id}";
	// $result = mysql_query($sql, $_mysql_link_);

	// $user_info = mysql_fetch_assoc($result);
	// $bind_id = $user_info['bind_id'];
	// $sql = "UPDATE crm_user_known SET
	// 		name = '{$name}'
	// WHERE id='{$bind_id}'";
	// mysql_query($sql, $_mysql_link_);
	header('Location: /crm/crm_business_customers.php');
	exit;
}

if(!empty($main['tax_title']) || !empty($main['tax_number']) || !empty($main['tax_address']) || !empty($main['tax_telphone']) || !empty($main['tax_bank_name']) || !empty($main['tax_bank_number'])){
	$main['tax_Ok']=1;
}

// var_dump($main);die;
// $bankInfo	= explode(':', $main['bank_info']);
// $main['bank_name']		= $bankInfo[0];
// $main['bank_number']	= $bankInfo[1];

// //---- 主营商品及价格 ----
// $mainBusiness			= explode(';', $CustomerInfo->main_business);
// foreach($mainBusiness as $dat)
// {
// 	$tmp	= explode(':', $dat);
// 	$list_product['name']	= $tmp[0];
// 	$list_product['price']	= $tmp[1];
// 	$xtpl->assign("list_product", $list_product);
// 	$xtpl->parse("main.list_product");
// }
// var_dump($main);
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");