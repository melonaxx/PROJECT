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
include "../bind_type.php";

$company_id = $_SESSION['_application_info_']['company_id'];

//订单日期
$rq = date("Y-m-d");
$main['rq'] = $rq;


//商品搜索
if(!empty($_POST['aa'])){
	$aa  = replace_safe($_POST['aa']);
	$array = explode(",",replace_safe($_POST['b']));
	$store_id = $_POST['store_id'];
	$addon	= array();
	$addon[]	= "product_info.company_id = '$company_id'";
	$addon[]	= "product_info.is_delete='N'";
	$addon[]	= "store_product.store_id='$store_id'";
	$addon[]	= "store_product.is_warning='N'";
	$addon[]	= "INSTR(product_info.name,'$aa')";
	for($j=0;$j<count($array);$j++){
		if($array[$j]){
			$array[$j] = intval($array[$j]);
			$addon[] = "product_info.id <> ".$array[$j];
		}
	}

	$where  = "";
	$where .= "WHERE ".implode(" AND ", $addon);
	$sql = "SELECT product_info.id,product_info.name,product_info.image,
	product_detail.parts_id ,product_detail.price_display,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
	FROM product_info
	LEFT JOIN product_detail on product_info.id = product_detail.id LEFT JOIN store_product ON store_product.product_id=product_detail.id ".$where." limit 15";
	$this = mysql_query($sql,$_mysql_link_);
	$arr = array();
	while($StoreInfo = mysql_fetch_object($this)){
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result);

		$arr[] = array(
		'image'          => $StoreInfo->image,
		'name' 		     => $StoreInfo->name,
		'id'   		     => $StoreInfo->id,
		'part_name'	   	 => $res->name,
		'price_display'  => $StoreInfo->price_display,
		'value_id_1'     => $StoreInfo->value_id_1,
		'value_id_2'     => $StoreInfo->value_id_2,
		'value_id_3'     => $StoreInfo->value_id_3,
		'value_id_4'     => $StoreInfo->value_id_4,
		'value_id_5'     => $StoreInfo->value_id_5
		);
		for($i=0;$i<count($arr);$i++){
			for($j=1;$j<=5;$j++){
				$format_id = $arr[$i]['value_id_'.$j];
				$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
				$result1 = mysql_query($sql1,$_mysql_link_);
				while($re = mysql_fetch_object($result1)){
					$arr[$i]['value_id_'.$j] = $re->body;
				}

			}
		}
	}
	echo json_encode($arr);
	exit;

}
//条码搜索
if(!empty($_GET['bar'])){
	$bar = replace_safe($_POST['bar']);
	$array = explode(",",replace_safe($_POST['b']));
	$addon	= array();
	$addon[]	= "product_info.company_id = '$company_id'";
	$addon[]	= "product_info.is_delete='N'";
	$addon[]	= "product_info.bar_code='$bar'";
	$where  = "";
	$where .= "WHERE ".implode(" AND ", $addon);
	$sql = "SELECT product_info.id,product_info.name,product_info.image,
	product_detail.parts_id ,product_detail.price_display,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
	FROM product_info
	LEFT JOIN product_detail on product_info.id = product_detail.id ".$where;
	$this = mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($this)){
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result);
		$arr = array(
			'image'			 => $StoreInfo->image,
			'name' 		     => $StoreInfo->name,
			'id'   		     => $StoreInfo->id,
			'part_name'   	 => $res->name,
			'price_display'  => $StoreInfo->price_display,
			'value_id_1'     => $StoreInfo->value_id_1,
			'value_id_2'     => $StoreInfo->value_id_2,
			'value_id_3'     => $StoreInfo->value_id_3,
			'value_id_4'     => $StoreInfo->value_id_4,
			'value_id_5'     => $StoreInfo->value_id_5
		);
		for($j=1;$j<=5;$j++){
			$format_id = $arr[$i]['value_id_'.$j];
			$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
			$result1 = mysql_query($sql1,$_mysql_link_);
			while($re = mysql_fetch_object($result1)){
				$arr['value_id_'.$j] = $re->body;
			}
		}
	}
	echo json_encode($arr);
	exit;
}

