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
	//订单日期
	$rq = date("Y-m-d");
	$main['rq'] = $rq;
$company_id = $_SESSION['_application_info_']['company_id'];
	
	//---- 店铺名称 ----
	$sql = "SELECT user_id FROM  company_related WHERE company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{	
		$list_shop['user_id'] = $StoreInfo->user_id;
		$sql = "SELECT shop_name FROM user_register_info WHERE id='$StoreInfo->user_id'";
		$query = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($query)){
			$list_shop['shop_name'] = $res->shop_name;
			$xtpl->assign("list_shop", $list_shop);
			$xtpl->parse("main.list_shop");
		}
	}


	//---- 发货仓库 ----
	$sql = "SELECT id,name FROM store_info where store_status ='Normal' AND company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_store	= array();
		$list_store['id']			= $StoreInfo->id;
		$list_store['name']			= $StoreInfo->name;
		$xtpl->assign("list_store", $list_store);
		$xtpl->parse("main.list_store");
	}

	//---- 到账账户 ----
	$sql = "SELECT id,name,is_default FROM finance_bank where status='Y' AND company_id='$company_id'";
	$result	= mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_account = array();
		$list_account['id']			= $StoreInfo->id;
		$list_account['name']	    = $StoreInfo->name;
		$list_account['is_default']	= $StoreInfo->is_default;
		$xtpl->assign("list_account", $list_account);
		$xtpl->parse("main.list_account");
	}

	//---- 销售渠道 ----
	$sql = "SELECT id,name FROM company_sales where company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_qudao = array();
		$list_qudao['id']			= $StoreInfo->id;
		$list_qudao['name']	    = $StoreInfo->name;
		$xtpl->assign("list_qudao", $list_qudao);
		$xtpl->parse("main.list_qudao");
	}

	//----- 快递公司 ----
	$sql = "SELECT express_id,name FROM company_express_info WHERE status = 'Y' AND company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_deliver	= array();
		$list_deliver['express_id']     = $StoreInfo->express_id;
		$list_deliver['name']			= $StoreInfo->name;
		$xtpl->assign("list_deliver", $list_deliver);
		$xtpl->parse("main.list_deliver");
		
	}
	
	if(!empty($_GET['order_id'])){
		$id = $_GET['order_id'];
		$sql2 = "SELECT product_id,store_id,total,price,discount,payment,content FROM order_product where company_id='$company_id' AND order_id='$id'";
		$result2 = mysql_query($sql2, $_mysql_link_);
		$num = 1;
		$to = array();
		while($Store = mysql_fetch_object($result2))
		{

			$sql3 = "SELECT product_info.name,
				product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content   
				FROM product_info 
				LEFT JOIN product_detail on product_info.id = product_detail.id 
				WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$Store->product_id'";
			$result3 = mysql_query($sql3,$_mysql_link_);
			$a = array();
			$b = "";
			while($StoreInfo = mysql_fetch_object($result3)){
				$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
				$result = mysql_query($sql,$_mysql_link_);
				$res = mysql_fetch_object($result);

				$a = array(
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
				r.name,r.phone,r.mobile,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.need_invoice,r.tax_number,r.tax_title,r.tax_bank_name,r.tax_bank_number,r.tax_text,
				o.bank_id,o.discount,o.post_fee,o.theory_amount,o.real_amount,o.payment_status,o.arrears,
				p.express_id
				FROM order_info AS i 
				LEFT JOIN order_source AS s ON i.id = s.id
				LEFT JOIN order_receiver AS r ON i.id = r.id
				LEFT JOIN finance_order AS o ON i.id = o.order_id
				LEFT JOIN order_express_paper AS p ON i.id = p.order_id
				WHERE i.company_id = '$company_id' AND i.id = '$id' AND i.is_delete='N'";
		$result = mysql_query($sql,$_mysql_link_);
		while($Detail = mysql_fetch_object($result)){
			$arr = array();
			$sql1 = "SELECT name FROM finance_bank where status='Y' AND company_id='$company_id' AND id='$Detail->bank_id'";
			$result1 = mysql_query($sql1, $_mysql_link_);
			while($StoreInfo = mysql_fetch_object($result1))
			{
				$arr['bank_name']			= $StoreInfo->name;
			}
			$sql2 = "SELECT shop_name FROM user_register_info WHERE bind_id = '$Detail->user_id'";
			$result2 = mysql_query($sql2,$_mysql_link_);
			while($store = mysql_fetch_assoc($result2)){
				$arr['shop_name']           = $store['shop_name'];
			}
			$arr['user_id']                 = $Detail->user_id;
			$arr['tt']					    = $tt;   //商品总数
			$arr['discount']				= number_format($Detail->discount,2);
			$arr['express_id']				= $Detail->express_id;
			// $arr['store_id']				= $Detail->store_id;
			$arr['order_date'] 				= $Detail->order_date;
			$arr['bind_number'] 			= $Detail->bind_number;
			$arr['type'] 				    = $Detail->bind_type;
			if($arr['type'] == "System"){
				$arr['bind_type'] = "系统默认";
			}elseif($arr['type'] == "360buy"){
				$arr['bind_type'] = "京东商城";
			}else{
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
			$arr['related_order'] 			= $Detail->related_order;
			$arr['order_id']				= $id;
			$arr['bank_id']					= $Detail->bank_id;
			$arr['need_invoice']			= $Detail->need_invoice;
			$arr['name']					= $Detail->name;
			$arr['mobile']					= $Detail->mobile;
			$arr['phone']					= $Detail->phone;
			$arr['company_name']			= $Detail->company_name;
			$arr['post_code']				= $Detail->post_code;
			$arr['post_fee']				= number_format($Detail->post_fee,2);
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
			$arr['total_amount']            = $Detail->discount+$Detail->theory_amount;

			$arr['tax_number']              = $Detail->tax_number;
			$arr['tax_title']              	= $Detail->tax_title;
			$arr['tax_bank_name']           = $Detail->tax_bank_name;
			$arr['tax_bank_number']         = $Detail->tax_bank_number;
			$arr['tax_text']                = $Detail->tax_text;

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
	}

	if(isset($_POST['submit'])){
		
		if(!empty($_POST['bind_number'])){
			$bind_number   = replace_safe($_POST['bind_number']);		                //订单编号
		}else{
			//---- 自动生成员工编号 ----
			// $bind_number 	= replace_safe(insert_company_number($company_id, "order"));
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
		$province		   = intval($_POST['state_id']);                //省份
		$city              = intval($_POST['city_id']);                 //城市
		$xian              = intval($_POST['district_id']);             //县
		$address           = replace_safe($_POST['address']);           //详细地址
		$post_code		   = intval($_POST['post_code']);	            //邮政编码
		$need_invoice	   = replace_safe($_POST['need_invoice']);	    //发票收据
		$company_name	   = replace_safe($_POST['company_name']);	    //公司名称
		$tax_number	       = replace_safe($_POST['tax_number']);	    //税号
		$tax_type          = replace_safe($_POST['tax_type']);
		$tax_text	       = replace_safe($_POST['tax_text']);	    	//发票明细
		$tax_title	       = replace_safe($_POST['tax_title']);	    	//发票抬头


		 
		//order_product

		$store_id		   = intval($_POST['store_id']);	            //发货仓库
		$real_amount       = $_POST['real_amount'];             		//实付金额
		$shop_title		   = intval($_POST['shop_title']);	    //店铺名称

		//finance_order
		$tax_bank_name	   = replace_safe($_POST['tax_bank_name']);	    //开户银行名称
		$tax_bank_number   = replace_safe($_POST['tax_bank_number']);	//发票银行帐号
		$tax_number   	   = replace_safe($_POST['tax_number']);	    //税号

		$bank_id		   = intval($_POST['bank_id']);	     	 		//到账账户
		$total_yh          = $_POST['total_yh'];         				//商品总优惠
		$post_fee	   	   = $_POST['post_fee'];	     				//快递运费
		$theory_amount     = $_POST['theory_amount'];    				//应付金额
		$real_amount       = $_POST['real_amount'];      				//实付金额
		$remain_amount     = $_POST['remain_amount'];           		//顾客欠款或者尾款 
		$payment_status    = replace_safe($_POST['payment_status']);    //付款状态
		//order_info
		$is_audit          = replace_safe($_POST['is_audit']);          //审核状态
		$order_date 	   = replace_safe($_POST['order_date']);		//下单时间
		$company_name	   = replace_safe($_POST['company_name']);	 	//公司名称
		// order_express_paper
		$express_id	   	   = intval($_POST['express_id']);	    		//快递公司
		//order_custom
		$body              = replace_safe($_POST['body']);              //定制内容
		$total             = intval($_POST['total']);					//定制数量
		$status            = replace_safe($_POST['status']);            //确认情况
		// order_logs
		$is_delete		   = "N";
		$nick_name         = replace_safe($_POST['nick_name']);
		$total_yh          = replace_safe($_POST['total_yh']);			        //商品总优惠
		if(!isset($ERPBindInfo[$bind_type]))
		{
			$bind_type	= "System";
		}
		// $bind_type         = replace_safe($_POST['bind_type']);					//订单来源

		$need_dz           = replace_safe($_POST['need_dz']);

		$price             = $_POST['price'];			        //单价
		$number            = $_POST['number'];                  //商品数量
		$discount          = $_POST['discount'];         		//商品优惠
		$good_name         = $_POST['good_name'];	            //商品id
		$pay         	   = $_POST['pay'];	                    //商品总价
		$content           = $_POST['content'];                 //商品备注
		$delivery_status   = "Untreated";
		$now = date('Y-m-d');
		$sql = "INSERT INTO order_info (
			company_id,
			is_audit,
			order_date,
			is_delete
			) VALUES (
			'$company_id',
			'$is_audit',
			'$order_date',
			'$is_delete'
			)";
		mysql_query($sql,$_mysql_link_);
		$row = mysql_affected_rows($_mysql_link_);
		if($row){
			$order_id = mysql_insert_id($_mysql_link_);

			$sql = "SELECT name,crm_user_id FROM crm_user_related WHERE company_id='$company_id' AND mobile='$mobile'";
			$result = mysql_query($sql,$_mysql_link_);
			$res = mysql_fetch_object($result);
			$crm_user_id = intval($res->crm_user_id);
			if(!$res){
				$sql = "INSERT INTO crm_user_known(id,name) VALUES ('',$name)";
				mysql_query($sql,$_mysql_link_);
				$rows = mysql_affected_rows($_mysql_link_);
				if($rows){
					$bind_id = mysql_insert_id($_mysql_link_);
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
					'$order_id',
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
				$good_name[$i] = intval($good_name[$i]);
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
				$sql = "INSERT INTO store_related(
				id,
				company_id,
				store_id,
				product_id,
				lock_total
				)VALUES(
				'',
				'$company_id',
				'$store_id',
				'{$good_name[$i]}',
				'{$number[$i]}'
				)";
				mysql_query($sql,$_mysql_link_);
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

			$sql = "INSERT INTO finance_order(
			id,
			company_id,
			order_id,
			bank_id,
			discount,
			post_fee,
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
			'$post_fee',
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
				purchase_channels
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
			fee
			)VALUES(
			'',
			'$company_id',
			'$order_id',
			'$express_id',
			'$post_fee'
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

		header("Location: order_sale_deal.php");
		exit;
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");