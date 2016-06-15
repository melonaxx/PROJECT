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

$company_id = $_SESSION['_application_info_']['company_id'];
$id = intval($_REQUEST['id']);
if(!empty($id)){
$sql = "SELECT state_id,city_id,district_id,id,express_id,name,payment,fee,address,post_code,contact_name,mobile,telphone,bank_id,tax,body FROM  company_express_info WHERE company_id = '$company_id' AND id = '$id'";
$result = mysql_query($sql,$_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	//---- 不存在 或者 属于其它公司 ----
	illegal_operation();
}
$StoreInfo 	= mysql_fetch_object($result);
$express_id				= $StoreInfo -> express_id;
$main['state_id']		= $StoreInfo -> state_id;
$main['city_id']		= $StoreInfo -> city_id;
$main['district_id']	= $StoreInfo -> district_id;
$main['name']			= $StoreInfo -> name;
$main['id']				= $StoreInfo -> id;
$main['payment']		= $StoreInfo -> payment;
$main['fee']			= $StoreInfo -> fee;
$main['address']		= $StoreInfo -> address;
$main['post_code']		= $StoreInfo -> post_code;
$main['contact_name']	= $StoreInfo -> contact_name;
$main['mobile']			= $StoreInfo -> mobile;
$main['telphone']		= $StoreInfo -> telphone;
$main['bank_id']		= $StoreInfo -> bank_id;
$main['tax']			= $StoreInfo -> tax;
$main['body']			= $StoreInfo -> body;
	
	$sql2 = "SELECT id,name FROM  main_express_info";
	$result2 = mysql_query($sql2,$_mysql_link_);
	$express_info = array();
	while($StoreInfo = mysql_fetch_object($result2)){
        $express_info['name'] 	=	$StoreInfo->name;
		$express_info['id']		= 	$StoreInfo->id;
			if($express_info['id'] == $express_id){
				$express_info['selected'] = "selected";
			}else{
				$express_info['selected'] = "";
			}
		$express[$StoreInfo->id] = $StoreInfo->name;
		$xtpl->assign("express_info", $express_info);
		$xtpl->parse("main.express_info");
	}

	$sql3 = "SELECT id,name,is_default FROM  company_express_template_info WHERE express_id='$express_id' AND company_id='$company_id'";
	$result3 = mysql_query($sql3,$_mysql_link_);
	$template_list = array();
	while($templateInfo = mysql_fetch_object($result3)){

        $template_list['name'] 	=	$templateInfo->name;
		$template_list['id']	= 	$templateInfo->id;
			if($templateInfo->is_default == 'Y'){
				$template_list['selected'] = "selected";
			}else{
				$template_list['selected'] = "";
			}
		$template[$templateInfo->id] = $templateInfo->name;
		$xtpl->assign("template_list", $template_list);
		$xtpl->parse("main.template_list");	
	}

}
if(!empty($_POST['send'])){
	$id 			= intval($_POST['id']);
	$express_id		= intval($_POST['express_id']);
	$state_id 		= intval($_POST['state_id']);
	$city_id 		= intval($_POST['city_id']);
	$district_id 	= intval($_POST['district_id']);
	$template_id 	= intval($_POST['template_id']);
	$name 			= replace_safe($_POST['name']);
	$payment		= replace_safe($_POST['payment']);
	$fee			= replace_safe($_POST['fee']);
	$address		= replace_safe($_POST['address']);
	$post_code		= replace_safe($_POST['post_code']);
	$contact_name	= replace_safe($_POST['contact_name']);
	$mobile			= replace_safe($_POST['mobile']);
	$telphone		= replace_safe($_POST['telphone']);
	$bank_id		= replace_safe($_POST['bank_id']);
	$tax			= replace_safe($_POST['tax']);
	$body			= replace_safe($_POST['body']);
	if(isset($express[$express_id])){	
		$sql = "UPDATE company_express_info SET express_id = '$express_id',state_id = '$state_id',city_id = '$city_id',district_id = '$district_id',name = '$name',payment = '$payment',fee = '$fee',address = '$address',post_code = '$post_code',contact_name = '$contact_name',mobile = '$mobile',telphone = '$telphone',bank_id = '$bank_id',tax = '$tax',body = '$body' WHERE id = '$id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
	

		if(isset($template[$template_id])){
		
			// $sql2 = "UPDATE company_express_template_info IF(id='$template_id',SET is_default='Y',SET is_default='N') WHERE  company_id='$company_id' AND express_id='$express_id'";

				$sql1 = "UPDATE company_express_template_info SET is_default='N' WHERE express_id='$express_id' AND company_id='$company_id'";
				mysql_query($sql1,$_mysql_link_);

				$sql2 = "UPDATE company_express_template_info SET is_default='Y' WHERE id='$template_id' AND express_id='$express_id' AND company_id='$company_id'";

				mysql_query($sql2,$_mysql_link_);

		}
	}

	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}

if(!empty($_GET['aa'])){
	$name = $_GET['aa'];
	$sql = "SELECT name,id FROM main_express_info WHERE name like '%$name%'";
	$result = mysql_query($sql,$_mysql_link_);
	$num = array();
	while($expressInfo = mysql_fetch_object($result)){
		$data['expressInfo'][] = array(
        	'name' 	=> 	$expressInfo->name,
			'id' 	=> 	$expressInfo->id
		);
	}
	
	$express_id = $data['expressInfo'][0]['id'];
	$sql3 = "SELECT id,name FROM  company_express_template_info WHERE express_id='$express_id' AND company_id='$company_id'";
	$result3 = mysql_query($sql3,$_mysql_link_);

	while($templateInfo = mysql_fetch_object($result3)){

		$data['templateInfo'][] =array(
			'name'=>$templateInfo->name,
			'id'=>$templateInfo->id
		);
	}

	echo json_encode($data);
	exit;
}

if($_GET['bb']){
	$express_id = intval($_GET['express_id']);

	$sql3 = "SELECT id,name FROM  company_express_template_info WHERE express_id='$express_id' AND company_id='$company_id'";
	$result3 = mysql_query($sql3,$_mysql_link_);
	$data2 = array();
	while($templateInfo = mysql_fetch_object($result3)){

		$data2[] =array(
			'name'=>$templateInfo->name,
			'id'=>$templateInfo->id
		);
	}

	echo json_encode($data2);
	exit;

}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");