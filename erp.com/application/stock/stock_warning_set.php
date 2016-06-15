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

if(!empty($_REQUEST['box'])){
	$main['box']   =  $_REQUEST['box'];
	$main['ware']  =  $_REQUEST['ware'];
}

if(!empty($_REQUEST['made'])){
	$box		 	= 	explode(',',$_REQUEST['box']);
	$ware 			= 	replace_safe($_REQUEST['ware']);
	$type 			= 	replace_safe($_REQUEST['type']);
	$lower 			= 	intval($_REQUEST['lower']);
	$upper 			= 	intval($_REQUEST['upper']);
	$product_id		=	array();
	$yes 			= 	0;//定义修改成功数量
	$no 			= 	0;//定义修改失败数量
	//判断如果只存在下限，则只修改下限
	if(!empty($lower) && empty($upper) && $type=='low')
	{
		for($i=0;$i<count($box);$i++){
			$product_id	=	intval($box[$i]);
			//查看商品原有上限值
			$sql = "SELECT upper FROM store_product WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$product_id' ";
			$res = mysql_query($sql,$_mysql_link_);
			$old_upper = mysql_result($res,0,0);
			if($old_upper > 0 && $lower > $old_upper)
			{
				$no++;
			}else{
				//修改下限值，同时设置该商品为有预警状态；
				$sql = "UPDATE store_product SET `lower` = '$lower', is_warning = 'Y' WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$product_id' ";
				mysql_query($sql,$_mysql_link_);
				$yes++;
			}

		}
		echo "<script>\n";
		echo "alert('设置成功".$yes."条失败".$no."条！');";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>设置成功！<br/><br/><br/><br/></center>";
		exit;
	}
	//判断如果只存在上限，则只修改上限
	if(empty($lower) && !empty($upper) && $type=='up')
	{
		for($i=0;$i<count($box);$i++){
			$product_id	=	intval($box[$i]);
			//查看商品原有上限值
			$sql = "SELECT lower FROM store_product WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$product_id' ";
			$res = mysql_query($sql,$_mysql_link_);
			$old_lower = mysql_result($res,0,0);
			if($old_lower > 0 && $old_lower > $upper)
			{
				$no++;
			}else{
				//修改下限值，同时设置该商品为有预警状态；
				$sql = "UPDATE store_product SET `upper` = '$upper', is_warning = 'Y' WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$product_id' ";
				mysql_query($sql,$_mysql_link_);
				$yes++;
			}

		}
		echo "<script>\n";
		echo "alert('设置成功".$yes."条失败".$no."条！');";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>设置成功！<br/><br/><br/><br/></center>";
		exit;
	}
	if(!empty($lower) && !empty($upper) && $lower > $upper && $type=='all')
	{
		echo "<script>\n";
		echo "alert('下限不能大于上限，请重新设置！');";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace(parent.location.href);";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>设置成功！<br/><br/><br/><br/></center>";
		exit;
	}
	if(!empty($lower) && !empty($upper) && $type=='all' && $upper>=$lower)
	{
		for($i=0;$i<count($box);$i++){
			$product_id	=	intval($box[$i]);
			//修改上限下限值，同时设置该商品为有预警状态；
			$sql = "UPDATE store_product SET `lower` = '$lower',`upper` = '$upper', is_warning = 'Y' WHERE company_id = '$company_id' And store_id = '$ware' AND product_id = '$product_id' ";
			mysql_query($sql,$_mysql_link_);

		}
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
