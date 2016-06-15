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
$company_id = $_SESSION['_application_info_']['company_id'];
$orderId = replace_safe($_GET['id'],20);

	//订单日期
	$tSql = "SELECT s.bind_type,i.order_date FROM order_source AS s
			LEFT JOIN order_info AS i ON s.id=i.id
			WHERE s.company_id=$company_id AND s.id=$orderId";
	$tRes = mysql_query($tSql,$_mysql_link_);
	$tData = mysql_fetch_object($tRes);
	$rdate = $tData->bind_type;
	if ($rdate == 'Taobao') {
		$rq = $tData->order_date;
		$main['rq'] = $rq;
	}else {
		$rq = date("Y-m-d");
		$main['rq'] = $rq;
	}

	//商品搜索
	if(!empty($_POST['aa'])){
		$aa  = replace_safe($_POST['aa']);
		$array = explode(",",replace_safe($_POST['b']));
		$addon	= array();
		$addon[]	= "product_info.company_id = '$company_id'";
		$addon[]	= "product_info.is_delete='N'";
		$addon[]	= "INSTR(product_info.name,'$aa')";
		for($j=0;$j<count($array);$j++){
			if($array[$j]){
				$array[$j] = intval($array[$j]);
				$addon[] = "product_info.id <> ".$array[$j];
			}
		}

		$where  = "";
		$where .= "WHERE ".implode(" AND ", $addon);
		$sql = "SELECT product_info.id,product_info.name,
			product_detail.parts_id ,product_detail.price_display,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
			FROM product_info
			LEFT JOIN product_detail on product_info.id = product_detail.id ".$where." limit 15";
		$this = mysql_query($sql,$_mysql_link_);
		$arr = array();
		while($StoreInfo = mysql_fetch_object($this)){
			$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
			$result = mysql_query($sql,$_mysql_link_);
			$res = mysql_fetch_object($result);

			$arr[] = array(
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
		$sql = "SELECT product_info.id,product_info.name,
				product_detail.parts_id ,product_detail.price_display,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
				FROM product_info
				LEFT JOIN product_detail on product_info.id = product_detail.id ".$where;
			$this = mysql_query($sql,$_mysql_link_);
			while($StoreInfo = mysql_fetch_object($this)){
				$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
				$result = mysql_query($sql,$_mysql_link_);
				$res = mysql_fetch_object($result);
				$arr = array(
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
		$sql = "SELECT i.nick_name,r.name,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.mobile,r.telphone FROM crm_user_related AS r LEFT JOIN crm_user_info AS i ON r.crm_user_id=i.id WHERE INSTR(r.name,'$recog') OR r.mobile='$recog'";
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
				'phone'         => $res->phone,
				'mobile'        => $res->mobile
				);
		}
		echo json_encode($arr);
		exit;
	}

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
				'phone'         => $res->phone,
				'mobile'        => $res->mobile
				);
		}
		echo json_encode($arr);
		exit;
	}

	if(!empty($_POST['find'])){
		$find = replace_safe($_POST['find']);
		$sql = "SELECT product_info.id,product_info.name,product_detail.parts_id,product_detail.price_display  FROM product_info LEFT JOIN product_detail ON product_info.id = product_detail.id WHERE product_info.id= '$find' AND product_info.company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result)){
			$arr = array();
			$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			$res = mysql_fetch_object($result2);
			$arr['id'] = $StoreInfo->id;
			$arr['name'] = $StoreInfo->name;
			$arr['detail'] = $StoreInfo->detail;
			$arr['unit'] = $res->name;
			$arr['price_display'] = $StoreInfo->price_display;
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
	$sql = "SELECT id,name FROM store_info where store_status ='Normal' AND company_id='$company_id'";
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
	$sql = "SELECT b.name,b.id
			FROM finance_bank AS b
			LEFT JOIN finance_order AS f ON b.id=f.bank_id
			WHERE f.order_id=$orderId";
	$result	= mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_account = array();
		$list_account['id']			 = $StoreInfo->id;
		$list_account['name']	     = $StoreInfo->name;
		// $list_account['is_default']	 = $StoreInfo->is_default;
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


	if(!empty($_GET['id'])){
		$id = $_GET['id'];
	}
	$sql2 = "SELECT product_id,store_id,total,price,discount,payment,content FROM order_product where company_id='$company_id' AND order_id='$id'";
	$result2 = mysql_query($sql2, $_mysql_link_);
	$num = 1;
	$to = array();
	while($Store = mysql_fetch_object($result2))
	{

		$sql = "SELECT product_info.name,product_info.image,
			product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content
			FROM product_info
			LEFT JOIN product_detail on product_info.id = product_detail.id
			WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$Store->product_id'";
		$result3 = mysql_query($sql,$_mysql_link_);
		$a = array();
		$b = "";
		while($StoreInfo = mysql_fetch_object($result3)){
			$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
			$result = mysql_query($sql,$_mysql_link_);
			$res = mysql_fetch_object($result);

			$a = array(
			'image'          => $StoreInfo->image,
			'part_name'	     => $res->name,
			'name' 		     => $StoreInfo->name,
			'value_id_1'     => $StoreInfo->value_id_1,
			'value_id_2'     => $StoreInfo->value_id_2,
			'value_id_3'     => $StoreInfo->value_id_3,
			'value_id_4'     => $StoreInfo->value_id_4,
			'value_id_5'     => $StoreInfo->value_id_5
			);
			for($j=1;$j<=5;$j++){
				$format_id = $a['value_id_'.$j];
				$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
				$result1 = mysql_query($sql1,$_mysql_link_);
				while($re = mysql_fetch_object($result1)){
					$b .= $re->body.",";
				}

			}
		}

		$product = array(
			'image'      => $a['image'],
			'unit'		 => $a['unit'],
			'name'       => $a['name'],
			'format'     => rtrim($b,","),
			'num'        => $num++,
			'product_id' => $Store->product_id,
			'total'      => $Store->total,
			'price'      => number_format($Store->price,2),
			'discount'   => number_format($Store->discount,2),
			'payment'    => number_format($Store->payment,2),
			'content'    => $Store->content,
			'store_id'   => $Store->store_id
			);

		$to[] = $Store->total;
		$xtpl->assign("product", $product);
		$xtpl->parse("main.product");
	}

	$tt = 0;
	for($i=0;$i<count($to);$i++){
		$tt +=$to[$i];
	}

	$sql = "SELECT i.is_audit,i.order_date,
			s.bind_type,s.user_id,s.bind_number,s.related_order,s.order_text,s.customer_text,
			r.name,r.phone,r.mobile,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.need_invoice,r.tax_type,r.tax_number,r.tax_title,r.tax_bank_name,r.tax_bank_number,r.tax_text,
			o.bank_id,o.discount,o.theory_amount,o.real_amount,o.payment_status,o.arrears
			FROM order_info AS i
			LEFT JOIN order_source AS s ON i.id = s.id
			LEFT JOIN order_receiver AS r ON i.id = r.id
			LEFT JOIN finance_order AS o ON i.id = o.order_id
			WHERE i.company_id = '$company_id' AND i.id = '$id' AND i.is_delete='N'";
	$result = mysql_query($sql,$_mysql_link_);
	while($Detail = mysql_fetch_object($result)){
		$arr = array();

		$cSql = "SELECT nick_name FROM crm_user_info WHERE bind_id = '$Detail->user_id'";
		$cResult = mysql_query($cSql,$_mysql_link_);

		while ($cData = mysql_fetch_object($cResult)) {
			$arr['nick_name'] = $cData->nick_name;
		}

		$sql2 = "SELECT shop_name FROM user_register_info WHERE bind_id = '$Detail->user_id'";
		$result2 = mysql_query($sql2,$_mysql_link_);
		while($store = mysql_fetch_assoc($result2)){
			$arr['shop_name']           = $store['shop_name'];
		}

		$sql = "SELECT express_id,freight_buyer,freight_seller FROM order_express_paper where order_id='$id' AND company_id='$company_id'";
		$result3 = mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result3))
		{
			$arr['express_id']			= $StoreInfo->express_id;
			$arr['post_fee']			= number_format($StoreInfo->freight_seller,2);
			$arr['freight_buyer']       = $StoreInfo->freight_buyer;
		}

		$sql = "SELECT body,total,status FROM order_custom where order_id='$id' AND company_id='$company_id'";
		$result4 = mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result4))
		{
			$arr['body']			= $StoreInfo->body;
			$arr['total']			= $StoreInfo->total;
			$arr['status']			= $StoreInfo->status;
		}
		if($arr['status'] == "Y"){
			$arr['status_name'] = "是";
		}else{
			$arr['status_name'] = "否";
		}
		$arr['user_id']                 = $Detail->user_id;
		$arr['tt']					    = $tt;   //商品总数
		$arr['discount']				= number_format($Detail->discount,2);
		// $arr['store_id']				= $Detail->store_id;
		$arr['order_date'] 				= $Detail->order_date;
		$arr['bind_number'] 			= $Detail->bind_number;
		$arr['type'] 				    = $Detail->bind_type;

		if($arr['type'] == "System"){
			$arr['bind_type'] = "系统默认";
		}elseif($arr['type'] == "360buy"){
			$arr['bind_type'] = "京东商城";
		}elseif($arr['type'] == "Taobao"){
			$arr['bind_type'] = "淘宝网";
		}

		$arr['is_audit']                = $Detail->is_audit;
		if($arr['is_audit'] =="Y"){
			$arr['audit'] = "已审核";
		}elseif($arr['is_audit'] =="N"){
			$arr['audit'] = "未审核";
		}else{
			$arr['audit'] = "异常";
		}
		$arr['bank_id'] 			    = $Detail->bank_id;
		$arr['related_order'] 			= $Detail->related_order;
		$arr['order_id']				= $id;
		$arr['bank_id']					= $Detail->bank_id;
		$arr['need_invoice']			= $Detail->need_invoice;
		$arr['tax_type']                = $Detail->tax_type;
		$arr['name']					= $Detail->name;
		$arr['mobile']					= $Detail->mobile;
		$arr['phone']					= $Detail->phone;
		$arr['company_name']			= $Detail->company_name;
		$arr['post_code']				= $Detail->post_code;

		$arr['order_text']				= $Detail->order_text;
		$arr['customer_text']			= $Detail->customer_text;
		$arr['address']					= $Detail->address;

		$arr['state_id']                = $Detail->state_id;
		$arr['city_id']                 = $Detail->city_id;
		$arr['district_id']             = $Detail->district_id;

		$arr['theory_amount']			= number_format($Detail->theory_amount,2);
		$arr['real_amount']				= number_format($Detail->real_amount,2);
		$arr['arrears']					= number_format($Detail->arrears,2);
		$arr['payment_status']			= $Detail->payment_status;
		$arr['total_amount']            = $Detail->theory_amount-$arr['freight_buyer'];

		$arr['tax_number']              = $Detail->tax_number;
		$arr['tax_title']              	= $Detail->tax_title;
		$arr['tax_bank_name']           = $Detail->tax_bank_name;
		$arr['tax_bank_number']         = $Detail->tax_bank_number;
		$arr['tax_text']                = $Detail->tax_text;

		$arr['zong']                    = $Detail->theory_amount - $arr['freight_buyer'];

		if($arr['payment_status'] == "N"){
			$arr['pay_status'] = "未付款";
		}elseif($arr['payment_status'] == "Y"){
			$arr['pay_status'] = "已付款";
		}elseif($arr['payment_status'] == "P"){
			$arr['pay_status'] ="部分付款";
		}



		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");

	}


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
		$real_amount       = $_POST['real_amount'];             		//实付金额
		$shop_title		   = intval($_POST['shop_title']);	    //店铺名称
		$tax_bank_name	   = replace_safe($_POST['tax_bank_name']);	    //开户银行名称
		$tax_bank_number   = replace_safe($_POST['tax_bank_number']);	//发票银行帐号
		$tax_number   	   = replace_safe($_POST['tax_number']);	    //税号
		$bank_id		   = intval($_POST['bank_id']);	     	 		//到账账户
		$total_yh          = $_POST['total_yh'];         				//商品总优惠
		$post_fee	   	   = $_POST['post_fee'];	     				//卖家运费
		$freight_buyer	   = $_POST['freight_buyer'];                   //买家运费
		$theory_amount     = $_POST['theory_amount'];    				//应付金额
		$real_amount       = $_POST['real_amount'];      				//实付金额
		$remain_amount     = $_POST['remain_amount'];           		//顾客欠款或者尾款
		$payment_status    = replace_safe($_POST['payment_status']);    //付款状态
		$is_audit          = replace_safe($_POST['is_audit']);          //审核状态
		$order_date 	   = replace_safe($_POST['order_date']);		//下单时间
		$company_name	   = replace_safe($_POST['company_name']);	 	//公司名称
		$express_id	   	   = intval($_POST['express_id']);	    		//快递公司
		$body              = replace_safe($_POST['body']);              //定制内容
		$total             = intval($_POST['total']);					//定制数量
		$sure            = replace_safe($_POST['status']);              //确认情况
		$is_delete		   = "N";
		$nick_name         = replace_safe($_POST['nick_name']);
		$total_yh          = replace_safe($_POST['total_yh']);			//商品总优惠
		$beforeAmount      = replace_safe($_POST['beforeAmount']);		//实收金额增前的
		$afterAmount       = replace_safe($_POST['afterAmount']);		//实收金额增后的
		// var_dump($bank_id);
		// die();
		if(!isset($ERPBindInfo[$bind_type]))
		{
			$bind_type	= "System";
		}
		// $bind_type         = replace_safe($_POST['bind_type']);	    //订单来源
		$need_dz           = replace_safe($_POST['need_dz']);
		$price             = $_POST['price'];			        //单价
		$number            = $_POST['number'];                  //商品数量
		$discount          = $_POST['discount'];         		//商品优惠
		$good_name         = $_POST['good_name'];	            //商品id
		$pay         	   = $_POST['pay'];	                    //商品总价
		$content           = $_POST['content'];                 //商品备注
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
		if(!isset($ListQudao[$bind_type])){
			$bind_type 	   = 0;
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

		$order_id	   	   = intval($_POST['order_id']);	     				//订单ID
		$sql = "UPDATE order_source SET
		user_id='$shop_title',
		bind_number='$bind_number',
		related_order='$related_order',
		order_text='$order_text',
		customer_text='$customer_text'
		WHERE id='$order_id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
		$sql = "SELECT product_id,store_id FROM order_product WHERE company_id='$company_id' AND order_id='$order_id'";
		$result = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($result)){
			$sql = "UPDATE store_related SET lock_total='0' WHERE company_id='$company_id' AND product_id='$res->product_id' AND store_id='$res->store_id'";
			mysql_query($_mysql_link_);
		}

		$sql = "DELETE FROM order_product WHERE company_id='$company_id' AND order_id='$order_id'";
		mysql_query($sql,$_mysql_link_);
		for($i=0;$i<count($good_name);$i++){
			$good_name[$i] = replace_safe($good_name[$i]);
			$price[$i]     = intval($price[$i]);
			$number[$i]    = intval($number[$i]);
			$discount[$i]  = intval($discount[$i]);
			$pay[$i]       = intval($pay[$i]);
			$content[$i]   = replace_safe($content[$i]);

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
			'{$good_name[$i]}',
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

			$sql = "UPDATE order_receiver SET
			name='$name',
			phone='$phone',
			mobile='$mobile',
			state_id='$state_id',
			city_id='$city_id',
			district_id='$district_id',
			post_code='$post_code',
			address='$address',
			company_name='$company_name',
			need_invoice='$need_invoice',
			tax_type='$tax_type',
			tax_number='$tax_number',
			tax_bank_name='$tax_bank_name',
			tax_bank_number='$tax_bank_number',
			tax_title='$tax_title',
			tax_text='$tax_text'
			WHERE id='$order_id' AND company_id='$company_id'";
			mysql_query($sql,$_mysql_link_);
			//账务订单信息的修改
			$sql = "UPDATE finance_order SET
			bank_id='$bank_id',
			discount='$total_yh',
			theory_amount='$theory_amount',
			real_amount='$real_amount',
			payment_status='$payment_status',
			arrears='$remain_amount'
			WHERE order_id='$order_id' AND company_id='$company_id'";
			mysql_query($sql,$_mysql_link_);

			//向银行账户中增加金额finance_bank
			if (floatval($beforeAmount) < floatval($afterAmount)) {
				$bRemain = floatval($afterAmount) - floatval($beforeAmount);
				$bSql = "UPDATE finance_bank
						SET balance=balance+$bRemain
						WHERE company_id=$company_id AND status='Y' AND id=$bank_id";
				$bRes = mysql_query($bSql,$_mysql_link_);

				//向记录finance_cash_logs表中记录数据
				$amount_date = date('Y-m-d H:i:s',time());
				$logSql = "INSERT INTO finance_cash_logs(company_id,info_id,amount_date,company_type,money,bank_id)
					VALUES($company_id,$order_id,'$amount_date','Custom',$bRemain,$bank_id)";
				$logRes = mysql_query($logSql,$_mysql_link_);
			}

			if($need_dz == "Y"){
				$sql = "INSERT INTO order_custom(
				body='$body',
				total='$total',
				status='$status'
				WHERE order_id='$order_id' AND company_id='$company_id";
				mysql_query($sql,$_mysql_link_);
			}

		$sql = "UPDATE order_express_paper SET express_id = '$express_id',freight_seller='$post_fee',freight_buyer='$freight_buyer' WHERE order_id ='$order_id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
		header("Location: order_list_audit.php");
		exit;
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