//客户识别
if(!empty($_POST['recog'])){
	$recog = replace_safe($_POST['recog']);
	$sql = "SELECT i.nick_name,r.name,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.mobile,r.telphone FROM crm_user_related AS r INNER JOIN crm_user_info AS i ON r.crm_user_id=i.id WHERE (INSTR(r.name,'$recog') OR r.mobile='$recog') AND r.company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$arr = array();
	while($res = mysql_fetch_object($result)){
		$arr[] = array(
			'nick_name'     => $res->nick_name,
			'name'          => $res->name,
			'state_id'      => $res->state_id,
			'city_id'       => $res->city_id,
			'district_id'   => $res->district_id,
			'post_code'     => $res->post_code,
			'address'       => $res->address,
			'company_name'  => $res->company_name,
			'phone'         => $res->telphone,
			'mobile'        => $res->mobile
			);
	}
	echo json_encode($arr);
	exit;
}
//客户change
if(!empty($_POST['change'])){
	$change = replace_safe($_POST['change']);
	$sql = "SELECT i.nick_name,r.name,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.mobile,r.telphone FROM crm_user_related AS r LEFT JOIN crm_user_info AS i ON r.crm_user_id=i.id WHERE INSTR(r.name,'$change')";
	$result = mysql_query($sql,$_mysql_link_);

	while($res = mysql_fetch_object($result)){
		$arr = array();
		$arr = array(
			'nick_name'     => $res->nick_name,
			'name'          => $res->name,
			'state_id'      => $res->state_id,
			'city_id'       => $res->city_id,
			'district_id'   => $res->district_id,
			'post_code'     => $res->post_code,
			'address'       => $res->address,
			'company_name'  => $res->company_name,
			'phone'         => $res->telphone,
			'mobile'        => $res->mobile
			);
	}
	echo json_encode($arr);
	exit;
}
//商品change
if(!empty($_POST['find'])){
	$find = replace_safe($_POST['find']);
	$sql = "SELECT product_info.id,product_info.name,product_info.image,product_detail.parts_id,product_detail.price_display  FROM product_info LEFT JOIN product_detail ON product_info.id = product_detail.id WHERE product_info.id= '$find' AND product_info.company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result)){
		$arr = array();
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result2 = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result2);
		$arr['image']	= $StoreInfo->image;
		$arr['id']		= $StoreInfo->id;
		$arr['name'] 	= $StoreInfo->name;
		$arr['detail']  = $StoreInfo->detail;
		$arr['unit'] 	= $res->name;
		$arr['price_display'] = $StoreInfo->price_display;
	}
	echo json_encode($arr);
	exit;
}

//仓库改变时
if(!empty($_POST['pid'])){
	$pid  = $_POST['pid'];
	$arr = array();
	for($i=0;$i<count($pid);$i++){
		$where  = "WHERE product_info.company_id = '$company_id' AND product_info.is_delete='N' AND store_product.store_id='$store_id' AND product_info.id='$pid[$i]'";
			$sql = "SELECT product_info.id,product_info.name,product_info.image,
		product_detail.parts_id ,product_detail.price_display,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
		FROM product_info
		LEFT JOIN product_detail on product_info.id = product_detail.id LEFT JOIN store_product ON store_product.product_id=product_detail.id ".$where;
		$res = mysql_query($sql,$_mysql_link_);
		$num = mysql_num_rows($res);

		if($num>0){
			$arr[] = "1";
		}else{
			$arr[] = "0";
		}
	}

	echo json_encode($arr);
	exit;
}

