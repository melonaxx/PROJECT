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



$company_id   = $_SESSION['_application_info_']["company_id"];
$staff_id     = $_SESSION['_application_info_']["staff_id"];
//获取当前日期以前15天日期列表
$days=array();

for($i=15;$i>0;$i--){
    $days['day']=date("m.d",strtotime('-'.$i.'day'));
    $xtpl->assign('days',$days);
    $xtpl->parse('main.days');
}
$bishu  = array();
$shishou = array();
$kehu = array();
for($i=15;$i>0;$i--){
    $days['day']=date("Y-m-d",strtotime('-'.$i.'day'));
    //获取最近15天每天的发货笔数
    $sql = "SELECT COUNT(*) as total_bishu FROM order_info LEFT JOIN order_delivery ON order_info.id = order_delivery.id WHERE order_info.company_id='$company_id' AND order_info.is_delete='N' AND order_info.status='S' AND order_info.unusual_id='0' AND order_delivery.delivery_status='Finish' AND INSTR(order_delivery.action_date,'{$days['day']}')";

    $result = mysql_query($sql,$_mysql_link_);
    $bishu['total_bishu'] = mysql_result($result,0, 'total_bishu');
    $xtpl->assign('bishu',$bishu);
    $xtpl->parse('main.bishu');
    //获取最近15天每天的支付客户数
    $sql = "SELECT s.crm_user_id FROM finance_order AS f LEFT JOIN order_source AS s ON f.order_id = s.id LEFT JOIN order_info As i ON f.order_id = i.id WHERE f.company_id = '$company_id' AND f.payment_status != 'N' AND INSTR(i.order_date,'{$days['day']}')";
	$result = mysql_query($sql,$_mysql_link_);
	$kehushu = array();
	while($dbRow = mysql_fetch_object($result)){
		$kehushu[]	= $dbRow->crm_user_id;
	}
	$kehushu = array_unique($kehushu);
	$kehu['kehushu'] = count($kehushu);
	$xtpl->assign('kehu',$kehu);
	$xtpl->parse('main.kehu');

	//获取最近15天每天的支付金额数
	$sql = "SELECT sum(f.real_amount) AS qianshu FROM finance_order AS f LEFT JOIN order_info As i ON f.order_id = i.id WHERE f.company_id = '$company_id' AND f.payment_status <> 'N' AND INSTR(i.order_date,'{$days['day']}') ";
	$result = mysql_query($sql,$_mysql_link_);
	$shishou['qianshu'] = intval(mysql_result($result,0 ,'qianshu'));
	$xtpl->assign('shishou',$shishou);
	$xtpl->parse('main.shishou');
}
//查询待审核订单数
$sql = "SELECT COUNT(*) as total_shenhe FROM order_info WHERE company_id='$company_id' AND is_audit='N' AND is_delete='N' AND status='N' AND unusual_id = 0 ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_shenhe']		= mysql_result($result, 0, 'total_shenhe');

//查询待打单配货订单数
$sql = "SELECT COUNT(*) as total_dadan FROM order_info WHERE company_id='$company_id' AND is_audit='Y' AND is_delete='N' AND status='N' AND unusual_id = 0 ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_dadan']		= mysql_result($result, 0, 'total_dadan');

//查询待验货订单数
$sql = "SELECT COUNT(*) as total_yanhuo FROM order_info WHERE company_id='$company_id' AND is_delete='N' AND status='F' AND unusual_id = 0 ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_yanhuo']		= mysql_result($result, 0, 'total_yanhuo');

//查询待称重订单数
$sql = "SELECT COUNT(*) as total_chengzhong FROM order_info  WHERE company_id='$company_id' AND is_delete='N' AND status='I' AND unusual_id = 0 ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_chengzhong']		= mysql_result($result, 0, 'total_chengzhong');

//查询待发货订单数
$sql = "SELECT COUNT(*) as total_daifa FROM order_info WHERE company_id='$company_id' AND is_delete='N' AND status='W' AND unusual_id=0 ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_daifa']		= mysql_result($result, 0, 'total_daifa');

//查询已发货订单数
$date = date('Y-m-d');//获取当天日期以便查询当天发货订单数
$sql = "SELECT COUNT(*) as total_yifa FROM order_delivery WHERE company_id='$company_id' AND delivery_status='Finish' AND INSTR(action_date,'$date')";
$result = mysql_query($sql,$_mysql_link_);
$main['total_yifa']		= mysql_result($result, 0, 'total_yifa');

//查询异常订单数
$sql = "SELECT COUNT(*) as total_yichang FROM order_info WHERE company_id='$company_id' AND is_delete='N' AND unusual_id <> 0 ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_yichang']		= mysql_result($result, 0, 'total_yichang');

