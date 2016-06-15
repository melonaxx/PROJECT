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
if(!empty($_GET['id'])){
	echo $_GET['id'];
}
if(!empty($_GET['staff_id'])){
	$main['staff_id'] 	= intval($_GET['staff_id']);
	$main['years']		= intval($_GET['years']);
	$main['month']		= intval($_GET['month']);
	$main['day']		= intval($_GET['day']);
	$main['pam']		= replace_safe($_GET['pam']);
	$main['department'] = intval($_GET['department']);
	$main['attendance'] = intval($_GET['attendance']);
}
if(!empty($_POST['send'])){
	$staff 		= intval($_POST['staff_id']);
	$years 		= intval($_POST['years']);
	$month 		= intval($_POST['month']);
	$day   		= intval($_POST['day']);
	$pam 		= replace_safe($_POST['pam']);
	$department = intval($_POST['department']);
	$manager_id	= intval($_POST['attendance']);
	$content 	= replace_safe($_POST['content']);
	// $manager_id = $_SESSION['_application_info_']['staff_id'];
	$data = "$years-$month-$day";
	$sql = "SELECT id FROM company_attendance WHERE staff_id = '$staff' AND company_id = '$company_id' AND action_date = '$data'";
	$result = mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($result) == 0){
		$sql = "INSERT INTO company_attendance SET manager_id = '$manager_id',company_id = '$company_id',action_date = '$data',staff_id = '$staff',$pam = '$content'";
		mysql_query($sql,$_mysql_link_);
	}else{
		while($company_attendance = mysql_fetch_object($result)){
			$sql = "UPDATE company_attendance SET manager_id = '$manager_id',$pam = '$content' WHERE company_id = '$company_id' AND id = '{$company_attendance->id}'";
			mysql_query($sql,$_mysql_link_);
		}
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>考勤完成！<br/><br/><br/><br/></center>";
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");