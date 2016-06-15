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

	//打回审核
	if(!empty($_GET['tiao']) && !empty($_GET['bian']) && !empty($_GET['shen'])){
		$tiao = $_GET['tiao'];
		$bian = $_GET['bian'];
		if($tiao == "D"){
			$sql = "SELECT id FROM order_source WHERE company_id='$company_id' AND bind_number='$bian'";
			$res = mysql_query($sql,$_mysql_link_);
			if(mysql_num_rows($res)>0){
				$mmid = 1;
				while($rows = mysql_fetch_object($res)){
					$oid = $rows->id;
				}
			}else{
				$mmid = 0;
				$oid = "";
			}
		}else{
			$sql = "SELECT order_id FROM order_express_paper WHERE company_id='$company_id' AND number='$bian'";
			$res = mysql_query($sql,$_mysql_link_);
			if(mysql_num_rows($res)>0){
				$mmid = 1;
				while($rows = mysql_fetch_object($res)){
					$oid = $rows->order_id;
				}
			}else{
				$mmid = 0;
				$oid = "";
			}
		}

		$sql = "UPDATE order_info SET is_audit='N',status='N' WHERE company_id='$company_id' AND id='$oid'";
		mysql_query($sql,$_mysql_link_);
		$iid = mysql_affected_rows($_mysql_link_);

		$sql = "UPDATE order_delivery SET delivery_status='Untreated' WHERE company_id='$company_id' AND id='$oid'";
		mysql_query($sql,$_mysql_link_);
		$did = mysql_affected_rows($_mysql_link_);
		if($iid && $did && $mmid){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//打回配货
	if(!empty($_GET['tiao']) && !empty($_GET['bian']) && !empty($_GET['pei'])){
		$tiao = $_GET['tiao'];
		$bian = $_GET['bian'];
		if($tiao == "D"){
			$sql = "SELECT id FROM order_source WHERE company_id='$company_id' AND bind_number='$bian'";
			$res = mysql_query($sql,$_mysql_link_);
			if(mysql_num_rows($res)>0){
				$mmid = 1;
				while($rows = mysql_fetch_object($res)){
					$oid = $rows->id;
				}
			}else{
				$mmid = 0;
				$oid = "";
			}
		}else{
			$sql = "SELECT order_id FROM order_express_paper WHERE company_id='$company_id' AND number='$bian'";
			$res = mysql_query($sql,$_mysql_link_);
			if(mysql_num_rows($res)>0){
				$mmid = 1;
				while($rows = mysql_fetch_object($res)){
					$oid = $rows->order_id;
				}
			}else{
				$mmid = 0;
				$oid = "";
			}
		}

		$sql = "UPDATE order_info SET status='N' WHERE company_id='$company_id' AND id='$oid'";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);

		$sql = "UPDATE order_delivery SET delivery_status='Untreated' WHERE company_id='$company_id' AND id='$oid'";
		mysql_query($sql,$_mysql_link_);
		$did = mysql_affected_rows($_mysql_link_);
		if($id && $did && $mmid){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//打回异常
	if(!empty($_GET['tiao']) && !empty($_GET['bian']) && !empty($_GET['yichang'])){
		$tiao = $_GET['tiao'];
		$bian = $_GET['bian'];
		if($tiao == "D"){
			$sql = "SELECT id FROM order_source WHERE company_id='$company_id' AND bind_number='$bian'";
			$res = mysql_query($sql,$_mysql_link_);
			if(mysql_num_rows($res)>0){
				while($rows = mysql_fetch_object($res)){
					$oid = $rows->id;
				}
				echo json_encode($oid);
				exit;
			}else{
				echo json_encode("0");
				exit;
			}
		}else{
			$sql = "SELECT order_id FROM order_express_paper WHERE company_id='$company_id' AND number='$bian'";
			$res = mysql_query($sql,$_mysql_link_);
			if(mysql_num_rows($res)>0){
				while($rows = mysql_fetch_object($res)){
					$oid = $rows->order_id;
				}
				echo json_encode($oid);
				exit;
			}else{
				echo json_encode("0");
				exit;
			}
		}
	}

	//确认发货
	if(!empty($_GET['fa_id'])){

		$id     = intval($_GET['fa_id']);
		$stat   = false;
		$aid    = 0;
		$iid    = 0;
		$oid    = 0;

		$sql 	= "SELECT order_express_paper.number, order_express_paper.express_id, order_source.bind_number, order_source.bind_type FROM order_express_paper LEFT JOIN order_source ON order_express_paper.order_id = order_source.id WHERE order_express_paper.company_id='$company_id' AND order_express_paper.order_id = '$id'";
		$res 	= mysql_query($sql,$_mysql_link_);
		$this   = mysql_fetch_object($res);
		if(mysql_num_rows($res)>0){
			if($this->bind_type == "System"){
				$stat = true;
				$sql = "UPDATE order_delivery SET delivery_status = 'Finish',action_date=NOW() WHERE company_id='$company_id' AND id = '$id'";
				mysql_query($sql,$_mysql_link_);
				$aid = mysql_affected_rows($_mysql_link_);

				$sql = "UPDATE order_info SET status = 'S' WHERE company_id='$company_id' AND is_delete='N' AND id = '$id'";
				mysql_query($sql,$_mysql_link_);
				$iid = mysql_affected_rows($_mysql_link_);

				$sql = "UPDATE order_express_paper SET deliver_date = NOW() WHERE company_id='$company_id' AND order_id = '$id'";
				mysql_query($sql,$_mysql_link_);
				$oid = mysql_affected_rows($_mysql_link_);

				$sql    = "SELECT product_id,total,store_id FROM order_product WHERE order_id = '$id' AND company_id='$company_id'";
				$result = mysql_query($sql,$_mysql_link_);
				while($res = mysql_fetch_object($result)){
					$sql = "UPDATE store_related SET real_total=real_total-'$res->total',lock_total=lock_total-'$res->total' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$res->product_id'";
					mysql_query($sql,$_mysql_link_);
					$sql = "UPDATE store_product SET total_real=total_real-'$res->total',total_lock=total_lock-'$res->total' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$res->product_id'";
					mysql_query($sql,$_mysql_link_);
				}
			}else{

				$sql  = "SELECT name FROM main_express_info WHERE status = 'Y' AND id='$this->express_id'";
				$res1 = mysql_query($sql,$_mysql_link_);
				$dbRow = mysql_fetch_object($res1);

				$out_sid  = $this->number;		//快递单号
				$name 	  = $dbRow->name;       // 快递名字的code
				$tid      = $this->bind_number; //订单编号

				//获取快递公司的CODE(查询物流公司信息)
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
							$sql = "UPDATE order_delivery SET delivery_status = 'Finish',action_date=NOW() WHERE company_id='$company_id' AND id = '$id'";
							mysql_query($sql,$_mysql_link_);
							$aid = mysql_affected_rows($_mysql_link_);

							$sql = "UPDATE order_info SET status = 'S' WHERE company_id='$company_id' AND is_delete='N' AND id = '$id'";
							mysql_query($sql,$_mysql_link_);
							$iid = mysql_affected_rows($_mysql_link_);

							$sql = "UPDATE order_express_paper SET deliver_date = NOW() WHERE company_id='$company_id' AND order_id = '$id'";
							mysql_query($sql,$_mysql_link_);
							$oid = mysql_affected_rows($_mysql_link_);

							$sql    = "SELECT product_id,total,store_id FROM order_product WHERE order_id = '$id' AND company_id='$company_id'";
							$result = mysql_query($sql,$_mysql_link_);
							while($res = mysql_fetch_object($result)){
								$sql = "UPDATE store_related SET real_total=real_total-'$res->total',lock_total=lock_total-'$res->total' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$res->product_id'";
								mysql_query($sql,$_mysql_link_);
								$sql = "UPDATE store_product SET total_real=total_real-'$res->total',total_lock=total_lock-'$res->total' WHERE store_id ='$res->store_id' AND company_id='$company_id' AND product_id='$res->product_id'";
								mysql_query($sql,$_mysql_link_);
							}
						}else{
							$stat = false;
						}
					}
				}
			}
			if($aid && $iid && $stat && $oid){
				echo json_encode(1);
			}else{
				echo json_encode(0);
			}
			exit;
		}else{
			echo json_encode(0);
			exit;
		}


	}

	//待发货数量
	$sql = "SELECT count(*) AS total  FROM order_info AS i
			LEFT JOIN order_delivery AS d ON i.id=d.id WHERE i.company_id='$company_id' AND i.status='W' AND i.is_delete='N' AND i.is_audit='Y' AND d.delivery_status='Picking' AND i.unusual_id='0'";
	$result	= mysql_query($sql, $_mysql_link_);
	$main['total']	= mysql_result($result, 0, 'total');


	if(!empty($_POST['val'])){
		$val  = replace_safe($_POST['val']); //编号
		$tiao = replace_safe($_POST['tiao']);//查询条件
		$num = 1;
		$stat   = false;
		$did    = 0;	//order_delivery 发货单状态
		$iid    = 0;    //order_info 订单状态
		$oid    = 0;	//order_express_info快递单状态

		if($tiao == "D"){

			$sql = "SELECT i.unusual_id,i.id,order_source.bind_type FROM order_source
			LEFT JOIN order_info AS i ON order_source.id=i.id WHERE order_source.bind_number='$val'";
			$query = mysql_query($sql,$_mysql_link_);
			$resf = mysql_fetch_object($query);
			//判断是否是异常订单
			if($resf->unusual_id == "0"){
				//判断是线上订单还是线下订单
				if($resf->bind_type == "System"){

					$sql = "UPDATE order_info SET status='S' WHERE company_id='$company_id' AND id='$resf->id'";
					mysql_query($sql,$_mysql_link_);
					$iid = mysql_affected_rows($_mysql_link_);

					$Time = date('Y-m-d H:i:s',time());
					$sql = "UPDATE order_delivery SET delivery_status='Finish',action_date='$Time' WHERE company_id='$company_id' AND id='$resf->id'";
					mysql_query($sql,$_mysql_link_);
					$did = mysql_affected_rows($_mysql_link_);

					$sql = "UPDATE order_express_paper SET deliver_date = NOW() WHERE company_id='$company_id' AND order_id = '$resf->id'";
					mysql_query($sql,$_mysql_link_);
					$oid = mysql_affected_rows($_mysql_link_);

					//商品信息
					$sql    = "SELECT product_id,total,store_id,payment FROM order_product WHERE order_id = '$resf->id' AND company_id='$company_id'";
					$result = mysql_query($sql,$_mysql_link_);
					while($res = mysql_fetch_object($result)){
						//商品的库存减少
						$sql = "UPDATE store_related SET real_total=real_total-'{$res->total}',lock_total=lock_total-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
						mysql_query($sql,$_mysql_link_);
						$mm =  mysql_affected_rows($_mysql_link_);
						//商品库存减少
						$sql = "UPDATE store_product SET total_real=total_real-'{$res->total}',total_lock=total_lock-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
						mysql_query($sql,$_mysql_link_);
						$nn =  mysql_affected_rows($_mysql_link_);
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
										$sql = "SELECT theory_amount from finance_order  WHERE order_id='$resf->id' AND company_id='$company_id'";
										$sac = mysql_query($sql,$_mysql_link_);
								 		while($rwq = mysql_fetch_object($sac)){
								 			//应收金额的（组合金额）
								 			$ysjf = $rwq->theory_amount;
								 			//当前子商品的销售额
								 			// var_dump($jyp);
								 			$dawq = ($zspr*$total)/$mlp;
								 			// var_dump($dawq);
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
							 $sql = "SELECT theory_amount from finance_order  WHERE order_id='$resf->id' AND company_id='$company_id'";
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
								}

							 }
						}

					}
					$stat = true;
				}else{
					//---- 线上的订单发货 ----
					//通过订单号得到快递单号和快递公司id
					$sql 	= "SELECT order_express_paper.number, order_express_paper.express_id FROM order_express_paper  WHERE order_express_paper.company_id='$company_id' AND order_id = '$resf->id'";
					$res 	= mysql_query($sql,$_mysql_link_);
					$data   = mysql_fetch_object($res);
					//查询快递公司的名称
					$sql   = "SELECT name FROM main_express_info WHERE status = 'Y' AND id='$data->express_id'";
					$res1  = mysql_query($sql,$_mysql_link_);
					$dbRow = mysql_fetch_object($res1);
					$out_sid  = $data->number;		//快递单号
					$name 	  = $dbRow->name;       // 快递名字
					$tid      = $val;               //订单编号

					//获取淘宝中快递公司的code id name(查询物流公司信息)
					$params				= array();
					$params['method']	= 'taobao.logistics.companies.get';
					$params['fields']	= 'id, code, name';
					$params['order_mode'] = 'offline';

					$params['nick']		= $_SESSION['_application_info_']['nick'];
					$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
					$json_data			= json_decode($site_str);
					header("Content-Type: text/html; charset=UTF-8");
					//快递的 id name code
					$companies = $json_data->logistics_companies_get_response->logistics_companies->logistics_company;
					for($i=0;$i<count($companies);$i++){
						if($companies[$i]->name == $name){
							// (自己联系物流（线下物流）发货)
							$params				    = array();
							$params['method']	    = 'taobao.logistics.offline.send';
							$params['tid']		    = $tid;  //订单编号
							$params['out_sid']		= $out_sid; //快递单号
							$params['company_code'] = $companies[$i]->code; //物流公司代码

							$params['nick']		= $_SESSION['_application_info_']['nick'];
							$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
							$deliver			= json_decode($site_str);

							header("Content-Type: text/html; charset=UTF-8");
							//返回的结果信息 返回发货是否成功。
							$status = $deliver->logistics_offline_send_response->shipping->is_success;
							echo '<pre>';
							var_dump($deliver);
							die();
							if($status){
								$stat = true;
								$sql = "UPDATE order_delivery SET delivery_status = 'Finish',action_date=NOW() WHERE company_id='$company_id' AND id = '$resf->id'";
								mysql_query($sql,$_mysql_link_);
								$did = mysql_affected_rows($_mysql_link_);

								$sql = "UPDATE order_info SET status = 'S' WHERE company_id='$company_id' AND is_delete='N' AND id = '$resf->id'";
								mysql_query($sql,$_mysql_link_);
								$iid = mysql_affected_rows($_mysql_link_);

								$sql = "UPDATE order_express_paper SET deliver_date = NOW() WHERE company_id='$company_id' AND order_id = '$resf->id'";
								mysql_query($sql,$_mysql_link_);
								$oid = mysql_affected_rows($_mysql_link_);

								$sql    = "SELECT product_id,total,store_id,payment FROM order_product WHERE order_id = '$resf->id' AND company_id='$company_id'";
								$result = mysql_query($sql,$_mysql_link_);
								while($res = mysql_fetch_object($result)){
									$sql = "UPDATE store_related SET real_total=real_total-'{$res->total}',lock_total=lock_total-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
									mysql_query($sql,$_mysql_link_);
									$sql = "UPDATE store_product SET total_real=total_real-'{$res->total}',total_lock=total_lock-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
									mysql_query($sql,$_mysql_link_);
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
													$sql = "SELECT theory_amount from finance_order  WHERE order_id='$resf->id' AND company_id='$company_id'";
													$sac = mysql_query($sql,$_mysql_link_);
											 		while($rwq = mysql_fetch_object($sac)){
											 			//应收金额的（组合金额）
											 			$ysjf = $rwq->theory_amount;
											 			//当前子商品的销售额
											 			// var_dump($jyp);
											 			$dawq = ($zspr*$total)/$mlp;
											 			// var_dump($dawq);
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
										 $sql = "SELECT theory_amount from finance_order  WHERE order_id='$resf->id' AND company_id='$company_id'";
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
											}

										 }
									}
								}
							}else{
								$stat = false;
							}
						}
					}

					echo '<pre>';
					var_dump('iid:'.$iid.'---did:'.$did.'---stat:'.$stat.'---oid:'.$oid);
					die();
				}
				if($iid && $did && $stat && $oid){
					echo json_encode(1);
				}else{
					echo json_encode(0);
				}
				exit;

			}else{
				$sql = "SELECT name FROM company_unusual WHERE company_id='$company_id' AND id='$resf->unusual_id'";
				$this = mysql_query($sql,$_mysql_link_);
				$re = mysql_fetch_object($this);
				echo json_encode($re->name);
				exit;
			}
		}elseif($tiao == "K"){

			$sql = "SELECT i.unusual_id,i.id,e.express_id,s.bind_type,s.bind_number FROM order_express_paper AS e
			LEFT JOIN order_info AS i ON e.order_id=i.id
			LEFT JOIN order_source AS s ON e.order_id=s.id WHERE e.number='$val'";
			$query = mysql_query($sql,$_mysql_link_);
			$resf = mysql_fetch_object($query);
			if($resf->unusual_id == "0"){
				if($resf->bind_type == "System"){

					$sql = "UPDATE order_info SET status='S' WHERE company_id='$company_id' AND id='$resf->id'";
					mysql_query($sql,$_mysql_link_);
					$iid = mysql_affected_rows($_mysql_link_);

					$sql = "UPDATE order_delivery SET delivery_status='Finish' WHERE company_id='$company_id' AND id='$resf->id'";
					mysql_query($sql,$_mysql_link_);
					$did = mysql_affected_rows($_mysql_link_);

					$sql = "UPDATE order_express_paper SET deliver_date = NOW() WHERE company_id='$company_id' AND order_id = '$resf->id'";
					mysql_query($sql,$_mysql_link_);
					$oid = mysql_affected_rows($_mysql_link_);

					$sql    = "SELECT product_id,total,store_id,payment FROM order_product WHERE order_id = '$resf->id' AND company_id='$company_id'";
					$result = mysql_query($sql,$_mysql_link_);
					while($res = mysql_fetch_object($result)){
						$sql = "UPDATE store_related SET real_total=real_total-'{$res->total}',lock_total=lock_total-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
						mysql_query($sql,$_mysql_link_);
						$mm = mysql_affected_rows($_mysql_link_);

						$sql = "UPDATE store_product SET total_real=total_real-'{$res->total}',total_lock=total_lock-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
						mysql_query($sql,$_mysql_link_);
						$nn = mysql_affected_rows($_mysql_link_);
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
										$sql = "SELECT theory_amount from finance_order  WHERE order_id='$resf->id' AND company_id='$company_id'";
										$sac = mysql_query($sql,$_mysql_link_);
								 		while($rwq = mysql_fetch_object($sac)){
								 			//应收金额的（组合金额）
								 			$ysjf = $rwq->theory_amount;
								 			//当前子商品的销售额
								 			// var_dump($jyp);
								 			$dawq = ($zspr*$total)/$mlp;
								 			// var_dump($dawq);
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
							 $sql = "SELECT theory_amount from finance_order  WHERE order_id='$resf->id' AND company_id='$company_id'";
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
								}

							 }
						}
					}
					if($mm && $nn){
						$stat = true;
					}else{
						$stat = false;
					}

				}else{

					$sql   = "SELECT name FROM main_express_info WHERE status = 'Y' AND company_id='$company_id' AND id='$resf->express_id'";
					$res1  = mysql_query($sql,$_mysql_link_);
					$dbRow = mysql_fetch_object($res1);

					$out_sid  = $resf->number;		//订单编号
					$name 	  = $dbRow->name;       //快递名字的code
					$tid      = $val;               //快递单号

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
								$sql = "UPDATE order_delivery SET delivery_status = 'Finish',action_date=NOW() WHERE company_id='$company_id' AND id = '$resf->id'";
								mysql_query($sql,$_mysql_link_);
								$did = mysql_affected_rows($_mysql_link_);

								$sql = "UPDATE order_info SET status = 'S' WHERE company_id='$company_id' AND is_delete='N' AND id = '$resf->id'";
								mysql_query($sql,$_mysql_link_);
								$iid = mysql_affected_rows($_mysql_link_);

								$sql = "UPDATE order_express_paper SET deliver_date = NOW() WHERE company_id='$company_id' AND order_id = '$resf->id'";
								mysql_query($sql,$_mysql_link_);
								$oid = mysql_affected_rows($_mysql_link_);

								$sql    = "SELECT product_id,total,store_id,payment FROM order_product WHERE order_id = '$resf->id' AND company_id='$company_id'";
								$result = mysql_query($sql,$_mysql_link_);
								while($res = mysql_fetch_object($result)){
									$sql = "UPDATE store_related SET real_total=real_total-'{$res->total}',lock_total=lock_total-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
									mysql_query($sql,$_mysql_link_);
									$sql = "UPDATE store_product SET total_real=total_real-'{$res->total}',total_lock=total_lock-'{$res->total}' WHERE store_id ='{$res->store_id}' AND company_id='$company_id' AND product_id='{$res->product_id}'";
									mysql_query($sql,$_mysql_link_);
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
													$sql = "SELECT theory_amount from finance_order  WHERE order_id='$resf->id' AND company_id='$company_id'";
													$sac = mysql_query($sql,$_mysql_link_);
											 		while($rwq = mysql_fetch_object($sac)){
											 			//应收金额的（组合金额）
											 			$ysjf = $rwq->theory_amount;
											 			//当前子商品的销售额
											 			// var_dump($jyp);
											 			$dawq = ($zspr*$total)/$mlp;
											 			// var_dump($dawq);
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
										 $sql = "SELECT theory_amount from finance_order  WHERE order_id='$resf->id' AND company_id='$company_id'";
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
				if($iid && $did && $oid && $stat){
					echo json_encode(1);
				}else{
					echo json_encode(0);
				}
				exit;
			}else{
				$sql = "SELECT name FROM company_unusual WHERE company_id='$company_id' AND id='$resf->unusual_id'";
				$this = mysql_query($sql,$_mysql_link_);
				$re = mysql_fetch_object($this);
				echo json_encode($re->name);
				exit;
			}
		}

		// echo json_encode($bind);
		// exit;
	}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");