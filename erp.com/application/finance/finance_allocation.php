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


//日期
$rq = date("Y-m-d");
$main['rq'] = $rq;

$company_id = $_SESSION['_application_info_']['company_id'];
$staff_id 	= $_SESSION['_application_info_']['staff_id'];

//显示账户
//判断转出账户显示内容不在转入账户显示
if(!empty($_POST['name'])){
	$out = replace_safe($_POST['name']);
	$sql = "SELECT id,name FROM finance_bank WHERE company_id = '$company_id' AND status='Y' AND id != '$name'";
	$result 		= mysql_query($sql,$_mysql_link_);
	while($sting 	= mysql_fetch_object($result)){
		$data[] 	= array(
			'name' 	=> $sting->name,
			'id'   	=> $sting->id
		);
	}
	echo json_encode($data);
	exit;
}else{
	$sql = "SELECT id,name FROM finance_bank WHERE company_id='$company_id' AND status='Y'";
	$result = mysql_query($sql,$_mysql_link_);
	while ($dbRow = mysql_fetch_object($result)) {
		$list_bank1 		= array();
		$list_bank1['id'] 	= $dbRow->id;
		$list_bank1['name'] = $dbRow->name;
		$xtpl->assign("list_bank1", $list_bank1);
		$xtpl->parse("main.list_bank1");
	}
}

//显示转出账户
$sql = "SELECT id,name FROM finance_bank WHERE company_id='$company_id' AND status='Y'";
$result = mysql_query($sql,$_mysql_link_);
$bankInfo = array();
while ($dbRow = mysql_fetch_object($result)) {
	$list_bank 			= array();
	$list_bank['id'] 	= $dbRow->id;
	$list_bank['name'] 	= $dbRow->name;
	$xtpl->assign("list_bank", $list_bank);
	$xtpl->parse("main.list_bank");
	$bankInfo[$dbRow->id] = $dbRow->name;
}

if(!empty($_POST)){
	$business_date 	= replace_safe($_POST['business_date']);
	$output_bank_id = intval($_POST['output_bank']);
	$input_bank_id 	= intval($_POST['input_bank']);
	$money 			= round($_POST['money'],2);
	$body 			= replace_safe($_POST['body']);
	if(!isset($bankInfo[$output_bank_id]) || !isset($bankInfo[$input_bank_id])){
		$output_bank_id = 0;
		$input_bank_id 	= 0;
	}
	$sql = "INSERT INTO finance_cash_allocation SET  company_id='$company_id',action_date=NOW(), business_date='$business_date', output_bank_id='$output_bank_id', input_bank_id='$input_bank_id', `money`='$money', body='$body', staff_id = '$staff_id'";
	mysql_query($sql, $_mysql_link_);
	//转出账户余额执行减额
	$sql = "UPDATE finance_bank SET balance = balance-'$money' WHERE company_id = '$company_id' AND id='$output_bank_id' ";
	mysql_query($sql,$_mysql_link_);
	//转入账户余额执行增加
	$sql = "UPDATE finance_bank SET balance = balance+'$money' WHERE company_id = '$company_id' AND id='$input_bank_id' ";
	mysql_query($sql,$_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