//---- 店铺名称 ----
$ListShop 		= array();
$sql = "SELECT user_id FROM  company_related WHERE company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_shop 	= array();
	$list_shop['user_id'] = $StoreInfo->user_id;
	$sql = "SELECT shop_name FROM user_register_info WHERE id='$StoreInfo->user_id'";
	$query = mysql_query($sql,$_mysql_link_);
	while($res = mysql_fetch_object($query)){
		$list_shop['shop_name'] 	   = $res->shop_name;
		$ListShop[$StoreInfo->user_id] = $res->shop_name;
		$xtpl->assign("list_shop", $list_shop);
		$xtpl->parse("main.list_shop");
	}
}

//---- 发货仓库 ----
$ListStore  	= array();
$sql = "SELECT id, name, store_status FROM store_info WHERE (store_status = 'Normal' OR store_status = 'Default') AND company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_store	= array();
	$list_store['id']			= $StoreInfo->id;
	$list_store['name']			= $StoreInfo->name;
	$ListStore[$StoreInfo->id]   = $StoreInfo->name;
	$xtpl->assign("list_store", $list_store);
	$xtpl->parse("main.list_store");
}

//---- 到账账户 ----
$ListAccount 	= array();
$sql = "SELECT id,name,is_default FROM finance_bank where status='Y' AND company_id='$company_id'";
$result	= mysql_query($sql,$_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_account = array();
	$list_account['id']			 = $StoreInfo->id;
	$list_account['name']	     = $StoreInfo->name;
	$list_account['is_default']	 = $StoreInfo->is_default;
	$ListAccount[$StoreInfo->id] = $StoreInfo->name;
	$xtpl->assign("list_account", $list_account);
	$xtpl->parse("main.list_account");
}

//---- 销售渠道 ----
$ListQudao 		= array();
$sql = "SELECT id,name FROM company_sales where company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_qudao = array();
	$list_qudao['id']		= $StoreInfo->id;
	$list_qudao['name']	    = $StoreInfo->name;
	$ListQudao[$Store->id]  = $StoreInfo->name;
	$xtpl->assign("list_qudao", $list_qudao);
	$xtpl->parse("main.list_qudao");
}

//----- 快递公司 ----
$ListDeliver 	= array();
$sql = "SELECT express_id,name FROM company_express_info WHERE status = 'Y' AND company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_deliver	= array();
	$list_deliver['express_id']          = $StoreInfo->express_id;
	$list_deliver['name']			     = $StoreInfo->name;
	$ListDeliver[$StoreInfo->express_id] = $StoreInfo->name;
	$xtpl->assign("list_deliver", $list_deliver);
	$xtpl->parse("main.list_deliver");
}

