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

$company_id   = $_SESSION['_application_info_']["company_id"];
$staff_id     = $_SESSION['_application_info_']["staff_id"];

$sql 	= "SELECT id,name FROM company_staff_info WHERE company_id = '{$company_id}'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$fabu[$dbRow->id] = $dbRow->name;
}

if(!empty($_POST)){
	$name       = replace_safe($_POST['name']);
	$nick       = replace_safe($_POST['nick']);
	$begin_date = replace_safe($_POST['begin_date']);
	$end_date 	= replace_safe($_POST['end_date']);
	$body 		= replace_safe($_POST['body']);
	$number 	= $_POST['number'];
	$person     = replace_safe($_POST['person']);
	$sign       = replace_safe($_POST['sign']);
	//执行通知公告添加
	$sql	= "INSERT INTO company_notice_info SET company_id = '{$company_id}', name = '{$name}', nick = '{$nick}', begin_date = '{$begin_date}', end_date = '{$end_date}', body = '{$body}', person = '{$person}', sign = '{$sign}', action_date = NOW()";
	mysql_query($sql,$_mysql_link_);
	// 获取新插入通知id
	$notice_id = mysql_insert_id($_mysql_link_);
	$shixiang = array();
	// 执行事项添加
		foreach ($number as $key => $value) {
			if($value!='')
			{
				$no = $key+1;
				$shixiang = $no."、".replace_safe($value);
				$sql	= "INSERT INTO company_notice_item SET company_id = '{$company_id}', notice_id = '{$notice_id}', body = '{$shixiang}'";
				mysql_query($sql,$_mysql_link_);
			}
		}

	// 执行待办事项添加
	// $source	='Notice';
	// $status 	='N';
	// $sql	= "INSERT INTO company_schedule SET company_id = '{$company_id}', notice_id = '{$notice_id}', name = '{$name}', body = '{$body}', source= '{$source}', person = '{$person}', status = '{$status}', end_date = '{$begin_date}', action_date = NOW(), staff_id ='{$staff_id}' ";
	// mysql_query($sql,$_mysql_link_);

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


