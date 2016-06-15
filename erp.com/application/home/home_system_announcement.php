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

//查询待审核订单数
$sql = "SELECT COUNT(*) as total_shenhe FROM order_info LEFT JOIN order_delivery ON order_info.id = order_delivery.id WHERE order_info.company_id='$company_id' AND order_info.is_audit='N' AND order_info.is_delete='N' AND order_info.status='N' AND order_delivery.delivery_status='Untreated' ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_shenhe']		= mysql_result($result, 0, 'total_shenhe');

//查询待打单配货订单数
$sql = "SELECT COUNT(*) as total_dadan FROM order_info LEFT JOIN order_delivery ON order_info.id = order_delivery.id WHERE order_info.company_id='$company_id' AND order_info.is_audit='Y' AND order_info.is_delete='N' AND order_info.status='N' AND order_delivery.delivery_status='Untreated' ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_dadan']		= mysql_result($result, 0, 'total_dadan');

//查询待验货订单数
$sql = "SELECT COUNT(*) as total_yanhuo FROM order_info LEFT JOIN order_delivery ON order_info.id = order_delivery.id WHERE order_info.company_id='$company_id' AND order_info.is_audit='Y' AND order_info.is_delete='N' AND order_info.status='F' AND order_delivery.delivery_status='Picking' ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_yanhuo']		= mysql_result($result, 0, 'total_yanhuo');

//查询待称重订单数
$sql = "SELECT COUNT(*) as total_chengzhong FROM order_info LEFT JOIN order_delivery ON order_info.id = order_delivery.id WHERE order_info.company_id='$company_id' AND order_info.is_audit='Y' AND order_info.is_delete='N' AND order_info.status='I' AND order_delivery.delivery_status='Picking' ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_chengzhong']		= mysql_result($result, 0, 'total_chengzhong');

//查询待发货订单数
$sql = "SELECT COUNT(*) as total_daifa FROM order_info LEFT JOIN order_delivery ON order_info.id = order_delivery.id WHERE order_info.company_id='$company_id' AND order_info.is_audit='N' AND order_info.is_delete='N' AND status='W' AND order_delivery.delivery_status='Picking' ";
$result = mysql_query($sql,$_mysql_link_);
$main['total_daifa']		= mysql_result($result, 0, 'total_daifa');

//查询已发货订单数
$sql = "SELECT COUNT(*) as total_yifa FROM order_info LEFT JOIN order_delivery ON order_info.id = order_delivery.id WHERE order_info.company_id='$company_id' AND order_info.is_audit='N' AND order_info.is_delete='N' AND order_delivery.delivery_status='Finish'";
$result = mysql_query($sql,$_mysql_link_);
$main['total_yifa']		= mysql_result($result, 0, 'total_yifa');

//查询异常订单数
// $sql = "SELECT COUNT(*) as total_yichang FROM order_info WHERE order_info.company_id='$company_id' AND order_info.is_audit='N' AND order_info.is_delete='N' AND order_info.status='N' AND order_delivery.delivery_status='Finish' ";
// $result = mysql_query($sql,$_mysql_link_);
// $main['total_yichang']		= mysql_result($result, 0, 'total_yichang');


//获取总通知条数用来判断是否需要在页面下方出现‘更多》’
$sql = "SELECT count(*) AS total FROM company_notice_info WHERE company_id = '{$company_id}'";
$result 		= mysql_query($sql,$_mysql_link_);
$main['total']  = mysql_result($result,0,'total');

//获取首页通知列表标题和发布时间
$sql = "SELECT id,name,action_date FROM company_notice_info WHERE company_id = '{$company_id}' ORDER BY action_date DESC LIMIT 13 ";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$not 			= array();
	$not['id'] 		= $dbRow->id;
	$not['name'] 	= $dbRow->name;
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
		$notice['begin_date']  = substr($dbRow->begin_date,0,16);
		$notice['end_date']    = $dbRow->end_date;
		$notice['body'] 	   = $dbRow->body;
		$notice['person'] 	   = $dbRow->person;
		$notice['sign'] 	   = $dbRow->sign;
		$notice['action_date'] = substr($dbRow->action_date,0,16);
		$xtpl->assign("notice", $notice);
		$xtpl->parse("main.notice");
	}
}else{
	$sql = "SELECT id,name,nick,begin_date,end_date,body,person,sign,action_date FROM company_notice_info WHERE company_id = '{$company_id}' ORDER BY action_date DESC LIMIT 1 ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$notice 			  = array();
		$notice['id'] 		  = $dbRow->id;
		$notice['name'] 	  = $dbRow->name;
		$notice['nick'] 	   = $dbRow->nick;
		$notice['begin_date']  = substr($dbRow->begin_date,0,16);
		$notice['end_date']    = $dbRow->end_date;
		$notice['body'] 	   = $dbRow->body;
		$notice['person'] 	   = $dbRow->person;
		$notice['sign'] 	   = $dbRow->sign;
		$notice['action_date'] = substr($dbRow->action_date,0,16);
		$xtpl->assign("notice", $notice);
		$xtpl->parse("main.notice");
	}
}
// var_dump($notice['na']);die;
$notice_id = intval($notice['id']);
//从通知事项表获取对应事项
$sql = "SELECT id,body FROM company_notice_item WHERE company_id = '{$company_id}' AND notice_id = '{$notice_id}'";

$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$shixiang = array();
	$shixiang['id'] = $dbRow->id;
	$shixiang['body'] = $dbRow->body;
	$xtpl->assign("shixiang", $shixiang);
	$xtpl->parse("main.shixiang");
}







$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");