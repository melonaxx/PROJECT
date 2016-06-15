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

$StoreLocation	= array();
$StoreLocation['Area']		= "库区";
$StoreLocation['Shelves']	= "货架";
$StoreLocation['Location']	= "货位";

$location	= $_REQUEST['location'];
$store_id	= intval($_REQUEST['store_id']);
$area_id	= intval($_REQUEST['area_id']);
$shelves_id	= intval($_REQUEST['shelves_id']);
$company_id	= $_SESSION['_application_info_']["company_id"];

if(!isset($StoreLocation[$location]))
{
	//---- 没有选择仓库类型 ----
	illegal_operation();
}

$sql	= "SELECT name FROM store_info WHERE company_id='$company_id' AND id='$store_id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	//---- 所属仓库错误/不属于当前公司 ----
	illegal_operation();
}
$main['store_name']		= mysql_result($result, 0, 'name');
$main['location_name']	= $StoreLocation[$location];
$main['location_type']	= $location;
$parent_id	= 0;
switch($location)
{
	case "Area":
		//---- 无上级id ----
		$area_id	= 0;
		$shelves_id	= 0;
		$parent_id	= 0;
		break;
	case "Shelves":
		//---- 上级id 为 库区id ----
		$shelves_id	= 0;
		$parent_id	= $area_id;
		$sql	= "SELECT name FROM store_location WHERE company_id='$company_id' AND id='$area_id' AND location_type='Area'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result) < 1)
		{
			//---- 所属库区不存在 ----
			illegal_operation();
		}
		$display_area['name']	= mysql_result($result, 0, 'name');
		$xtpl->assign("display_area", $display_area);
		$xtpl->parse("main.display_area");
		break;
	case "Location":
		//---- 上级id 为 货架id ----
		$area_id	= 0;
		$parent_id	= $shelves_id;
		$sql	= "SELECT name, parent_id FROM store_location WHERE company_id='$company_id' AND id='$shelves_id' AND location_type='Shelves'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result) < 1)
		{
			//---- 所属货架不存在 ----
			illegal_operation();
		}
		$display_shelves['name']	= mysql_result($result, 0, 'name');
		$tmp	= mysql_result($result, 0, 'parent_id');
		$xtpl->assign("display_shelves", $display_shelves);
		$xtpl->parse("main.display_shelves");

		//---- 查找货架的上级 ----
		$sql	= "SELECT name FROM store_location WHERE company_id='$company_id' AND id='$tmp' AND location_type='Area'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result) < 1)
		{
			//---- 所属库区不存在 ----
			illegal_operation();
		}
		$display_area['name']	= mysql_result($result, 0, 'name');
		$xtpl->assign("display_area", $display_area);
		$xtpl->parse("main.display_area");
		break;
}

if(!empty($_POST['name']))
{
	$name	= replace_safe($_POST['name'], 20);
	$body	= replace_safe($_POST['body'], 80);

	$sql = "INSERT INTO store_location SET company_id='".$_SESSION['_application_info_']['company_id']."', store_id='$store_id', location_type='$location', parent_id='$parent_id', name='$name', body='$body'";
	mysql_query($sql, $_mysql_link_);

	$sql	= "SELECT COUNT(id) AS c FROM store_location WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND store_id='$store_id' AND location_type='$location' AND parent_id='$parent_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	$total	= mysql_result($result, 0, 'c');
	$id = mysql_insert_id($_mysql_link_);
	if($location == "Area")
	{
		$sql	= "UPDATE store_info SET total='$total' WHERE id='$store_id'";
		mysql_query($sql, $_mysql_link_);
	}
	else
	{
		$sql	= "UPDATE store_location SET total='$total' WHERE id='$parent_id'";
		mysql_query($sql, $_mysql_link_);
	}
	if($location == 'Location')
	{
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		// echo "parent.location.replace(parent.location.href);";
		echo "parent.$('#my".$location." ul').append('<li><div class=\"mySto\" >".$name."</div><div class=\"mySto2\"><a class=\"setUp\" target=\"_blank\" href=\"/stock/stock_product_match.php?id=".$id."\">商品设置</a><input type=\"hidden\" name=\"area_id\" value=\"".$id."\"><span class=\"myMod\" onclick=\"".$location."_M(".$id.")\" ><img src=\"/images/iconfont-mod.svg\" width=\"16px;\" height=\"32px;\" /></span><span class=\"myDel\" onclick=\"".$location."_D(".$id.")\" ><img src=\"/images/iconfont-del.svg\" width=\"16px;\" height=\"32px;\" /></span></div></li>')";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
		exit;
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	// echo "parent.location.replace(parent.location.href);";
	echo "parent.$('#my".$location." ul').append('<li><div class=\"mySto3\" onclick=\"".$location."(".$id.")\" id=\"L".$id."\" >".$name."</div><div class=\"mySto4\"><input type=\"hidden\" name=\"area_id\" value=\"".$id."\"><span class=\"myMod\" onclick=\"".$location."_M(".$id.")\" ><img src=\"/images/iconfont-mod.svg\" width=\"16px;\" height=\"32px;\" /></span><span class=\"myDel\" onclick=\"".$location."_D(".$id.")\" ><img src=\"/images/iconfont-del.svg\" width=\"16px;\" height=\"32px;\" /></span></div></li>')";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}
$main['store_id']	= $store_id;
$main['area_id']	= $area_id;
$main['shelves_id']	= $shelves_id;

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
