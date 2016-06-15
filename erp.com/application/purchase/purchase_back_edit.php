<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
// include "../strsub.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";


$company_id = $_SESSION['_application_info_']['company_id'];
if(!empty($_GET['addon'])){
	$main['addon'] = $_GET['addon'];
}
$main_id = array();
if(!empty($_POST['send'])){
	$id 	= explode(",",$_POST['main_id']);
	$num 	= '';//定义已是待修改采购单的编号字符串变量
	$tol 	= 0;//定义已经是待采购单的选项数量
	$total 	= 0;//定义修改成功数量
	for($i=0;$i<count($id);$i++){
		$main_id[] = intval($id[$i]);
		$sql = "SELECT `number`,status_audit FROM purchase_main_info WHERE company_id='$company_id' AND id='{$main_id[$i]}' ";
		// var_dump($sql);die;
		$result = mysql_query($sql,$_mysql_link_);
		$number = mysql_result($result,0,0);
		$status = mysql_result($result,0,1);
		if($status=='R')
		{
			// $num.= $number.',';
			$tol++;
		}else{
			$sql = "UPDATE purchase_main_info SET status_audit = 'R' WHERE company_id = '$company_id' AND id = '{$main_id[$i]}'";
			mysql_query($sql,$_mysql_link_);
			$total++;
		}
	}
	// $num = rtrim($num,',');
	if($tol != 0)
	{
		// $str = $num."采购单当前状态为：待修改，不可修改！剩余".$total."条修改成功！";
		$str = "待修改采购单不可再次操作！剩余".$total."条打回修改成功！";
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.$('#warning').text('".$str."');";
		echo "parent.$('#alert').css('display','block');";
		echo "setTimeout(function(){parent.$('#alert').css('display','none');},3000);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>打回完成！<br/><br/><br/><br/></center>";
		exit;
	}else{
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>打回完成！<br/><br/><br/><br/></center>";
		exit;
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");