//获取最近七天的时间
$date = array();
$date[] = date('Y-m-d');
$date[] = date('Y-m-d',strtotime('-1 day'));
$date[] = date('Y-m-d',strtotime('-2 day'));
$date[] = date('Y-m-d',strtotime('-3 day'));
$date[] = date('Y-m-d',strtotime('-4 day'));
$date[] = date('Y-m-d',strtotime('-5 day'));
$date[] = date('Y-m-d',strtotime('-6 day'));


$sql = "SELECT COUNT(*) AS total_1 FROM after_sale_info WHERE company_id = '$company_id' AND is_delete='N' AND status='N' AND INSTR(action_date,'$date[0]') ";
$result 			= mysql_query($sql,$_mysql_link_);
$main['total_1']	= mysql_result($result, 0, 'total_1');

$sql = "SELECT COUNT(*) AS total_2 FROM after_sale_info WHERE company_id = '$company_id' AND is_delete='N' AND status='N' AND (INSTR(action_date,'$date[1]') OR INSTR(action_date,'$date[2]'))";
$result 			= mysql_query($sql,$_mysql_link_);
$main['total_2']	= mysql_result($result, 0, 'total_2');

$sql = "SELECT COUNT(*) AS total_3 FROM after_sale_info WHERE company_id = '$company_id' AND is_delete='N' AND status='N' AND (INSTR(action_date,'$date[3]') OR INSTR(action_date,'$date[4]') OR INSTR(action_date,'$date[5]'))";
$result 			= mysql_query($sql,$_mysql_link_);
$main['total_3']	= mysql_result($result, 0, 'total_3');

$sql = "SELECT COUNT(*) AS total_4 FROM after_sale_info WHERE company_id = '$company_id' AND Date(action_date) <= '$date[6]' AND status='N' AND is_delete='N' ";
$result 			= mysql_query($sql,$_mysql_link_);
$main['total_4']	= mysql_result($result, 0, 'total_4');

//获取总通知条数用来判断是否需要在页面下方出现‘更多》’
$sql = "SELECT count(*) AS total FROM company_notice_info WHERE company_id = '{$company_id}'";
$result 			= mysql_query($sql,$_mysql_link_);
$main['total']  	= mysql_result($result,0,'total');

//获取首页通知列表标题和发布时间
$sql = "SELECT id,name,action_date FROM company_notice_info WHERE company_id = '{$company_id}' ORDER BY action_date DESC LIMIT 7 ";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$not 				= array();
	$not['id'] 			= $dbRow->id;
	$not['name'] 		= $dbRow->name;
	$not['action_date'] = substr($dbRow->action_date,5,11);
	$xtpl->assign("not", $not);
	$xtpl->parse("main.not");
}
//判断是否有点击的通知，有则显示点击通知信息，没有则获取最新通知内容
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$sql = "SELECT id,name,nick,begin_date,end_date,body,person,sign,action_date FROM company_notice_info WHERE company_id = '{$company_id}' AND id = '{$id}'";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$notice 			   = array();
		$notice['id'] 		   = $dbRow->id;
		$notice['name'] 	   = $dbRow->name;
		$notice['nick'] 	   = $dbRow->nick;
		$notice['body'] 	   = $dbRow->body;
		$notice['sign'] 	   = $dbRow->sign;
		$notice['person'] 	   = $dbRow->person;
		$notice['end_date']    = $dbRow->end_date;
		$notice['begin_date']  = substr($dbRow->begin_date,0,16);
		$notice['action_date'] = substr($dbRow->action_date,0,16);
		$xtpl->assign("notice", $notice);
		$xtpl->parse("main.notice");
	}
}else{
	$sql = "SELECT id,name,nick,begin_date,end_date,body,person,sign,action_date FROM company_notice_info WHERE company_id = '{$company_id}' ORDER BY action_date DESC LIMIT 1 ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$notice 			  	= array();
		$notice['id'] 		  	= $dbRow->id;
		$notice['name'] 	  	= $dbRow->name;
		$notice['nick'] 	   	= $dbRow->nick;
		$notice['body'] 	   	= $dbRow->body;
		$notice['sign'] 	   	= $dbRow->sign;
		$notice['person'] 	   	= $dbRow->person;
		$notice['end_date']    	= $dbRow->end_date;
		$notice['begin_date']  	= substr($dbRow->begin_date,0,16);
		$notice['action_date'] 	= substr($dbRow->action_date,0,16);
		$xtpl->assign("notice", $notice);
		$xtpl->parse("main.notice");
	}
}
$notice_id = intval($notice['id']);
//从通知事项表获取对应事项
$sql = "SELECT id,body FROM company_notice_item WHERE company_id = '{$company_id}' AND notice_id = '{$notice_id}'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$shixiang 			= array();
	$shixiang['id'] 	= $dbRow->id;
	$shixiang['body'] 	= $dbRow->body;
	$xtpl->assign("shixiang", $shixiang);
	$xtpl->parse("main.shixiang");
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");