<?php
//拆分订单列表
class Action_order_showchai extends XAction
{
    public function _run($request, $xcontext)
    {
    	$orderid = $request->attr['orderid'];
    	$prolist = XDao::query("OrderproductQuery")->findfororderid($orderid);
    	$pg = new GetFormatStingByProductId();
    	foreach($prolist as $k=>$v){
    		$productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
          	$prolist[$k]['productname'] = $productname['name'];
          	$gui = $pg->getformatstr($v['productid']);
          	$prolist[$k]['gui'] = $gui;
    	}
        if (!$orderid) {
            echo ResultSet::jfail(500, "Server Error：doorderverify Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($prolist);
        return XNext::nothing();
    }
}
// 执行拆分
class Action_order_dochai extends XAction
{
    public function _run($request, $xcontext)
    {
    	$cookuid = $_COOKIE['U'];
        $uidarr = explode('=',$cookuid);
        $userid = $uidarr['2'];//操作人
    	$orderid = $request->attr['order_id'];
    	$pid = $request->attr['product_id'];
    	$number = $request->attr['bind_number'];
    	$procomment = "拆单";
    	//剩余数量
    	$sheng = $request->attr['old_total'];
    	//拆分出的数量
    	$new = $request->attr['new_total'];
    	//查出老订单信息
    	$oldorderinfo = XDao::query("orderinfoQuery")->findforid($orderid);
    	$ci = $oldorderinfo['splittimes']+1;
    	$time = time();
		$onlineid = $oldorderinfo['onlineid'].$ci;
		$orderclass = $oldorderinfo['categoryid'];
		$qudao = $oldorderinfo['channelid'];
		$shop = $oldorderinfo['companyid'];
		$store = $oldorderinfo['storeid'];
		// $oparid = $oldorderinfo['onlineid'];
		$guanlian = $oldorderinfo['relatedid'];
		$serviceid = $oldorderinfo['serviceid'];
		$isreceive = $oldorderinfo['isreceive'];
		$status = $oldorderinfo['orstatus'];
		$isbill = $oldorderinfo['isbill'];
		$billtype = $oldorderinfo['billtype'];
		$comment = $oldorderinfo['comment'];
		$cusmsg = $oldorderinfo['cusmsg'];
		$type = 'N';
		$deliversta = 'E';
      	$desstoresta = 'N';
      	$khid = $oldorderinfo['customerid'];
      	$log = "拆单";
      	$logs = "添加子订单(拆单)";
      	$tar = "Split";
    	//查老订单的商品信息
    	$oldproduct = XDao::query("OrderproductQuery")->findfororderid($orderid);
    	//查老订单的主订单id
    	$mainid = XDao::query("orderftersonQuery")->findparent($orderid);
    	//查询老订单的印刷信息
    	$print = XDao::query("orderprintquery")->findfororderid($orderid);
    	//查询老订单的发货信息
    	$fahuo = XDao::query("orderdeliverquery")->findfororderid($orderid);
      	//获取相关数据
    	foreach($oldproduct as $k=>$v){
    		$syouhui[] = round((floatval($v['discount'])/floatval($v['total']))*floatval($sheng[$k]),2);//剩余的优惠
    		$nyouhui[] = round((floatval($v['discount'])/floatval($v['total']))*floatval($new[$k]),2);//新的优惠
    		$danjia[] = $v['price'];//单价
    		$spay[] = $sheng[$k]*$v['price']-round((floatval($v['discount'])/floatval($v['total']))*floatval($sheng[$k]),2);//剩余应付
    		$npay[] = $new[$k]*$v['price']-round((floatval($v['discount'])/floatval($v['total']))*floatval($new[$k]),2);//新的应付
    	}
    	$syouhuis = array_sum($syouhui);//剩余的总优惠
    	$nyouhuis = array_sum($nyouhui);//新订单总优惠
    	$spays = array_sum($spay);//剩余的总付款
    	$npays = array_sum($npay);//新订单的总付款
    	$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();
    	//修改原订单
    	$chaiedit=OrderinfoSvc::ins()->chaiedit($orderid,$ci,$syouhuis,$spays);
    	//修改原财务关联表
    	$chaipay = XDao::dwriter("OrderfinanceWriter")->chaiedit($orderid,$spays);
    	//原订单商品部分
        $pro = XDao::query("OrderproductQuery")->findfororderid($orderid);
        $pronum = count($pro);
        $del= XDao::dwriter("OrderproductWriter")->del($orderid);
        if($pronum != $del){
			$writer->rollback();
			return XNext::gotourl('/order/orderreviewoffline.php');
        }
		foreach($pid as $k1=>$v1){
		// 订单商品
			if($sheng[$k1]){
				$saddpro=OrderproductSvc::ins()->add($orderid,$v1,$danjia[$k1],$sheng[$k1],$syouhui[$k1],$spay[$k1],$procomment);
			}
			if($saddpro!=1){
				$writer->rollback();
				return XNext::gotourl('/order/orderreviewoffline.php');
			}
		}
		//添加新订单
		$norderid=OrderinfoSvc::ins()->addmainorder($time,$onlineid,$orderclass,$qudao,$shop,$store,$guanlian,$serviceid,$isreceive,$status,$isbill,$billtype,$comment,$nyouhuis,$npays,$cusmsg,$khid,$type,$deliversta,$desstoresta);
		//新订单商品
		foreach($pid as $k2=>$v2){
			if($new[$k2]){
				$np=OrderproductSvc::ins()->add($norderid,$v2,$danjia[$k2],$new[$k2],$nyouhui[$k2],$npay[$k2],$procomment);
				if($np!=1){
					$writer->rollback();
					return XNext::gotourl('/order/orderreviewoffline.php');
				}
			}
		}
		//主子订单关联表插入数据
		foreach($mainid as $k3=>$v3){
			$mainre=OrderftersonSvc::ins()->add($norderid,$v3['porderid']);
			if($mainre!=1){
				$writer->rollback();
				return XNext::gotourl('/order/orderreviewoffline.php');
			}
		}
		//订单财务表
		$res=OrderfinanceSvc::ins()->add($norderid,$npays);
		//操作记录表
		$ress=OrderlogSvc::ins()->add($orderid,$userid,$log);
		$nress=OrderlogSvc::ins()->add($norderid,$userid,$logs);
		//订单印刷表
		$printres=OrderprintSvc::ins()->add($norderid,$print['contents'],$print['affirm']);
		//订单发货表
		$delivertres=OrderDeliverSvc::ins()->add($norderid,$fahuo['type'],$fahuo['transportid'],$fahuo['waybill'],$fahuo['realweight'],$fahuo['freight']);
		//拆分标记表
		$target = OrdersplitSvc::ins()->add($orderid,$tar);
		if($res!=1 || $ress!=1 || $nress!=1 || $printres!=1 || $delivertres!=1 || $target != 1){
			$writer->rollback();
			return XNext::gotourl('/order/orderreviewoffline.php');
		}
		$writer->commit(); 
        return XNext::gotourl('/order/orderreviewoffline.php');
    }
}
// 合并订单列表
class Action_order_showhe extends XAction
{
    public function _run($request, $xcontext)
    {
    	$orderid = $request->attr['orderid'];
    	$list = array();
    	foreach($orderid as $k=>$v){
    		$list[] = XDao::query("orderinfoQuery")->findforid($v);
    	}
    	foreach($list as $k1=>$v1){
			$cus = XDao::query("CustomerInfoQuery")->find($v1['customerid']);
			$list[$k1]['cusname'] = $cus['name'];
			$list[$k1]['address'] = $cus['address'];
			$shop = XDao::query("systemshopQuery")->findone($v1['companyid']);
			$list[$k1]['shopname'] = $shop['name'];
			$store = XDao::query("StoreShowQuery")->findname($v1['storeid']);
			$list[$k1]['storename'] = $store['name'];
			$fahuo = XDao::query("orderdeliverquery")->findfororderid($v1['id']);
			$exname = XDao::query("ecinfoQuery")->findexp($fahuo['transportid']);
			$list[$k1]['exname'] = $exname['name'];
    	}
        if (!$orderid) {
            echo ResultSet::jfail(500, "Server Error：doorderverify Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($list);
        return XNext::nothing();
    }
}
// 执行合并
class Action_order_dohe extends XAction
{
    public function _run($request, $xcontext)
    {
        $cookuid = $_COOKIE['U'];
        $uidarr = explode('=',$cookuid);
        $userid = $uidarr['2'];//操作人
    	$selectid['sele'] = $request->attr['select_one'];
    	$orderid = $request->attr['order_id'];
    	$tar = 'Merge';
        $logs = "合并到订单".$selectid['sele'];
    	$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();
    	$passorder = array_values(array_diff($orderid,$selectid));
    	foreach($passorder as $k1=>$v1){
            //操作记录表
            $recode=OrderlogSvc::ins()->add($v1,$userid,$logs);
    		$del = OrderinfoSvc::ins()->delmain($v1);
    		if($del!=1 || $recode != 1){
				$writer->rollback();
				return XNext::gotourl('/order/orderreviewoffline.php');
			}
    	}
    	$str = implode(",",$passorder);
    	$oldproduct = XDao::query("OrderproductQuery")->findhe($str);
    	foreach($oldproduct as $k=>$v){
			$productid = $v['productid'];
			$total = $v['total'];
			$price = $v['price'];
			$discount = $v['discount'];
			$payment = $v['payment'];
    		$comment = "从订单".$v['orderid']."合并来";
    		$result=OrderproductSvc::ins()->add($selectid['sele'],$productid,$price,$total,$discount,$payment,$comment);
    		if($result!=1){
				$writer->rollback();
				return XNext::gotourl('/order/orderreviewoffline.php');
			}
    	}
    	$row = XDao::query("OrderproductQuery")->findys($selectid['sele']);
    	$edit=OrderinfoSvc::ins()->heedit($selectid['sele'],$row['youhuis'],$row['pays']);
    	$target = OrdersplitSvc::ins()->add($selectid['sele'],$tar);
        //操作记录表
        $log = "合并订单".$str;
        $ress=OrderlogSvc::ins()->add($selectid['sele'],$userid,$log);
    	if($edit!=1 || $target!=1 || $ress != 1){
			$writer->rollback();
			return XNext::gotourl('/order/orderreviewoffline.php');
		}
		$writer->commit(); 
        return XNext::gotourl('/order/orderreviewoffline.php');
    }
}