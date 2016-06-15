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


	$sql = "SELECT count(*) AS total FROM company_message_order WHERE company_id='$company_id'";
	$query = mysql_query($sql,$_mysql_link_);
	$main['total']		= mysql_result($query, 0, 'total');

	//---- 处理分页 ----
	if(!is_array($page_param))
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

	if($main['total'] > 0){
		$num = 1;
		$sql = "SELECT price_id,alipay_name,alipay_no,payment,action_date FROM company_message_order WHERE company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($result)){
			$arr = array();
			$sql = "SELECT total FROM main_message_price WHERE id='$res->price_id'";
			$this = mysql_query($sql,$_mysql_link_);
			while($re = mysql_fetch_object($this)){
				$arr['total']   = $re->total;
			}
			$arr['alipay_name'] = $res->alipay_name;
			$arr['alipay_no']   = $res->alipay_no;
			$arr['payment']     = $res->payment;
			$arr['action_date'] = $res->action_date;
			$arr['num']         = $num++;
			$xtpl->assign("arr", $arr);
			$xtpl->parse("main.arr");
		}
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");