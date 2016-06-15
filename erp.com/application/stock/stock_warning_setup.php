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

if(!empty($_REQUEST['setup'])){
	$ware			 =  $_REQUEST['ware'];
	$setup			 =  $_REQUEST['setup'];
	$main['setup']   =  $_REQUEST['setup'];
	$main['ware']  	 =  $_REQUEST['ware'];
	$sql = "SELECT lower,upper FROM store_product WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$setup' ";
	$res = mysql_query($sql,$_mysql_link_);
	if(mysql_result($res,0,0)>0)
	{
		$main['lower']   = mysql_result($res,0,0);
	}else{
		$main['lower']	 = '';
	}
	if(mysql_result($res,0,1)>0)
	{
		$main['upper']   = mysql_result($res,0,1);
	}else{
		$main['upper']	 = '';
	}

}
if(!empty($_REQUEST['made'])){
	$setup		 	= 	intval($_REQUEST['setup']);
	$ware 			= 	intval($_REQUEST['ware']);
	$lower 			= 	intval($_REQUEST['lower']);
	$upper 			= 	intval($_REQUEST['upper']);
	//获取原有上下限值
	$sql = "SELECT lower,upper FROM store_product WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$setup'  ";
	$res = mysql_query($sql,$_mysql_link_);
	$old_lower = mysql_result($res,0,0);
	$old_upper = mysql_result($res,0,1);
	//判断如果存在上限不存在下限，则只修改下限值
	if(!empty($upper) && empty($lower))
	{
		//判断上限是否大于原有下限值
		if($upper < $old_lower)
		{
			echo "<script>\n";
			echo "alert('上限值小于已有下限值，请重新设置！');";
			echo "parent.$('#MessageBox').modal('hide');\n";
			echo "parent.location.replace(parent.location.href);";
			echo "</script>\n";
			echo "<center><br/><br/><br/><br/>设置失败！<br/><br/><br/><br/></center>";
			exit;
		}
		$sql = "UPDATE store_product SET `upper` = '$upper', is_warning = 'Y' WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$setup' ";
		mysql_query($sql,$_mysql_link_);

		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>设置成功！<br/><br/><br/><br/></center>";
		exit;
	}
	// 判断如果存在下限不存在上限，则只修改下限值
	if(empty($upper) && !empty($lower))
	{
		//判断下限值是否大于已有上限值，上限值为-1默认没有上限值
		if($old_upper>0 && $lower>$old_upper)
		{
			echo "<script>\n";
			echo "alert('下限值大于已有上限值，请重新设置！');";
			echo "parent.$('#MessageBox').modal('hide');\n";
			echo "parent.location.replace(parent.location.href);";
			echo "</script>\n";
			echo "<center><br/><br/><br/><br/>设置失败！<br/><br/><br/><br/></center>";
			exit;
		}
		$sql = "UPDATE store_product SET `lower` = '$lower', is_warning = 'Y' WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$setup' ";
		mysql_query($sql,$_mysql_link_);

		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>设置成功！<br/><br/><br/><br/></center>";
		exit;
	}
	//判断上限值不能小于下限值
	if(!empty($lower) && !empty($upper) && $lower > $upper)
	{
		echo "<script>\n";
		echo "alert('上限不能低于下限！');";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>设置成功！<br/><br/><br/><br/></center>";
		exit;
	}
	//修改上限下限值，同时设置该商品为有预警状态；
	if(!empty($lower) && !empty($upper) && $upper>=$lower)
	{
		$sql = "UPDATE store_product SET `lower` = '$lower',`upper` = '$upper', is_warning = 'Y' WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$setup' ";
		mysql_query($sql,$_mysql_link_);

		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>设置成功！<br/><br/><br/><br/></center>";
		exit;
	}


}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
