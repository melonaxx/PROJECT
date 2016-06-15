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
$company_id=$_SESSION['_application_info_']['company_id'];

	//发货处理
	if(!empty($_GET['status'])){
		$stat   = false;
		$id     = 0;
		$iid    = 0;
		$aid    = 0;
		$status = replace_safe(rtrim($_GET['status'],","));

		$sql 	= "SELECT order_express_paper.number, order_express_paper.express_id, order_source.bind_number, order_source.bind_type FROM order_express_paper LEFT JOIN order_source ON order_express_paper.order_id = order_source.id WHERE order_express_paper.company_id='$company_id' AND order_express_paper.order_id IN ($status)";
		$res2 	= mysql_query($sql,$_mysql_link_);
		while($this = mysql_fetch_object($res2)){

			if($this->bind_type == "System"){
				$stat = true;
				$sql = "UPDATE order_delivery SET delivery_status = 'Finish',action_date=NOW() WHERE company_id='$company_id' AND id IN ($status)";
				mysql_query($sql,$_mysql_link_);
				$id = mysql_affected_rows($_mysql_link_);

				$sql = "UPDATE order_info SET status = 'S' WHERE company_id='$company_id' AND is_delete='N' AND id IN ($status)";
				mysql_query($sql,$_mysql_link_);
				$iid = mysql_affected_rows($_mysql_link_);

				$sql = "UPDATE order_express_paper SET deliver_date = NOW() WHERE company_id='$company_id' AND order_id IN ($status)";
				mysql_query($sql,$_mysql_link_);
				$aid = mysql_affected_rows($_mysql_link_);

				$sql    = "SELECT product_id,total,store_id,payment FROM order_product WHERE order_id IN ($status) AND company_id='$company_id'";
				$result = mysql_query($sql,$_mysql_link_);
				while($res = mysql_fetch_object($result)){
					
					//计入销量（是否为组合商品）
					$sqlc = "SELECT sub_id,total from product_combination where product_id='{$res->product_id}'";
					$cai = mysql_query($sqlc,$_mysql_link_);
					$nna = mysql_num_rows($cai);
					$apl = array();
					if($nna>0){
						while($rwoo = mysql_fetch_object($cai)){
							$sub_id = $rwoo->sub_id;
							$total = $rwoo->total;
							$sqlo = "SELECT price_display FROM product_detail WHERE id='$sub_id'";
							$kyao = mysql_query($sqlo,$_mysql_link_);
							while($wqpo = mysql_fetch_object($kyao)){
								$apl[] = $wqpo->price_display*$total;
							}
						}	
					}
					for($i=0;$i<count($apl);$i++){
						$mlp += $apl[$i]; 
					}
					global $mlp;


					$sqlc = "SELECT sub_id,total from product_combination where product_id='{$res->product_id}'";
					$cai = mysql_query($sqlc,$_mysql_link_);
					$nna = mysql_num_rows($cai);
					if($nna>0){
	
						//该商品为组合商品
						while($rwo = mysql_fetch_object($cai)){
							$sub_id = $rwo->sub_id;
							$total = $rwo->total;

							//减库
							$jk = $total*$res->total;
							$sql = "UPDATE store_related SET real_total=real_total-'$jk',lock_total=lock_total-'$jk' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$sub_id'";
								mysql_query($sql,$_mysql_link_);
								$sql = "UPDATE store_product SET total_real=total_real-'$jk',total_lock=total_lock-'$jk' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$sub_id'";
								mysql_query($sql,$_mysql_link_);

							//查询组合商品的售价
							$sql = "SELECT price_display FROM product_detail WHERE id='{$res->product_id}'";

							$lsa = mysql_query($sql,$_mysql_link_);
							while($faq = mysql_fetch_object($lsa)){
								$fsk = $faq->price_display;//售价
								//查询单个子产品的组合单价售价
								$sql = "SELECT price_display,price_purchase FROM product_detail WHERE id='$sub_id'";
								$kya = mysql_query($sql,$_mysql_link_);
								while($mk = mysql_fetch_object($kya)){
									$zspr = $mk ->price_display;
									$jj  = $mk->price_purchase; //进价
									//查询实收金额
									$sql = "SELECT theory_amount from finance_order  WHERE order_id IN ($status) AND company_id='$company_id'";
									$sac = mysql_query($sql,$_mysql_link_);
							 		while($rwq = mysql_fetch_object($sac)){
							 			//应收金额的（组合金额）
							 			$ysjf = $rwq->theory_amount;
							 			//当前子商品的销售额
							 			// var_dump($jyp);
							 			$dawq = ($zspr*$total)/$mlp;

							 			$son_gpr = $ysjf * $dawq;
							 			// var_dump($son_gpr);
							 			//当前利润

							 			//销量
							 			$xiaol = $total * $res->total;

							 			$mnb = $xiaol*$jj;
							 			$lrd = $son_gpr-$mnb;			
							 			$sql = "UPDATE product_sales SET sales_number=sales_number+'$xiaol',sales_volume=sales_volume+'$son_gpr',profit=profit+'$lrd' WHERE id='$sub_id'";
							 			$ld = mysql_query($sql,$_mysql_link_);
							 		}

								}

							}

						}
					}else{
						//是否存在销量数据
						// $sql = "SELECT id FROM product_sales WHERE id='{$res->product_id}'";
						// $resu = mysql_query($sql,$_mysql_link_);
						// if(mysql_num_rows($resu)<=0){
						// 	$sql		= "INSERT INTO product_sales SET id='{$res->product_id}'";
						// 	mysql_query($sql, $_mysql_link_);
						// }
						 $sql = "SELECT theory_amount from finance_order  WHERE order_id IN ($status) AND company_id='$company_id'";
						 $sac = mysql_query($sql,$_mysql_link_);
						 while($rwq = mysql_fetch_object($sac)){
						 	//应收金额(销售额)
						 	$xse = $rwq->theory_amount;
						 	//进价查询
						 	$sql = "SELECT price_purchase from product_detail WHERE id={$res->product_id}";
						 	$cet = mysql_query($sql,$_mysql_link_);
						 	while($gfa = mysql_fetch_object($cet)){
						 		//进价值
						 		$jinj = $gfa->price_purchase;
						 		//利润
						 		$lr = $xse - ($jinj * $res->total);
						 		$sql = "UPDATE product_sales SET sales_number=sales_number+'{$res->total}',sales_volume=sales_volume+'$xse',profit=profit+'$lr' WHERE id='{$res->product_id}'";
								mysql_query($sql,$_mysql_link_);

								//减库
								$sql = "UPDATE store_related SET real_total=real_total-'{$res->total}',lock_total=lock_total-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='{$company_id}' AND product_id='{$res->product_id}'";
								mysql_query($sql,$_mysql_link_);
								$sql = "UPDATE store_product SET total_real=total_real-'{$res->total}',total_lock=total_lock-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
								mysql_query($sql,$_mysql_link_);
						 	}

						 }
					}	
				}
			}else{
				$sql  = "SELECT name FROM main_express_info WHERE status = 'Y' AND id='$this->express_id'";
				$res1 = mysql_query($sql,$_mysql_link_);
				$dbRow = mysql_fetch_object($res1);

				$out_sid  = $this->number;		//快递单号
				$name 	  = $dbRow->name;       // 快递名字的code
				$tid      = $this->bind_number; //订单编号
				//获取快递公司的CODE
				$params				= array();
				$params['method']	= 'taobao.logistics.companies.get';
				$params['fields']	= 'id, code, name';
				$params['order_mode'] = 'offline';

				$params['nick']		= $_SESSION['_application_info_']['nick'];
				$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
				$json_data			= json_decode($site_str);

				header("Content-Type: text/html; charset=UTF-8");
				$companies = $json_data->logistics_companies_get_response->logistics_companies->logistics_company;
				for($i=0;$i<count($companies);$i++){
					if($companies[$i]->name == $name){
						$params				    = array();
						$params['method']	    = 'taobao.logistics.offline.send';
						$params['tid']		    = $tid;
						$params['out_sid']		= $out_sid;
						$params['company_code'] = $companies[$i]->code;

						$params['nick']		= $_SESSION['_application_info_']['nick'];
						$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
						$deliver			= json_decode($site_str);

						header("Content-Type: text/html; charset=UTF-8");
						$status = $deliver->logistics_offline_send_response->shipping->is_success;
						if($status){
							$stat = true;
							$sql = "UPDATE order_delivery SET delivery_status = 'Finish',action_date=NOW() WHERE company_id='$company_id' AND id IN ($status)";
							mysql_query($sql,$_mysql_link_);
							$id = mysql_affected_rows($_mysql_link_);

							$sql = "UPDATE order_info SET status = 'S' WHERE company_id='$company_id' AND is_delete='N' AND id IN ($status)";
							mysql_query($sql,$_mysql_link_);
							$iid = mysql_affected_rows($_mysql_link_);

							$sql = "UPDATE order_express_paper SET deliver_date = NOW() WHERE company_id='$company_id' AND order_id IN ($status)";
							mysql_query($sql,$_mysql_link_);
							$aid = mysql_affected_rows($_mysql_link_);

							$sql    = "SELECT product_id,total,store_id,payment FROM order_product WHERE order_id IN ($status) AND company_id='$company_id'";
							$result = mysql_query($sql,$_mysql_link_);
							while($res = mysql_fetch_object($result)){
								
								//计入销量（是否为组合商品）
								$sqlc = "SELECT sub_id,total from product_combination where product_id='{$res->product_id}'";
								$cai = mysql_query($sqlc,$_mysql_link_);
								$nna = mysql_num_rows($cai);
								$apl = array();
								if($nna>0){
									while($rwoo = mysql_fetch_object($cai)){
										$sub_id = $rwoo->sub_id;
										$total = $rwoo->total;
										$sqlo = "SELECT price_display FROM product_detail WHERE id='$sub_id'";
										$kyao = mysql_query($sqlo,$_mysql_link_);
										while($wqpo = mysql_fetch_object($kyao)){
											$apl[] = $wqpo->price_display*$total;
										}
									}	
								}
								for($i=0;$i<count($apl);$i++){
									$mlp += $apl[$i]; 
								}
								global $mlp;


								$sqlc = "SELECT sub_id,total from product_combination where product_id='{$res->product_id}'";
								$cai = mysql_query($sqlc,$_mysql_link_);
								$nna = mysql_num_rows($cai);
								if($nna>0){
				
									//该商品为组合商品
									while($rwo = mysql_fetch_object($cai)){
										$sub_id = $rwo->sub_id;
										$total = $rwo->total;

										//减库
										$jk = $total*$res->total;
										$sql = "UPDATE store_related SET real_total=real_total-'$jk',lock_total=lock_total-'$jk' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$sub_id'";
											mysql_query($sql,$_mysql_link_);
											$sql = "UPDATE store_product SET total_real=total_real-'$jk',total_lock=total_lock-'$jk' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$sub_id'";
											mysql_query($sql,$_mysql_link_);
										//查询组合商品的售价
										$sql = "SELECT price_display FROM product_detail WHERE id='{$res->product_id}'";

										$lsa = mysql_query($sql,$_mysql_link_);
										while($faq = mysql_fetch_object($lsa)){
											$fsk = $faq->price_display;//售价
											//查询单个子产品的组合单价售价
											$sql = "SELECT price_display,price_purchase FROM product_detail WHERE id='$sub_id'";
											$kya = mysql_query($sql,$_mysql_link_);
											while($mk = mysql_fetch_object($kya)){
												$zspr = $mk ->price_display;
												$jj  = $mk->price_purchase; //进价
												//查询实收金额
												$sql = "SELECT theory_amount from finance_order  WHERE order_id IN ($status) AND company_id='$company_id'";
												$sac = mysql_query($sql,$_mysql_link_);
										 		while($rwq = mysql_fetch_object($sac)){
										 			//应收金额的（组合金额）
										 			$ysjf = $rwq->theory_amount;
										 			//当前子商品的销售额
										 			// var_dump($jyp);
										 			$dawq = ($zspr*$total)/$mlp;

										 			$son_gpr = $ysjf * $dawq;
										 			// var_dump($son_gpr);
										 			//当前利润

										 			//销量
										 			$xiaol = $total * $res->total;

										 			$mnb = $xiaol*$jj;
										 			$lrd = $son_gpr-$mnb;			
										 			$sql = "UPDATE product_sales SET sales_number=sales_number+'$xiaol',sales_volume=sales_volume+'$son_gpr',profit=profit+'$lrd' WHERE id='$sub_id'";
										 			$ld = mysql_query($sql,$_mysql_link_);
										 		}

											}

										}

									}

								}else{
									//是否存在销量数据
									// $sql = "SELECT id FROM product_sales WHERE id='{$res->product_id}'";
									// $resu = mysql_query($sql,$_mysql_link_);
									// if(mysql_num_rows($resu)<=0){
									// 	$sql		= "INSERT INTO product_sales SET id='{$res->product_id}'";
									// 	mysql_query($sql, $_mysql_link_);
									// }
									 $sql = "SELECT theory_amount from finance_order  WHERE order_id IN ($status) AND company_id='$company_id'";
									 $sac = mysql_query($sql,$_mysql_link_);
									 while($rwq = mysql_fetch_object($sac)){
									 	//应收金额(销售额)
									 	$xse = $rwq->theory_amount;
									 	//进价查询
									 	$sql = "SELECT price_purchase from product_detail WHERE id={$res->product_id}";
									 	$cet = mysql_query($sql,$_mysql_link_);
									 	while($gfa = mysql_fetch_object($cet)){
									 		//进价值
									 		$jinj = $gfa->price_purchase;
									 		//利润
									 		$lr = $xse - ($jinj * $res->total);
									 		$sql = "UPDATE product_sales SET sales_number=sales_number+'{$res->total}',sales_volume=sales_volume+'$xse',profit=profit+'$lr' WHERE id='{$res->product_id}'";
											mysql_query($sql,$_mysql_link_);

											//减库
											$sql = "UPDATE store_related SET real_total=real_total-'$res->total',lock_total=lock_total-'$res->total' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$res->product_id'";
											mysql_query($sql,$_mysql_link_);
											$sql = "UPDATE store_product SET total_real=total_real-'$res->total',total_lock=total_lock-'$res->total' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$res->product_id'";
											mysql_query($sql,$_mysql_link_);
									 	}

									 }
								}	
							}
						}else{
							$stat = false;
						}
					}
				}
			}
		}
		if($id && $iid && $stat && $aid){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//打回审核
	if(!empty($_GET['audit'])){
		$id = replace_safe(rtrim($_GET['audit'],","));
		$sql = "UPDATE order_info SET is_audit='N',status='N' WHERE company_id='$company_id' AND id IN ($id)";
		mysql_query($sql,$_mysql_link_);
		$iid = mysql_affected_rows($_mysql_link_);

		$sql = "UPDATE order_delivery SET delivery_status='Untreated' WHERE company_id='$company_id' AND id IN ($id)";
		mysql_query($sql,$_mysql_link_);
		$did = mysql_affected_rows($_mysql_link_);
		if($iid && $did){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//打回配货
	if(!empty($_GET['pei_id'])){
		$order_id = replace_safe(rtrim($_GET['pei_id'],","));
		$sql = "UPDATE order_info SET status='N' WHERE company_id='$company_id' AND id IN ($order_id)";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);

		$sql = "UPDATE order_delivery SET delivery_status='Untreated' WHERE company_id='$company_id' AND id IN ($order_id)";
		mysql_query($sql,$_mysql_link_);
		$did = mysql_affected_rows($_mysql_link_);
		if($id && $did){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//打回验货
	if(!empty($_GET['yan_id'])){
		$order_id = replace_safe(rtrim($_GET['yan_id'],","));
		$sql = "UPDATE order_info SET status='F' WHERE company_id='$company_id' AND id IN ($order_id)";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);

		$sql = "UPDATE order_delivery SET delivery_status='Picking' WHERE company_id='$company_id' AND id IN ($order_id)";
		mysql_query($sql,$_mysql_link_);
		$did = mysql_affected_rows($_mysql_link_);
		if($id && $did){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//打回称重
	if(!empty($_GET['cheng_id'])){
		$order_id = replace_safe(rtrim($_GET['cheng_id'],","));
		$sql = "UPDATE order_info SET status='I' WHERE company_id='$company_id' AND id IN ($order_id)";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);

		// $sql = "UPDATE order_delivery SET delivery_status='Picking' WHERE company_id='$company_id' AND id IN ($order_id)";
		// mysql_query($sql,$_mysql_link_);
		// $did = mysql_affected_rows($_mysql_link_);
		if($id){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}


	//---- 设置查询条件: ----
	$addon	= array();
	$addon[]	= "order_info.company_id='".$_SESSION['_application_info_']['company_id']."'";
	$addon[]	= "order_info.is_audit='Y'";
	$addon[]	= "order_info.is_delete='N'";
	$addon[]	= "order_info.status='W'";
	$addon[]	= "order_info.unusual_id='0'";
	$addon[]    = "order_delivery.delivery_status='Picking'";


	if(!empty($_GET['find']))
	{
		$find	= replace_safe($_REQUEST['find'], 20);
		if(!empty($find))
		{
			$addon[]		= "( INSTR(order_source.bind_number,'$find') OR INSTR(order_receiver.name,'$find') )";
			$main['find']	= $find;
			$page_param		= array();
			$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
		}
	}

	$where  = "";
	if(count($addon) > 0)
	{
		$where	= "WHERE ".implode(" AND ", $addon);
	}

	$sql = "SELECT COUNT(*) as total FROM order_info
		LEFT JOIN order_source ON order_info.id = order_source.id
		LEFT JOIN order_delivery ON order_info.id = order_delivery.id
		LEFT JOIN order_receiver ON order_info.id = order_receiver.id ".$where;
	$result	= mysql_query($sql, $_mysql_link_);
	$main['total']		= mysql_result($result, 0, 'total');

	//---- 处理分页 ----
	if(!is_array($page_param))
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

	//---- 数量大于0 ----
	if($main['total'] > 0)
	{
		$sql = "SELECT
		order_info.id,
		order_source.order_text,order_source.customer_text,order_source.user_id,order_source.bind_number,
		order_receiver.name,order_receiver.mobile,order_receiver.address
		FROM  order_info
		LEFT JOIN order_source ON order_info.id = order_source.id
		LEFT JOIN order_delivery ON order_info.id = order_delivery.id
		LEFT JOIN order_receiver ON order_info.id = order_receiver.id ".$where." ORDER BY order_info.id DESC LIMIT ".$limit;
		$result	= mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result))
		{
			$list_audit	= array();

			$sql     = "SELECT store_id,sum(total) AS total FROM order_product WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result1 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result1)){
				//仓库
				$sql = "SELECT name FROM store_info WHERE id = '$res->store_id' AND store_status = 'Normal' AND company_id = '$company_id'";
				$result5 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result5)){
					$list_audit['store_name']		    = $store['name'];
				}
				$list_audit['total']				= $res->total;
			}

			$sql     = "SELECT express_id FROM order_express_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				//快递
				$sql     = "SELECT name FROM company_express_info WHERE express_id = '$res->express_id'  AND company_id = '$company_id'";
				$result3 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result3)){
					$list_audit['express_id']	= $store['name'];
				}
			}

			$sql4 = "SELECT shop_name FROM user_register_info WHERE id = '$StoreInfo->user_id'";
			$result1 = mysql_query($sql4,$_mysql_link_);
			while($store = mysql_fetch_assoc($result1)){
				$list_audit['shop_name']		    = $store['shop_name'];
			}

			$list_audit['id']			        = $StoreInfo->id;
			$list_audit['bind_number']			= $StoreInfo->bind_number;
			$list_audit['order_text']			= $StoreInfo->order_text;
			$list_audit['customer_text']		= $StoreInfo->customer_text;
			$list_audit['name']					= $StoreInfo->name;
			$list_audit['mobile']				= $StoreInfo->mobile;
			$list_audit['address']				= $StoreInfo->address;
			$xtpl->assign("list_audit", $list_audit);
			$xtpl->parse("main.list_audit");
		}
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");