//-----进行新订单的添加------
if(isset($_POST['submit'])){
	if(!empty($_POST['bind_number'])){
		$bind_number   = replace_safe($_POST['bind_number']);		                //订单编号
	}else{
		// $bind_number 	= replace_safe(insert_company_number($company_id, "order"));   ---- 自动生成员工编号 ----
		$bind_number    = intval(time());                          //自动生成订单编号
	}
	$related_order 	   = replace_safe($_POST['related_order']);	    //关联订单
	$customer_text     = replace_safe($_POST['customer_text']);     //订单备注(客服)
	$order_text        = replace_safe($_POST['order_text']);        //客户留言(定制信息)
	$company_name	   = replace_safe($_POST['company_name']);	    //公司名称

	//order_receiver
	$name 			   = replace_safe($_POST['name']);		        //收件人
	$mobile			   = replace_safe($_POST['mobile']);			//手机号码
	$phone			   = replace_safe($_POST['phone']);			    //固定电话
	$state_id		   = intval($_POST['state_id']);                //省份
	$city_id           = intval($_POST['city_id']);                 //城市
	$district_id       = intval($_POST['district_id']);             //县
	$address           = replace_safe($_POST['address']);           //详细地址
	$post_code		   = intval($_POST['post_code']);	            //邮政编码
	$need_invoice	   = replace_safe($_POST['need_invoice']);	    //发票收据
	$company_name	   = replace_safe($_POST['company_name']);	    //公司名称
	$tax_number	       = replace_safe($_POST['tax_number']);	    //税号
	$tax_type          = replace_safe($_POST['tax_type']);          //发票类型
	$tax_text	       = replace_safe($_POST['tax_text']);	    	//发票明细
	$tax_title	       = replace_safe($_POST['tax_title']);	    	//发票抬头
	$store_id		   = intval($_POST['store_id']);	            //发货仓库
	$real_amount       = floatval($_POST['real_amount']);           //实付金额
	$shop_title		   = intval($_POST['shop_title']);	    		//店铺名称
	$tax_bank_name	   = replace_safe($_POST['tax_bank_name']);	    //开户银行名称
	$tax_bank_number   = replace_safe($_POST['tax_bank_number']);	//发票银行帐号
	$tax_number   	   = replace_safe($_POST['tax_number']);	    //税号
	$bank_id		   = intval($_POST['bank_id']);	     	 		//到账账户
	$total_yh          = floatval($_POST['total_yh']);         		//商品总优惠
	$post_fee	   	   = floatval($_POST['post_fee']);	     		//快递运费
	$theory_amount     = floatval($_POST['theory_amount']);    		//应付金额
	// $real_amount       = floatval($_POST['real_amount']);      	//实付金额
	$remain_amount     = floatval($_POST['remain_amount']);         //顾客欠款或者尾款
	$payment_status    = replace_safe($_POST['payment_status']);    //付款状态
	$is_audit          = replace_safe($_POST['is_audit']);          //审核状态
	$order_date 	   = replace_safe($_POST['order_date']);		//下单时间
	$company_name	   = replace_safe($_POST['company_name']);	 	//公司名称
	$express_id	   	   = intval($_POST['express_id']);	    		//快递公司
	$body              = replace_safe($_POST['body']);              //定制内容
	$total             = intval($_POST['total']);					//定制数量
	$sure              = replace_safe($_POST['status']);            //确认情况
	$bind_type         = replace_safe($_POST['bind_type']);			//销售渠道
	$need_dz           = replace_safe($_POST['need_dz']);			//需要定制
	$nick_name         = replace_safe($_POST['nick_name']);			//用户昵称
	$total_yh          = replace_safe($_POST['total_yh']);			//商品总优惠
	$express_number    = replace_safe($_POST['express_number']);	//快递单号
	$weight_number	   = floatval($_POST['weight_number']);			//商品重量
	$price             = $_POST['price'];			        //单价
	$number            = $_POST['number'];                  //商品数量
	$discount          = $_POST['discount'];         		//商品优惠
	$good_name         = $_POST['good_name'];	            //商品id
	$pay         	   = $_POST['pay'];	                    //商品总价
	$content           = $_POST['content'];                 //商品备注
	$freight_seller    = floatval($_POST['freight_seller']);
	$delivery_status   = "Untreated";
	$now 			   = date('Y-m-d');

	if($sure == "N"){
		$status = "N";
	}elseif($sure == "Y"){
		$status = "R";
	}else{
		$status = "N";
	}

	if($is_audit != "Y" && $is_audit != "N"){
		$is_audit = "Y";
	}

	if(!isset($ListShop[$shop_title])){
		$shop_title    = 0;
	}
	if(!isset($ListStore[$store_id])){
		$store_id  	   = 0;
	}
	if(!isset($ListAccount[$bank_id])){
		$bank_id       = 0;
	}
	if(!isset($ListDeliver[$express_id])){
		$express_id    = 0;
	}

	if(!isset($ERPBindInfo[$bind_type]))
	{
		$bind_type	= "System";
	}

	$sql = "INSERT INTO order_info (
		company_id,
		order_date,
		is_audit
		) VALUES (
		'$company_id',
		'$order_date',
		'$is_audit'
		)";
	mysql_query($sql,$_mysql_link_);
	$row = mysql_affected_rows($_mysql_link_);
	if($row){
		$order_id = mysql_insert_id($_mysql_link_);
		//客户与公司对应关系表
		$sql = "SELECT name,crm_user_id FROM crm_user_related WHERE company_id='$company_id' AND mobile='$mobile'";
		$result = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result);
		$crm_user_id = intval($res->crm_user_id);
		if(!$res){
			//未知客户关系表
			$sql = "INSERT INTO crm_user_known(id,name) VALUES ('',$name)";
			mysql_query($sql,$_mysql_link_);
			$rows = mysql_affected_rows($_mysql_link_);
			if($rows){
				$bind_id = mysql_insert_id($_mysql_link_);
				//客户基本信息
				$sql = "INSERT INTO crm_user_info(id,bind_type,bind_id,nick_name)VALUES('','Unknown','$bind_id','$nick_name')";
				mysql_query($sql,$_mysql_link_);
				$crm_id = mysql_insert_id($_mysql_link_);
				$sql = "INSERT INTO crm_user_related(
				id,
				company_id,
				crm_user_id,
				state_id,
				city_id,
				district_id,
				post_code,
				address,
				name,
				company_name,
				telphone,
				mobile,
				tax_type,
				tax_number,
				tax_bank_name,
				tax_bank_number,
				tax_title
				)VALUES(
				'',
				'$company_id',
				'$crm_id',
				'$state_id',
				'$city_id',
				'$district_id',
				'$post_code',
				'$address',
				'$name',
				'$company_name',
				'$phone',
				'$mobile',
				'$tax_type',
				'$tax_number',
				'$tax_bank_name',
				'$tax_bank_number',
				'$tax_title'
				)";
				mysql_query($sql,$_mysql_link_);
				//原订单
				$sql = "INSERT INTO order_source (
				id,
				company_id,
				user_id,
				bind_number,
				crm_user_id,
				related_order,
				order_text,
				customer_text
				) VALUES (
				'$order_id',
				'$company_id',
				'$shop_title',
				'$bind_number',
				'$crm_id',
				'$related_order',
				'$order_text',
				'$customer_text')";
				mysql_query($sql,$_mysql_link_);

			}
		}else{
			$sql = "INSERT INTO order_source (
			id,
			company_id,
			user_id,
			bind_type,
			bind_number,
			crm_user_id,
			related_order,
			order_text,
			customer_text
			) VALUES (
			'$order_id',
			'$company_id',
			'$shop_title',
			'$bind_type',
			'$bind_number',
			'$crm_user_id',
			'$related_order',
			'$order_text',
			'$customer_text')";
			mysql_query($sql,$_mysql_link_);
		}

		for($i=0;$i<count($good_name);$i++){
			$good = intval($good_name[$i]);
			$price[$i]     = floatval($price[$i]);
			$number[$i]    = intval($number[$i]);
			$discount[$i]  = floatval($discount[$i]);
			$pay[$i]       = floatval($pay[$i]);
			$content[$i]   = replace_safe($content[$i]);
			//订单的商品信息
			$sql = "INSERT INTO order_product(
			id,
			company_id,
			order_id,
			store_id,
			product_id,
			price,
			total,
			discount,
			payment,
			content
			)VALUES(
			'',
			'$company_id',
			'{$order_id}',
			'{$store_id}',
			'$good',
			'{$price[$i]}',
			'{$number[$i]}',
			'{$discount[$i]}',
			'{$pay[$i]}',
			'{$content[$i]}'
			)";
			mysql_query($sql,$_mysql_link_);
			if($is_audit == "Y"){
				$sql = "UPDATE store_related SET
				available_total = available_total-'{$number[$i]}',
				lock_total = lock_total+'{$number[$i]}'
				WHERE company_id='$company_id' AND product_id='$good' AND store_id='$store_id'";
				mysql_query($sql,$_mysql_link_);

				$sql = "UPDATE store_product SET
				total_available = total_available-'{$number[$i]}',
				total_lock = total_lock+'{$number[$i]}'
				WHERE company_id='$company_id' AND product_id='$good' AND store_id='$store_id'";
				mysql_query($sql,$_mysql_link_);
			}
		}
		$sql = "INSERT INTO order_receiver(
		id,
		company_id,
		name,
		phone,
		mobile,
		state_id,
		city_id,
		district_id,
		post_code,
		address,
		company_name,
		need_invoice,
		tax_type,
		tax_number,
		tax_bank_name,
		tax_bank_number,
		tax_title,
		tax_text
		)VALUES(
		'$order_id',
		'$company_id',
		'$name',
		'$phone',
		'$mobile',
		'$state_id',
		'$city_id',
		'$district_id',
		'$post_code',
		'$address',
		'$company_name',
		'$need_invoice',
		'$tax_type',
		'$tax_number',
		'$tax_bank_name',
		'$tax_bank_number',
		'$tax_title',
		'$tax_text'
		)";
		mysql_query($sql,$_mysql_link_);
		//财务订单表
		$sql = "INSERT INTO finance_order(
		id,
		company_id,
		order_id,
		bank_id,
		discount,
		theory_amount,
		real_amount,
		payment_status,
		arrears
		)VALUES(
		'',
		'$company_id',
		'$order_id',
		'$bank_id',
		'$total_yh',
		'$theory_amount',
		'$real_amount',
		'$payment_status',
		'$remain_amount'
		)";
		mysql_query($sql,$_mysql_link_);

		if($need_dz == "Y"){
			$sql = "INSERT INTO order_custom(
			id,
			company_id,
			order_id,
			body,
			total,
			status
			)VALUES(
			'',
			'$company_id',
			'$order_id',
			'$body',
			'$total',
			'$status'
			)";
			mysql_query($sql,$_mysql_link_);
		}
		$sql = "INSERT INTO order_operation(
			id,
			company_id,
			purchase_id
			)VALUES(
			'$order_id',
			'$company_id',
			'$bind_type'
			)";
		mysql_query($sql,$_mysql_link_);

		$sql = "INSERT INTO order_delivery(
		id,
		company_id,
		delivery_status
		)VALUES(
		'$order_id',
		'$company_id',
		'$delivery_status'
		)";
		mysql_query($sql,$_mysql_link_);

		$sql = "INSERT INTO order_express_paper(
		id,
		company_id,
		order_id,
		express_id,
		freight_seller,
		freight_buyer,
		number,
		weight
		)VALUES(
		'',
		'$company_id',
		'$order_id',
		'$express_id',
		'$post_fee',
		'$freight_buyer',
		'$express_number',
		'$weight_number'
		)";
		mysql_query($sql,$_mysql_link_);

		$sql = "INSERT INTO order_deliver_paper(
			id,
			company_id,
			order_id
			)VALUES(
			'',
			'$company_id',
			'$order_id'
			)";
		mysql_query($sql,$_mysql_link_);

		$sql = "INSERT INTO order_list_paper(
			id,
			company_id,
			order_id
			)VALUES(
			'',
			'$company_id',
			'$order_id'
			)";
		mysql_query($sql,$_mysql_link_);

		if(!empty($real_amount)){
			$sql = "INSERT INTO finance_cash_logs (id,company_id,business_date,type,company_type,company_name,money,bank_id)
			VALUES ('','$company_id','$now','Input','Custom','$company_name','$real_amount','$bank_id')";
			mysql_query($sql,$_mysql_link_);
			$sql = "UPDATE finance_bank SET balance=balance+'$real_amount' WHERE id='$bank_id' AND company_id='$company_id'";
			mysql_query($sql,$_mysql_link_);
		}



	}
	$_SESSION['error']	= "添加完成";
	header("Location: order_add.php");
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

