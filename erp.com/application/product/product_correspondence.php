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
include "../libstr.php";
include "../bind_type.php";
$company_id = $_SESSION['_application_info_']['company_id'];

//已选择的店铺
$sqlc = "SELECT name, user_id FROM company_shop WHERE company_id='$company_id' AND bind_type='Taobao'";
$resultc = mysql_query($sqlc,$_mysql_link_);
while($res = mysql_fetch_object($resultc)){
	$main['shop_name']	= $res->name;
}

// 获取当前会话用户出售中的商品列表
$params				= array();
$params['method']	= 'taobao.items.onsale.get';
$params['fields']	= 'num_iid,total_results';
if(!empty($_GET['find'])){
	$params['q']	= $_GET['find'];
}
$params['page_no']  = $page;
$params['page_size']=$_SESSION["_application_info_"]["page_size"];

$params['nick']		= $_SESSION['_application_info_']['nick'];
$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
$json_data			= json_decode($site_str);


// header("Content-Type: text/html; charset=UTF-8");
// print_r($site_str);
// exit;

// header("Content-Type: text/html; charset=UTF-8");
$product   = array();
$product[] = $json_data->items_onsale_get_response->items->item;
$main['total_results'] = $json_data->items_onsale_get_response->total_results;
	//---- 处理分页 ----
	if(!is_array($page_param))
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total_results'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$arr = array();

$num = 1;
for($i=0;$i<count($product[0]);$i++){
	$pro			= array();
	//获取单个商品详细信息
	$pro['method']	= 'taobao.item.seller.get';
	$pro['fields']	= 'num_iid, title, sku, sku.properties_name, sku.price, sku.quantity, pic_url';
	$pro['num_iid'] = $product[0][$i]->num_iid;

	$pro['nick']	= $_SESSION['_application_info_']['nick'];
	$aa				= get_taobao_response($pro, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
	$bb				= json_decode($aa);
	$num_iid = replace_safe($bb->item_seller_get_response->item->num_iid);
	$pic_url = replace_safe($bb->item_seller_get_response->item->pic_url);
	$title   = replace_safe($bb->item_seller_get_response->item->title);
	$sku     = $bb->item_seller_get_response->item->skus->sku;
	if($sku){
		for($n=0;$n<count($sku);$n++){
			$properties_name  = replace_safe($sku[$n]->properties_name);
			$prop 	= str_replace(";",":",$properties_name);
			$prop 	= explode(":",$prop);
			$number = replace_safe($num_iid.$prop[1].$prop[5]);

			$sql    = "SELECT product_id,id FROM product_related_info WHERE company_id='$company_id' AND format_list='$number' ";
			$result = mysql_query($sql,$_mysql_link_);
			$res 	= mysql_fetch_object($result);
			if($res){
				$sql 	= "SELECT i.name, i.image, d.value_id_1, d.value_id_2 FROM product_info AS i LEFT JOIN product_detail AS d ON i.id=d.id WHERE i.id='$res->product_id' AND i.company_id='$company_id' AND i.is_delete='N'";
				$query1  = mysql_query($sql,$_mysql_link_);
				while($dbRow = mysql_fetch_object($query1)){
					if($dbRow){
						$arr['name']  = $dbRow->name;
						$arr['image'] = $dbRow->image;
						$sql = "SELECT body FROM product_format_value WHERE id='$dbRow->value_id_1' AND company_id='$company_id' AND is_delete='N'";
						$query = mysql_query($sql,$_mysql_link_);
						$re 	= mysql_fetch_object($query);
						$arr['value_1'] = $re->body;
						$sql = "SELECT body FROM product_format_value WHERE id='$dbRow->value_id_2' AND company_id='$company_id' AND is_delete='N'";
						$query1 = mysql_query($sql,$_mysql_link_);
						$r  	= mysql_fetch_object($query1);
						$arr['value_2'] = $r->body;
					}else{
						$arr['name']    = "";
						$arr['image']   = "";
						$arr['value_1'] = "";
						$arr['value_2'] = "";
					}
				}
			}else{
				$arr['name']    = "";
				$arr['image']   = "";
				$arr['value_1'] = "";
				$arr['value_2'] = "";
				$arr['id']      = "";
				$arr['product_id'] = "";
			}

			$arr['id']      = $res->id;
			$arr['num_iid'] = $num_iid;
			$arr['product_id']  = $res->product_id;
			$arr['format_list'] = $number;
			$arr['format']  = $prop[3]."、".$prop[7];
			$arr['pic_url'] = $pic_url;
			$arr['title']   = $title;
			$arr['num']     = $num++;

			$xtpl->assign("arr", $arr);
			$xtpl->parse("main.arr");
		}
	}else{
		$number = replace_safe($num_iid);
		$sql    = "SELECT product_id,id FROM product_related_info WHERE company_id='$company_id' AND format_list='$number' ";
		$result = mysql_query($sql,$_mysql_link_);
		$res 	= mysql_fetch_object($result);
		if($res){
			$sql 	= "SELECT i.name, i.image, d.value_id_1, d.value_id_2 FROM product_info AS i LEFT JOIN product_detail AS d ON i.id=d.id WHERE i.id='$res->product_id' AND i.company_id='$company_id' AND is_delete='N'";
			$query1  = mysql_query($sql,$_mysql_link_);
			while($dbRow = mysql_fetch_object($query1)){
				if($dbRow){
					$arr['name']  = $dbRow->name;
					$arr['image'] = $dbRow->image;
					$sql = "SELECT body FROM product_format_value WHERE id='$dbRow->value_id_1' AND company_id='$company_id' AND is_delete='N'";
					$query3 = mysql_query($sql,$_mysql_link_);
					$re 	= mysql_fetch_object($query3);
					$arr['value_1'] = $re->body;
					$sql = "SELECT body FROM product_format_value WHERE id='$dbRow->value_id_2' AND company_id='$company_id' AND is_delete='N'";
					$query4 = mysql_query($sql,$_mysql_link_);
					$r  	= mysql_fetch_object($query4);
					$arr['value_2'] = $r->body;
				}else{
					$arr['name']    = "";
					$arr['image']   = "";
					$arr['value_1'] = "";
					$arr['value_2'] = "";
				}
			}
		}else{
			$arr['name']    = "";
			$arr['image']   = "";
			$arr['value_1'] = "";
			$arr['value_2'] = "";
			$arr['id']      = "";
			$arr['product_id'] = "";
		}

		$arr['id']      = $res->id;
		$arr['num_iid'] = $num_iid;
		$arr['product_id']  = $res->product_id;
		$arr['format_list'] = $number;
		$arr['format']  = "";
		$arr['pic_url'] = $pic_url;
		$arr['title']   = $title;
		$arr['num']     = $num++;

		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");
	}
}





$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


