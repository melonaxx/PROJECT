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
	// 客户名称
	$name		= replace_safe($_POST['name']);
	$sql 		= "INSERT INTO crm_user_known SET name='{$name}'";
	mysql_query ( $sql, $_mysql_link_ );
	$bind_id 	=	mysql_insert_id( $_mysql_link_ );
	$userOK 	= 	mysql_affected_rows( $_mysql_link_ );

	if($userOK == 1){
		// 客户信息表
		$nick_name 		= replace_safe($_POST['nick_name']);
		$sql			= "INSERT INTO crm_user_info SET nick_name = '{$nick_name}',bind_id={$bind_id},bind_type='Unknown'";
		mysql_query($sql, $_mysql_link_);

		//获取自增id
		$crm_user_id 	= mysql_insert_id($_mysql_link_);
		// 客户与公司对表应关系
		$state_id		= intval($_POST['state_id']);
		$city_id		= intval($_POST['city_id']);
		$district_id	= intval($_POST['district_id']);
		// $crm_type		= intval($_POST['crm_type']);
		$post_code		= replace_safe($_POST['post_code']);
		$address		= replace_safe($_POST['address']);
		$telphone		= replace_safe($_POST['telphone']);
		$mobile			= replace_safe($_POST['mobile']);
		$email			= replace_safe($_POST['email']);
		$qq				= replace_safe($_POST['qq']);
		$body			= replace_safe($_POST['body']);
		$company_name	= replace_safe($_POST['company_name']);
		$tax_title		= replace_safe($_POST['tax_title']);
		$tax_number		= replace_safe($_POST['tax_number']);
		$tax_address	= replace_safe($_POST['tax_address']);
		$tax_telphone	= replace_safe($_POST['tax_telphone']);
		$tax_bank_name	= replace_safe($_POST['tax_bank_name']);
		$tax_bank_number= replace_safe($_POST['tax_bank_number']);
		$sql = "INSERT INTO crm_user_related SET
			company_id 			= '{$company_id}',
			crm_user_id 		= '{$crm_user_id}',
			-- crm_type			= '{$crm_type}',
			name 				= '{$name}',
			company_name 		= '{$company_name}',
			post_code 			= '{$post_code}',
			state_id			= '{$state_id}',
			city_id 			= '{$city_id}',
			district_id 		= '{$district_id}',
			telphone 			= '{$telphone}',
			mobile			 	= '{$mobile}',
			email 				= '{$email}',
			qq 					= '{$qq}',
			address 			= '{$address}',
			body 				= '{$body}',
			tax_title 			= '{$tax_title}',
			tax_number 			= '{$tax_number}',
			tax_address 		= '{$tax_address}',
			tax_telphone 		= '{$tax_telphone}',
			tax_bank_name 		= '{$tax_bank_name}',
			tax_bank_number 	= '{$tax_bank_number}'
		";
		mysql_query($sql, $_mysql_link_);

	}
	header('Location: /crm/crm_business_customers.php');
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
