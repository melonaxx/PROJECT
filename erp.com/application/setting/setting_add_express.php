<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";
$company_id	= $_SESSION['_application_info_']['company_id'];

if(!empty($_POST['aa'])){
	$name = $_POST['aa'];
	$sql = "SELECT name,id FROM main_express_info WHERE name like '%$name%'";
	$result = mysql_query($sql,$_mysql_link_);
	$num = array();
	while($StoreInfo = mysql_fetch_object($result)){
		$data[] = array(
        'name' 	=> 	$StoreInfo->name,
		'id' 	=> 	$StoreInfo->id
		);
	}
	echo json_encode($data);
	exit;
}


if(!empty($_POST)){

	$express_id = intval($_POST['express_id']);
	$name		= replace_safe($_POST['name']);
	$payment 	= intval($_POST['payment']);
	$fee		= intval($_POST['fee']);
	$state_id 	= intval($_POST['state_id']);
	$city_id	= intval($_POST['city_id']);
	$district_id= intval($_POST['district_id']);
	$address	= replace_safe($_POST['address']);
	$post_code	= intval($_POST['post_code']);
	$contact_name= replace_safe($_POST['contact_name']);
	$mobile		= replace_safe($_POST['mobile']);
	$telphone	= replace_safe($_POST['telphone']);
	$bank_id	= intval($_POST['bank_id']);
	$tax		= replace_safe($_POST['tax']);
	$body		= replace_safe($_POST['body']);

	$sql2 = "SELECT count(*) FROM company_express_info WHERE company_id='$company_id' AND express_id='$express_id' AND status!='D'";
	$result = mysql_query($sql2,$_mysql_link_);
	$count = mysql_result($result, 0, 0);
	if($count > 0){
		echo "exit";
		exit;
	}

	$sql3 = "SELECT count(*) FROM main_express_info WHERE id='$express_id'";
	$result3 = mysql_query($sql3,$_mysql_link_);
	$count3 = mysql_result($result3, 0, 0);
	if($count3 > 0){
		// 判断是否添加过 又删除了
		$sql4 = "SELECT count(*) FROM company_express_info WHERE company_id='$company_id' AND express_id='$express_id' AND status ='D'";
		$result4 = mysql_query($sql4,$_mysql_link_);
		$count4 = mysql_result($result4, 0, 0);
		if($count4 > 0){
			// echo
			$sql = "UPDATE company_express_info SET  name = '$name', payment = '$payment' ,fee = '$fee', state_id = '$state_id', city_id = '$city_id', district_id = '$district_id', address = '$address', post_code = '$post_code', contact_name = '$contact_name', mobile = '$mobile', telphone = '$telphone', bank_id = '$bank_id', tax = '$tax', body = '$body',status='Y' WHERE company_id='$company_id' AND  express_id = '$express_id'";
			$res = mysql_query($sql,$_mysql_link_);

		}else{
			$sql = "INSERT INTO company_express_info SET company_id='$company_id', express_id = '$express_id', name = '$name', payment = '$payment' ,fee = '$fee', state_id = '$state_id', city_id = '$city_id', district_id = '$district_id', address = '$address', post_code = '$post_code', contact_name = '$contact_name', mobile = '$mobile', telphone = '$telphone', bank_id = '$bank_id', tax = '$tax', body = '$body'";
			$res = mysql_query($sql,$_mysql_link_);

		}

		if($res){
			echo 1;
		}

	}else{
		echo "no";
	}
	exit;
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");