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

$company_id = $_SESSION['_application_info_']['company_id'];
//查看短信剩余条数
$sql = "SELECT message_remain FROM company_name WHERE id='$company_id' ";
$res = mysql_query($sql,$_mysql_link_);
if($res)
{
	$message_remain         = mysql_result($res,0,0);
	$main['message_remain'] = $message_remain;
}


$chaxun = array();
$chaxun[] = "r.company_id = '".$company_id."'";
if(!empty($_REQUEST['status'])){
	$status = replace_safe($_REQUEST['status']);
	if(!empty($status)){
		$chaxun[] = "r.status = '".$status."'";
		$main['status'] = $status;
		$page_param     = array();
        $page_param['status']     = replace_safe($_REQUEST['status'], 20, false, false);
	}
}
if(!empty($_REQUEST['shop_id'])){
	$shop_id = replace_safe($_REQUEST['shop_id']);
	if(!empty($shop_id)){
		$chaxun[] = "s.user_id = '".$shop_id."'";
		$main['shop_id'] = $shop_id;
		$page_param     = array();
        $page_param['shop_id']     = replace_safe($_REQUEST['shop_id'], 20, false, false);
	}
}
$where = '';
if(count($chaxun) > 0){
	$where = "WHERE ".implode(' AND ', $chaxun);
}

//---分页---
$sql    = "SELECT COUNT(*) AS total FROM crm_message_logs  AS r LEFT JOIN order_source AS s ON r.order_id = s.id LEFT JOIN order_receiver AS e ON r.order_id = e.id ".$where;
$result = mysql_query($sql, $_mysql_link_);
$main['total']      = mysql_result($result, 0, 'total');
//处理分页
$page_param         = array();
$main['page_info']  = erp_page_info($main['total'], $page, $page_param);
$limit  = ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//获取店铺名称
$sql1 = "SELECT id,shop_name FROM user_register_info ";
$res1 = mysql_query($sql1,$_mysql_link_);
$shop = array();
$shopName = array();
while($dbRow = mysql_fetch_object($res1)){
	$shopName['id'] 	= $dbRow->id;
	$shopName['shop_name'] 	= $dbRow->shop_name;
	$shop[$dbRow->id] 	= $dbRow->shop_name;

	$xtpl->assign('shopName',$shopName);
	$xtpl->parse('main.shopName');
}
//获取收件人名字
$sql2 = "SELECT crm_user_id,name FROM crm_user_related WHERE company_id='$company_id' ";
$res2 = mysql_query($sql2,$_mysql_link_);
$name = array();
while($dbRow = mysql_fetch_object($res2)){
	$name[$dbRow->crm_user_id] = $dbRow->name;
}
$type = array('Deliver'=>'发货通知','Remind'=>'短信催付','Payment'=>'已付款通知');
// $status = array(
// 		0 	=>	'发送成功',
// 		1 	=>	'请求参数缺失',
// 		2 	=>	'请求参数格式错误',
// 		3 	=>	'账户余额不足',
// 		4 	=>	'关键词屏蔽',
// 		5 	=>	'未找到对应id的模板',
// 		6 	=>	'添加模板失败',
// 		7 	=>	'模板不可用',
// 		8 	=>	'同一手机号30秒内重复提交相同的内容',
// 		9 	=>	'同一手机号5分钟内重复提交相同的内容超过3次',
// 		10 	=>	'手机号黑名单过滤',
// 		11 	=>	'接口不支持GET方式调用',
// 		12 	=>	'接口不支持POST方式调用',
// 		13 	=>	'营销短信暂停发送',
// 		14 	=>	'解码失败',
// 		15 	=>	'签名不匹配',
// 		16 	=>	'签名格式不正确',
// 		17 	=>	'24小时内同一手机号发送次数超过限制',
// 		18 	=>	'签名校验失败',
// 		19 	=>	'请求已失效',
// 		20 	=>	'不支持的国家地区',
// 		21 	=>	'解密失败',
// 		22 	=>	'1小时内同一手机号发送次数超过限制',
// 		23 	=>	'发往模板支持的国家列表之外的地区',
// 		24 	=>	'添加告警设置失败',
// 		25 	=>	'手机号和内容个数不匹配',
// 		26 	=>	'流量包错误',
// 		27 	=>	'未开通金额计费',
// 		28 	=>	'运营商错误',
// 		-1 	=>	'非法的apikey',
// 		-2 	=>	'API没有权限',
// 		-3 	=>	'IP没有权限',
// 		-4 	=>	'访问次数超限',
// 		-5 	=>	'访问频率超限',
// 		-50 =>	'未知异常',
// 		-51 =>	'系统繁忙',
// 		-52 =>	'充值失败',
// 		-53 =>	'提交短信失败',
// 		-54 =>	'记录已存在',
// 		-55 =>	'记录不存在',
// 		-57 =>	'用户开通过固定签名功能，但签名未设置',
// 		100 =>	'24小时内同一用户只能发15条信息',
// 		101 =>	'同一内容同一用户120内只能发送一次',
// 		102 =>	'24小时同一用户同一内容不能超过3条',
// 		103 =>	'不符合模板设定规则',
// 		104 =>	'余额不足'
// 	);
$status = array('Y'=>'发送成功','N'=>'发送失败');
$sql = "SELECT r.id,r.template_id,r.content,r.type,r.status,r.reason,r.action_date,s.user_id,s.crm_user_id,s.bind_number,e.mobile FROM crm_message_logs AS r LEFT JOIN order_source AS s ON r.order_id = s.id LEFT JOIN order_receiver AS e ON r.order_id = e.id ".$where." ORDER BY r.action_date desc LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
$no = 1;
while($dbRow = mysql_fetch_object($result)){
	$logs					= array();
	$logs['no'] 			= $no++;
	$logs['id'] 			= $dbRow->id;
	$logs['mobile'] 		= $dbRow->mobile;
	$logs['reason'] 		= $dbRow->reason;
	$logs['content'] 		= $dbRow->content;
	$logs['action_date'] 	= $dbRow->action_date;
	$logs['type'] 			= $type[$dbRow->type];
	$logs['bind_number'] 	= $dbRow->bind_number;
	$logs['template_id'] 	= $dbRow->template_id;
	$logs['shop_id'] 		= $shop[$dbRow->user_id];
	$logs['status'] 		= $status[$dbRow->status];
	$logs['crm_user_id'] 	= $name[$dbRow->crm_user_id];

	$xtpl->assign('logs',$logs);
	$xtpl->parse('main.logs');
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");