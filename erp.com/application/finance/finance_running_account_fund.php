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

//显示科目
$GroupInfo	= array();
$GroupList	= array();
$group	    = array();
$sql = "SELECT id,name,level,sort,parent_id FROM finance_income_topic WHERE company_id='$company_id' AND status='Y' ORDER BY parent_id, sort ";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow=mysql_fetch_object($result))
{
	$GroupInfo[$dbRow->id]['name']				= $dbRow->name;
	$GroupInfo[$dbRow->id]['level']				= $dbRow->level;
	$GroupInfo[$dbRow->id]['sort']				= $dbRow->sort;
	$GroupList[$dbRow->parent_id][$dbRow->id] 	= $dbRow->name;
	$group[$dbRow->name]						= $dbRow->name;
}
$GroupList	= get_sort_by_array($GroupList);
	foreach($GroupList as $ix => $dat)
	{
		$list_group		= array();
		$list_group['id']	= $dat['id'];
		$list_group['name']	= str_repeat("　", $dat['level'] - 1).$dat['name'];
		$xtpl->assign("list_group", $list_group);
		$xtpl->parse("main.list_group");
	}

//显示账户

$sql = "SELECT id,name,`number`,is_default FROM finance_bank WHERE company_id='$company_id' AND status='Y'";
$result = mysql_query($sql,$_mysql_link_);
$bankInfo 					= array();
while ($dbRow = mysql_fetch_object($result)) {
	$bankInfo[$dbRow->id] 	= $dbRow->name;
	if($dbRow->is_default=='Y'){
		$default 			= array();
		$default['id'] 		= $dbRow->id;
		$default['name'] 	= $dbRow->name;
		$xtpl->assign("default", $default);
		$xtpl->parse("main.default");
	}else{
		$list_bank 					= array();
		$list_bank['id'] 			= $dbRow->id;
		$list_bank['name'] 			= $dbRow->name;
		$list_bank['unmber'] 		= $dbRow->number;
		$list_bank['is_default'] 	= $dbRow->is_default;
		$xtpl->assign("list_bank", $list_bank);
		$xtpl->parse("main.list_bank");
	}
}

$com_type = array();
$com_type['Custom'] 	= '客户';
$com_type['Supplier']	= '供应商';
$com_type['Express'] 	= '快递公司';
$com_type['Others'] 	= '其他';
// 执行添加
if(!empty($_POST)){
	$type 			= 'Input';
	$bank_id 		= intval($_POST['bank_id']);
	$money 			= round($_POST['money'],2);
	$body 			= replace_safe($_POST['body']);
	$subject 		= replace_safe($_POST['subject']);
	$buiness_date 	= replace_safe($_POST['business_date']);
	$company_type 	= replace_safe($_POST['company_type']);
	$company_name 	= replace_safe($_POST['company_name']);
	//判断客户类型是否存在，不存在默认为客户
	if(!isset($com_type[$company_type]))
	{
		$company_type = 'Others';
	}
	//判断科目是否存在，不存在科目id设置为0
	if(!isset($group[$subject]))
	{
		$subject = 0;
	}
	//判断结算账户是否存在，不存在科目id设置为0
	if(!isset($bankInfo[$bank_id]))
	{
		$bank_id = 0;
	}

	$sql = "INSERT INTO finance_cash_logs SET bank_id='$bank_id', company_id='$company_id',amount_date=NOW(), business_date='$buiness_date', type='$type', subject='$subject', company_name='$company_name', `money`='$money', body='$body'";
	mysql_query($sql, $_mysql_link_);

	$sql = "UPDATE finance_bank SET balance = balance+'$money' WHERE company_id = '$company_id' AND id='$bank_id' ";
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


