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
include "../libstr.php";
include "../bind_type.php";
$company_id = $_SESSION['_application_info_']['company_id'];



// 获取当前会话用户出售中的商品列表
$params				= array();
$params['method']	= 'taobao.items.onsale.get';
$params['fields']	= 'num_iid';

$params['nick']		= $_SESSION['_application_info_']['nick'];
$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
$json_data			= json_decode($site_str);

header("Content-Type: text/html; charset=UTF-8");
$product   = array();
$product[] = $json_data->items_onsale_get_response->items->item;

for($i=0;$i<count($product[0]);$i++){

	$pro			= array();
	//获取单个商品详细信息
	$pro['method']	= 'taobao.item.seller.get';
	$pro['fields']	= 'num_iid, modified, title, nick, sku.properties_name, sku.price, sku.quantity, pic_url, stuff_status,approve_status';
	$pro['num_iid'] = $product[0][$i]->num_iid;

	$pro['nick']	= $_SESSION['_application_info_']['nick'];
	$aa				= get_taobao_response($pro, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
	$bb				= json_decode($aa);

	$num_iid = replace_safe($bb->item_seller_get_response->item->num_iid);
	$pic_url = replace_safe($bb->item_seller_get_response->item->pic_url);
	$title   = replace_safe($bb->item_seller_get_response->item->title);
	$nick    = replace_safe($bb->item_seller_get_response->item->nick);
	$modified  = replace_safe($bb->item_seller_get_response->item->modified);
	$stuff_status = replace_safe($bb->item_seller_get_response->item->stuff_status);

	$sql  = "SELECT id FROM user_register_info WHERE nick_name='$nick' AND bind_type='Taobao'";
	$this = mysql_query($sql,$_mysql_link_);
	$answ = mysql_fetch_object($this);
	$user_id = $answ->id;

	if($stuff_status == "new"){
		$product_quality = "New";
	}else{
		$product_quality = "Used";
	}
	$sku = $bb->item_seller_get_response->item->skus->sku;
	if($sku){
		for($n=0;$n<count($sku);$n++){
			$quantity  		  = floatval($sku[$n]->quantity);
			$price  		  = floatval($sku[$n]->price);
			$properties_name  = replace_safe($sku[$n]->properties_name);

			$prop 	= str_replace(";",":",$properties_name);
			$prop 	= explode(":",$prop);
			$number = replace_safe($num_iid.$prop[1].$prop[5]);

			$sql    = "SELECT last_date, product_id FROM product_related_info WHERE format_list='$number' AND company_id='$company_id'";
			$result = mysql_query($sql,$_mysql_link_);
			$re 	= mysql_fetch_object($result);
			$last_date 	= $re->last_date;
			$product_id = $re->product_id;
			if($product_id){
				if(strtotime($modified) > strtotime($last_date)){

					$sql = "UPDATE product_related_info SET name='$title', format_list='$number', get_date=NOW(), last_date='$modified' WHERE company_id='$company_id' AND product_id='$product_id' AND info_id='$num_iid'";
					mysql_query($sql,$_mysql_link_);

					$sql = "UPDATE product_detail SET price_display='$price' WHERE id='$product_id'";
					mysql_query($sql,$_mysql_link_);

					$sql 	= "UPDATE product_info SET
					number='$number',
					total='$quantity',
					product_quality='$product_quality',
					image='$pic_url',
					name='$title' WHERE company_id='$company_id' AND id='$product_id'";
					mysql_query($sql,$_mysql_link_);

					$sql 	= "SELECT format_id_1, format_id_2, value_id_1, value_id_2 FROM product_detail WHERE id='$product_id'";
					$result = mysql_query($sql,$_mysql_link_);
					while($res = mysql_fetch_object($result)){
						$sql = "UPDATE product_format_name SET name ='$prop[2]' WHERE company_id='$company_id' AND id='$res->format_id_1' AND is_delete='N'";
						mysql_query($sql,$_mysql_link_);

						$sql = "UPDATE product_format_name SET name ='$prop[6]' WHERE company_id='$company_id' AND id='$res->format_id_2' AND is_delete='N'";
						mysql_query($sql,$_mysql_link_);

						$sql = "UPDATE product_format_value SET name ='$prop[3]' WHERE company_id='$company_id' AND id='$res->value_id_1' AND is_delete='N'";
						mysql_query($sql,$_mysql_link_);

						$sql = "UPDATE product_format_value SET name ='$prop[7]' WHERE company_id='$company_id' AND id='$res->value_id_2' AND is_delete='N'";
						mysql_query($sql,$_mysql_link_);
					}
				}
			}else{
				$sql = "INSERT INTO product_related_info SET company_id='$company_id', user_id='$user_id', info_id='$num_iid', name='$title', format_list='$number', get_date=NOW(), last_date='$modified'";
				mysql_query($sql,$_mysql_link_);

				$sql 	= "SELECT id FROM product_format_name WHERE company_id='$company_id' AND name='$prop[2]' AND is_delete='N'";
				$resukt = mysql_query($sql,$_mysql_link_);
				$res 	= mysql_fetch_object($resukt);
				$format_id_1 = intval($res->id);
				if($format_id_1){
					$sql 	 = "SELECT id FROM product_format_value WHERE company_id='$company_id' AND format_id='$format_id_1' AND is_delete='N' AND body='$prop[3]'";
					$result1 = mysql_query($sql,$_mysql_link_);
					$res 	 = mysql_fetch_object($result1);
					if($res->id){
						$value_id_1 =intval($res->id);
					}else{
						$sql = "INSERT INTO product_format_value SET company_id='$company_id', format_id='$format_id_1', body='$prop[3]'";
						mysql_query($sql,$_mysql_link_);
						$value_id_1 = intval(mysql_insert_id($_mysql_link_));
					}
				}else{
					$sql ="INSERT INTO product_format_name SET company_id='$company_id', name='$prop[2]'";
					mysql_query($sql,$_mysql_link_);
					$format_id_1 = intval(mysql_insert_id($_mysql_link_));
				}

				$sql 	= "SELECT id FROM product_format_name WHERE company_id='$company_id' AND name='$prop[6]' AND is_delete='N'";
				$resukt = mysql_query($sql,$_mysql_link_);
				$res 	= mysql_fetch_object($resukt);
				$format_id_2 = intval($res->id);
				if($format_id_2){
					$sql 	= "SELECT id FROM product_format_value WHERE company_id='$company_id' AND format_id='$format_id_2' AND is_delete='N' AND body='$prop[7]'";
					$result2 = mysql_query($sql,$_mysql_link_);
					$res 	= mysql_fetch_object($result2);
					if($res->id){
						$value_id_2 = intval($res->id);
					}else{
						$sql = "INSERT INTO product_format_value SET company_id='$company_id', format_id='$format_id_2', body='$prop[7]'";
						mysql_query($sql,$_mysql_link_);
						$value_id_2 = intval(mysql_insert_id($_mysql_link_));
					}
				}else{
					$sql ="INSERT INTO product_format_name SET company_id='$company_id', name='$prop[6]'";
					mysql_query($sql,$_mysql_link_);
					$format_id_2 = intval(mysql_insert_id($_mysql_link_));
				}

				$sql 	= "INSERT INTO product_info SET
				company_id='$company_id',
				number='$number',
				total='$quantity',
				product_quality='$product_quality',
				image='$pic_url',
				name='$title'";
				mysql_query($sql,$_mysql_link_);
				$id = mysql_insert_id($_mysql_link_);
				if($id>0){
					$sql = "INSERT INTO product_detail SET id='$id', price_display='$price', format_id_1='$format_id_1', format_id_2='$format_id_2', value_id_1='$value_id_1', value_id_2='$value_id_2'";
					mysql_query($sql,$_mysql_link_);
					$sqll = "INSERT INTO product_sales SET id='$id'";
					mysql_query($sqll,$_mysql_link_);
					$sql = "UPDATE product_related_info SET product_id='$id' WHERE company_id='$company_id' AND format_list='$number'";
					mysql_query($sql,$_mysql_link_);

					$sql   = "SELECT id FROM store_info WHERE company_id='$company_id' AND (store_status='Default' OR store_status='Normal')";
					$this1 = mysql_query($sql,$_mysql_link_);
					while($res = mysql_fetch_object($this1)){
						$store_id = $res->id;
						$sql = "INSERT INTO store_product SET company_id='$company_id', store_id='$store_id',product_id='$id'";
						mysql_query($sql,$_mysql_link_);
						$sql = "INSERT INTO store_related SET company_id='$company_id', store_id='$store_id',product_id='$id'";
						mysql_query($sql,$_mysql_link_);
					}
				}
			}
		}
	}else{
		$number = replace_safe($num_iid);
		$sql    = "SELECT last_date, product_id FROM product_related_info WHERE format_list='$num_iid' AND company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		$re 	= mysql_fetch_object($result);
		$last_date 	= $re->last_date;
		$product_id = $re->product_id;
		if($product_id){
			if(strtotime($last_date) > strtotime($modified)){
				$sql = "UPDATE product_related_info SET name='$title', format_list='$number', get_date=NOW(), last_date='$modified' WHERE company_id='$company_id' AND product_id='$product_id' AND info_id='$num_iid'";
				mysql_query($sql,$_mysql_link_);
				var_dump($sql);

				$sql = "UPDATE product_detail SET price_display='$price' WHERE id='$product_id'";
				mysql_query($sql,$_mysql_link_);

				$sql 	= "UPDATE product_info SET
				number='$number',
				total='$quantity',
				product_quality='$product_quality',
				image='$pic_url',
				name='$title' WHERE company_id='$company_id' AND id='$product_id'";
				mysql_query($sql,$_mysql_link_);
			}
		}else{
			$sql = "INSERT INTO product_related_info SET company_id='$company_id', user_id='$user_id', info_id='$num_iid', name='$title', format_list='$num_iid', get_date=NOW(), last_date='$modified'";
			mysql_query($sql,$_mysql_link_);
			$sql 	= "INSERT INTO product_info SET
			company_id='$company_id',
			number='$number', total='$quantity',
			product_quality='$product_quality',
			image='$pic_url',
			name='$title'";
			mysql_query($sql,$_mysql_link_);
			$id = mysql_insert_id($_mysql_link_);
			if($id>0){
				$sql = "INSERT INTO product_detail SET id='$id', price_display='$price'";
				mysql_query($sql,$_mysql_link_);
				$sql = "UPDATE product_related_info SET product_id='$id' WHERE company_id='$company_id' AND format_list='$num_iid'";
				mysql_query($sql,$_mysql_link_);
				$sqll = "INSERT INTO product_sales SET id='$id'";
				mysql_query($sqll,$_mysql_link_);

				$sql   = "SELECT id FROM store_info WHERE company_id='$company_id' AND (store_status='Default' OR store_status='Normal')";
				$this2 = mysql_query($sql,$_mysql_link_);
				while($res = mysql_fetch_object($this2)){
					$store_id = $res->id;
					$sql = "INSERT INTO store_product SET company_id='$company_id', store_id='$store_id',product_id='$id'";
					mysql_query($sql,$_mysql_link_);
					$sql = "INSERT INTO store_related SET company_id='$company_id', store_id='$store_id',product_id='$id'";
					mysql_query($sql,$_mysql_link_);
				}
			}
		}
	}
}
header("Location: product_correspondence.php");
exit;


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");