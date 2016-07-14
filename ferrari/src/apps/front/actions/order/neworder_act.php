<?php
/**
 *  @brief 新增定单页面
 */
class Action_order_neworder extends XAction
{
    public function _run($request, $xcontext)
    {
    	//分类
    	$orderclass = XDao::query("ordercategoryQuery")->findall();
    	//店铺
    	$shop = XDao::query("systemshopQuery")->findall();
    	//仓库
    	$store = XDao::query("StoreShowQuery")->listStoreInfo();
    	//渠道
    	$qudao = XDao::query("companysalesQuery")->allcompanysalesinfo();
    	//快递公司
    	$kuaidi = XDao::query("ecinfoQuery")->allexp();
    	//物流公司
    	$wuliu = XDao::query("TransportinfoQuery")->findall();
    	//操作人
    	$cookuid                  = $_COOKIE['U'];
    	$uidarr                   = explode('=',$cookuid);
    	$username = XDao::query("UserQuery")->userone($uidarr['2']);
    	$username = $username['name'];
    	$xcontext->orderclass = $orderclass;
    	$xcontext->qudao = $qudao;
    	$xcontext->username = $username;
    	$xcontext->shop = $shop;
    	$xcontext->store = $store;
    	$xcontext->kuaidi = $kuaidi;
    	$xcontext->wuliu = $wuliu;
    	$xcontext->userid = $uidarr['2'];
        return XNext::useTpl("/order/neworder.html");
    }
}
//验证所属订单
class Action_order_checknumber extends XAction
{
    public function _run($request, $xcontext)
    {
    	$number = $request->attr['number'];
	    $numarr = explode(",",$number);
	    foreach($numarr as $k=>$v){
            $result = XDao::query("orderinfoQuery")->findnum($v);
            if($result['count']!=1){
                echo "no";
            }
	    }
	    echo "ok";
    }
}
//执行添加子定单
class Action_order_doaddorder extends XAction
{
    public function _run($request, $xcontext)
    {
    	//订单主表数据
		$time = $request->attr['time'];
		$onlineid = $request->attr['onlineid'];
        if(!$onlineid){
            $onlineid = time();
          }
		$orderclass = $request->attr['orderclass'];
		$qudao = $request->attr['qudao'];
		$shop = $request->attr['shop'];
		$store = $request->attr['store'];
		$oparid = $request->attr['oparid'];
		$guanlian = $request->attr['guanlian'];
		$serviceid = $request->attr['serviceid'];
		$isreceive = $request->attr['isreceive'];
		$status = $request->attr['status'];
		$isbill = $request->attr['isbill'];
		$billtype = $request->attr['billtype'];
		$comment = $request->attr['comment'];
		$type = 'N';
		$deliversta = 'E';
      	$desstoresta = 'N';
		// 客户
		$khname = $request->attr['khname'];
		$khid = $request->attr['khid'];
		$nick = $request->attr['nick'];
		$mobile = $request->attr['mobile'];
		$telphone = $request->attr['telphone'];
		$postcode = $request->attr['postcode'];
		$companyname = $request->attr['companyname'];
		$stateid = $request->attr['stateid'];
		$cityid = $request->attr['cityid'];
		$districtid = $request->attr['districtid'];
		$address = $request->attr['address'];
		$cusmsg = $request->attr['cusmsg'];
		//商品
		$proid = $request->attr['proid'];
		$singleprice = $request->attr['singleprice'];
		$goodsnum = $request->attr['goodsnum'];
		$youhui = $request->attr['youhui'];
		$pay = $request->attr['pay'];
		$procomment = $request->attr['procomment'];
		$youhuis = $request->attr['youhuis'];
		$pays = $request->attr['pays'];
		$log = "添加子订单";
		//印刷
		$printcomment = $request->attr['printcomment'];
		$printinfo = $request->attr['printinfo'];
		//发货
		$radio = $request->attr['radio'];
		if($radio == 'K'){
			$types = 'K';
			$transportid = $request->attr['kuaidi'];
			$waybill = $request->attr['kdnumber'];
			$realweight = $request->attr['kdweight'];
			$freight = $request->attr['kdmoney'];
		}else if($radio == 'W'){
			$types = 'W';
			$transportid = $request->attr['wuliu'];
			$waybill = $request->attr['wlnumber'];
			$realweight = '';
			$freight = $request->attr['wlmoney'];
		}else{
			$types = 'N';
			$transportid = "";
			$waybill = "";
			$realweight = "";
			$freight = "";
		}
		$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();
		if(!$khid){
			//客户信息
			$khid=CustomerInfoSvc::ins()->add($khname,$nick,$mobile,$telphone,$postcode,$companyname,$stateid,$cityid,$districtid,$address);
		}
		//订单主表
		$orderid=OrderinfoSvc::ins()->addmainorder($time,$onlineid,$orderclass,$qudao,$shop,$store,$guanlian,$serviceid,$isreceive,$status,$isbill,$billtype,$comment,$youhuis,$pays,$cusmsg,$khid,$type,$deliversta,$desstoresta);
		if($proid){
			foreach($proid as $k=>$v){
				// 订单商品
				$result=OrderproductSvc::ins()->add($orderid,$v,$singleprice[$k],$goodsnum[$k],$youhui[$k],$pay[$k],$procomment[$k]);
				if($result!=1){
					$writer->rollback();
				}
			}
		}
		$arroparid = explode(",",$oparid);
		foreach($arroparid as $k=>$v){
			$guanlian=OrderftersonSvc::ins()->add($orderid,$v);
			if($guanlian!=1){
					$writer->rollback();
				}
		}
		//订单财务表
		$res=OrderfinanceSvc::ins()->add($orderid,$pays);
		//操作记录表
		$ress=OrderlogSvc::ins()->add($orderid,$serviceid,$log);
		//订单印刷表
		$printres=OrderprintSvc::ins()->add($orderid,$printcomment,$printinfo);
		//订单发货表
		$delivertres=OrderDeliverSvc::ins()->add($orderid,$types,$transportid,$waybill,$realweight,$freight);
		if($res!=1 || $ress!=1 || $printres!=1 || $delivertres!=1){
			$writer->rollback();
		}
		$writer->commit();
		return XNext::gotourl('/order/orderreviewoffline.php');
    }
}
//修改子订单
class Action_order_editorder extends XAction
{
    public function _run($request, $xcontext)
    {
    	//分类
        $orderclass = XDao::query("ordercategoryQuery")->findall();
        //店铺
        $shop = XDao::query("systemshopQuery")->findall();
        //仓库
        $store = XDao::query("StoreShowQuery")->listStoreInfo();
        //渠道
        $qudao = XDao::query("companysalesQuery")->allcompanysalesinfo();
        //快递公司
    	$kuaidi = XDao::query("ecinfoQuery")->allexp();
    	//物流公司
    	$wuliu = XDao::query("TransportinfoQuery")->findall();
        $id = $request->attr['orderid'];
        $list = XDao::query("orderinfoQuery")->findforid($id);
        $username = XDao::query("UserQuery")->userone($list['serviceid']);
        $username = $username['name'];
        $kehuname = XDao::query("CustomerInfoQuery")->find($list['customerid']);
        $kehuname = $kehuname['name'];
        //查找商品
        $pro = XDao::query("OrderproductQuery")->findfororderid($id);
        $pg = new GetFormatStingByProductId();
        foreach($pro as $k=>$v){
            $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
            $pro[$k]['productname'] = $productname['name'];
            $unitid = XDao::query("ListFormatQuery")->getformatbyproid($v['productid']);
            $unitid = $unitid[0]['unitid'];
            $dwname = XDao::query("ListProunitQuery")->getdwname($unitid);
            $pro[$k]['dwname'] = $dwname['name'];
            $gui = $pg->getformatstr($v['productid']);
            $pro[$k]['gui'] = $gui;
        }
        //查找印刷信息
        $print = XDao::query("orderprintquery")->findfororderid($id);
        //查找发货信息
        $deliver = XDao::query("orderdeliverquery")->findfororderid($id);
        //查找所属订单id
        $porderid = XDao::query("orderftersonQuery")->findparent($id);
        foreach ($porderid as $key => $value) {
        	$pidarr[] = $value['porderid'];
        }
        $strpid = implode(",",$pidarr);
        $xcontext->list = $list;
        $xcontext->qudao = $qudao;
        $xcontext->orderclass = $orderclass;
        $xcontext->shop = $shop;
        $xcontext->store = $store;
        $xcontext->username = $username;
        $xcontext->kehuname = $kehuname;
        $xcontext->pro = $pro;
        $xcontext->kuaidi = $kuaidi;
    	$xcontext->wuliu = $wuliu;
    	$xcontext->print = $print;
    	$xcontext->deliver = $deliver;
    	$xcontext->strpid = $strpid;
        return XNext::useTpl("/order/editorder.html");
    }
}
//执行修改子订单
class Action_order_doeditorder extends XAction
{
    public function _run($request, $xcontext)
    {
    	$id = $request->attr['orderid'];
        $time = $request->attr['time'];
        $onlineid = $request->attr['onlineid'];
        $orderclass = $request->attr['orderclass'];
        $qudao = $request->attr['qudao'];
        $shop = $request->attr['shop'];
		$oparid = $request->attr['oparid'];
        $store = $request->attr['store'];
        $guanlian = $request->attr['guanlian'];
        $serviceid = $request->attr['serviceid'];
        $isreceive = $request->attr['isreceive'];
        $status = $request->attr['status'];
        $isbill = $request->attr['isbill'];
        $billtype = $request->attr['billtype'];
        $comment = $request->attr['comment'];
        $type = 'N';
        $khname = $request->attr['khname'];
        $khid = $request->attr['khid'];
        $nick = $request->attr['nick'];
        $mobile = $request->attr['mobile'];
        $telphone = $request->attr['telphone'];
        $postcode = $request->attr['postcode'];
        $companyname = $request->attr['companyname'];
        $stateid = $request->attr['stateid'];
        $cityid = $request->attr['cityid'];
        $districtid = $request->attr['districtid'];
        $address = $request->attr['address'];
        $cusmsg = $request->attr['cusmsg'];
        $proid = $request->attr['proid'];
        $singleprice = $request->attr['singleprice'];
        $goodsnum = $request->attr['goodsnum'];
        $youhui = $request->attr['youhui'];
        $pay = $request->attr['pay'];
        $procomment = $request->attr['procomment'];
        $youhuis = $request->attr['youhuis'];
        $pays = $request->attr['pays'];
        $log = "编辑子订单";
        $cookuid = $_COOKIE['U'];
        $uidarr = explode('=',$cookuid);
        $userid = $uidarr['2'];
        //印刷
		$printcomment = $request->attr['printcomment'];
		$printinfo = $request->attr['printinfo'];
		//发货
		$radio = $request->attr['radio'];
		if($radio == 'K'){
			$types = 'K';
			$transportid = $request->attr['kuaidi'];
			$waybill = $request->attr['kdnumber'];
			$realweight = $request->attr['kdweight'];
			$freight = $request->attr['kdmoney'];
		}else if($radio == 'W'){
			$types = 'W';
			$transportid = $request->attr['wuliu'];
			$waybill = $request->attr['wlnumber'];
			$realweight = '';
			$freight = $request->attr['wlmoney'];
		}else{
			$types = 'N';
			$transportid = "";
			$waybill = "";
			$realweight = "";
			$freight = "";
		}
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();
        if(!$khid){
          //客户信息
            $khid=CustomerInfoSvc::ins()->add($khname,$nick,$mobile,$telphone,$postcode,$companyname,$stateid,$cityid,$districtid,$address);
        }
        // 修改订单主表
        $orderres=OrderinfoSvc::ins()->editmain($id,$time,$onlineid,$orderclass,$qudao,$shop,$store,$guanlian,$serviceid,$isreceive,$status,$isbill,$billtype,$comment,$youhuis,$pays,$cusmsg,$khid,$type);
        //修改所属订单关联表
        //先删
        $fterson= XDao::dwriter("OrderftersonWriter")->del($id);
        //再加
        $arroparid = explode(",",$oparid);
		foreach($arroparid as $k=>$v){
			$guanlian=OrderftersonSvc::ins()->add($id,$v);
			if($guanlian!=1){
                $writer->rollback();
                return XNext::gotourl('/order/orderreviewoffline.php');
			}
		}
        //商品部分
        $pro = XDao::query("OrderproductQuery")->findfororderid($id);
        $pronum = count($pro);
        $del= XDao::dwriter("OrderproductWriter")->del($id);
        if($pronum != $del){
            $writer->rollback();
            return XNext::gotourl('/order/orderreviewoffline.php');
        }
        if($proid){
            foreach($proid as $k=>$v){
                // 订单商品
                $result=OrderproductSvc::ins()->add($id,$v,$singleprice[$k],$goodsnum[$k],$youhui[$k],$pay[$k],$procomment[$k]);
                if($result!=1){
                    $writer->rollback();
                    return XNext::gotourl('/order/orderreviewoffline.php');
                }
            }
        }
        //订单印刷表
        $pres=XDao::dwriter("orderprintWriter")->update($printcomment,$printinfo,$id);
        //发货方式表
        $deres=XDao::dwriter("OrderDeliverWriter")->update($transportid,$waybill,$freight,$realweight,$types,$id);
        //订单财务表
        $res=XDao::dwriter("OrderfinanceWriter")->editfororderid($pays,$pays,$id);
        //操作记录表
        $ress=OrderlogSvc::ins()->add($id,$userid,$log);
        if($res!=1 || $ress!=1 || $pres!=1|| $deres!=1){
            $writer->rollback();
            return XNext::gotourl('/order/orderreviewoffline.php');
        }
        $writer->commit();
        return XNext::gotourl('/order/orderreviewoffline.php');
    }
}
//待收款编辑
class Action_order_editpayment extends XAction
{
    public function _run($request, $xcontext)
    {
    	//分类
        $orderclass = XDao::query("ordercategoryQuery")->findall();
        //店铺
        $shop = XDao::query("systemshopQuery")->findall();
        //仓库
        $store = XDao::query("StoreShowQuery")->listStoreInfo();
        //渠道
        $qudao = XDao::query("companysalesQuery")->allcompanysalesinfo();
        //快递公司
    	$kuaidi = XDao::query("ecinfoQuery")->allexp();
    	//物流公司
    	$wuliu = XDao::query("TransportinfoQuery")->findall();
        $id = $request->attr['orderid'];
        $list = XDao::query("orderinfoQuery")->findforid($id);
        $payin = XDao::query("orderfinanceQuery")->findone($id);
        $username = XDao::query("UserQuery")->userone($list['serviceid']);
        $username = $username['name'];
        $kehuname = XDao::query("CustomerInfoQuery")->find($list['customerid']);
        $kehuname = $kehuname['name'];
        $zhanghu = XDao::query("financebankQuery")->allfinancebank();
        $kemu = XDao::query("financialaccountQuery")->allfinan();
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catekemu = get_sort_by_array($kemu);
        if (count($catekemu)) {
            foreach($catekemu as $k=>&$v) {
                $v['name'] = str_repeat("|--", $v['level'] - 1).$v['name'];
            }
        }
        //查找商品
        $pro = XDao::query("OrderproductQuery")->findfororderid($id);
        $pg = new GetFormatStingByProductId();
        foreach($pro as $k=>$v){
            $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
            $pro[$k]['productname'] = $productname['name'];
            $pro[$k]['image'] = $productname['image'];
            $unitid = XDao::query("ListFormatQuery")->getformatbyproid($v['productid']);
            $unitid = $unitid[0]['unitid'];
            $dwname = XDao::query("ListProunitQuery")->getdwname($unitid);
            $pro[$k]['dwname'] = $dwname['name'];
            $gui = $pg->getformatstr($v['productid']);
            $pro[$k]['gui'] = $gui;
        }
        //查找印刷信息
        $print = XDao::query("orderprintquery")->findfororderid($id);
        //查找发货信息
        $deliver = XDao::query("orderdeliverquery")->findfororderid($id);
        $recode = XDao::query("orderlogQuery")->findfororderid($id);
         //查找所属订单id
        $porderid = XDao::query("orderftersonQuery")->findparent($id);
        if($porderid){
            foreach ($porderid as $key => $value) {
            	$pidarr[] = $value['porderid'];
            }
            $strpid = implode(",",$pidarr);
        }else{
            $strpid = "";
        }

        /*订单图片*/
        $ordermsgdata = XDao::query('ListOrderMsgImgQuery')->getOrderImg($id);
        foreach ($ordermsgdata as $key => &$value) {
            $value['path'] = ProImage::IMAGEPATH;
        }

        $xcontext->ordermsgdata = $ordermsgdata; //订单图片
        $xcontext->list = $list;
        $xcontext->qudao = $qudao;
        $xcontext->orderclass = $orderclass;
        $xcontext->shop = $shop;
        $xcontext->store = $store;
        $xcontext->username = $username;
        $xcontext->kehuname = $kehuname;
        $xcontext->kemu = $catekemu;
        $xcontext->zhanghu = $zhanghu;
        $xcontext->pro = $pro;
        $xcontext->kuaidi = $kuaidi;
    	$xcontext->wuliu = $wuliu;
    	$xcontext->print = $print;
    	$xcontext->deliver = $deliver;
    	$xcontext->payin = $payin;
        $xcontext->recode = $recode;
        $xcontext->strpid = $strpid;
        return XNext::useTpl("/order/editpayment.html");
    }
}
//执行子订单收款
class Action_order_doeditpayment extends XAction
{
    public function _run($request, $xcontext)
    {
    	$orderid = $request->attr['orderid'];
        $shishou = $request->attr['shishou'];
        $qiankuan = $request->attr['qiankuan'];
        $zhanghao = $request->attr['zhanghao'];
        $ruzhang = $request->attr['ruzhang'];
        $kemu = $request->attr['kemu'];
        $cookuid = $_COOKIE['U'];
        $uidarr = explode('=',$cookuid);
        $userid = $uidarr['2'];//操作人
        $banktype = 'I';
        $bankcomment = "订单　".$orderid."　收款";
        $log = "主订单收款";
        //科目部分
        $last = XDao::query("subjectbalanceQuery")->findlast($kemu);
        if($last['endingpce']){
            $qichu = $last['endingpce'];
        }else{
            $qichu = 0;
        }
        $qimo = $qichu+$shishou;
        // // 银行账号部分
        $balance = XDao::query("financebankQuery")->findbalance($zhanghao);
        $newbalance = $balance['balance']+$shishou;
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();
        // // //科目记录
        $kemujilu=SubjectbalanceSvc::ins()->addsubjectbalance($kemu,$shishou,$qichu,$qimo);
        // // //修改账号主表里的余额
        $zhanghaozhubiao = FinancebankSvc::ins()->editbalance($zhanghao,$newbalance);
        // // //账号交易记录
        $ress = BankactactionSvc::ins()->addbankactaction($zhanghao,$userid,$banktype,$bankcomment,$shishou,$newbalance);
        $result=XDao::dwriter("OrderfinanceWriter")->dopayin($orderid,$shishou,$qiankuan,$zhanghao,$ruzhang,$kemu);
        //订单操作记录
        $re=OrderlogSvc::ins()->add($orderid,$userid,$log);
        if($ress!=1 || $result!=1 || $zhanghaozhubiao!=1 || $kemujilu!=1 || $re!=1){
            $writer->rollback();
            return XNext::gotourl('/order/paymentorderoffline.php');
        }
        $writer->commit();
        return XNext::gotourl('/order/paymentorderoffline.php');
    }
}
//编辑全部订单
class Action_order_editorderquery extends XAction
{
    public function _run($request, $xcontext)
    {
    	//分类
        $orderclass = XDao::query("ordercategoryQuery")->findall();
        //店铺
        $shop = XDao::query("systemshopQuery")->findall();
        //仓库
        $store = XDao::query("StoreShowQuery")->listStoreInfo();
        //渠道
        $qudao = XDao::query("companysalesQuery")->allcompanysalesinfo();
        //快递公司
    	$kuaidi = XDao::query("ecinfoQuery")->allexp();
    	//物流公司
    	$wuliu = XDao::query("TransportinfoQuery")->findall();
        $id = $request->attr['orderid'];
        $list = XDao::query("orderinfoQuery")->findforid($id);
        $payin = XDao::query("orderfinanceQuery")->findone($id);
        $username = XDao::query("UserQuery")->userone($list['serviceid']);
        $username = $username['name'];
        $kehuname = XDao::query("CustomerInfoQuery")->find($list['customerid']);
        $kehuname = $kehuname['name'];
        $zhanghu = XDao::query("financebankQuery")->allfinancebank();
        $kemu = XDao::query("financialaccountQuery")->allfinan();
        function get_sort_by_array($arr,$parentid=0,$level=1) {
          $subs = array(); // 子孙数组
          foreach($arr as $k=>$v) {
            if($v['parent'] == $parentid) {
                $v['level'] = $level;
                $subs[] = $v;
                $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
            }
          }
          return $subs;
        }
        $catekemu = get_sort_by_array($kemu);
        if (count($catekemu)) {
            foreach($catekemu as $k=>&$v) {
                $v['name'] = str_repeat("|--", $v['level'] - 1).$v['name'];
            }
        }
        //查找商品
        $pro = XDao::query("OrderproductQuery")->findfororderid($id);
        $pg = new GetFormatStingByProductId();
        foreach($pro as $k=>$v){
            $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
            $pro[$k]['productname'] = $productname['name'];
            $pro[$k]['image'] = $productname['image'];
            $unitid = XDao::query("ListFormatQuery")->getformatbyproid($v['productid']);
            $unitid = $unitid[0]['unitid'];
            $dwname = XDao::query("ListProunitQuery")->getdwname($unitid);
            $pro[$k]['dwname'] = $dwname['name'];
            $gui = $pg->getformatstr($v['productid']);
            $pro[$k]['gui'] = $gui;
        }
        //查找印刷信息
        $print = XDao::query("orderprintquery")->findfororderid($id);
        //查找发货信息
        $deliver = XDao::query("orderdeliverquery")->findfororderid($id);
        $recode = XDao::query("orderlogQuery")->findfororderid($id);
         //查找所属订单id
        $porderid = XDao::query("orderftersonQuery")->findparent($id);
        foreach ($porderid as $key => $value) {
        	$pidarr[] = $value['porderid'];
        }

        /*订单图片*/
        $ordermsgdata = XDao::query('ListOrderMsgImgQuery')->getOrderImg($id);
        foreach ($ordermsgdata as $key => &$value) {
            $value['path'] = ProImage::IMAGEPATH;
        }

        $xcontext->ordermsgdata = $ordermsgdata; //订单图片
        $strpid = implode(",",$pidarr);
        $xcontext->list         = $list;
        $xcontext->qudao        = $qudao;
        $xcontext->orderclass   = $orderclass;
        $xcontext->shop         = $shop;
        $xcontext->store        = $store;
        $xcontext->username     = $username;
        $xcontext->kehuname     = $kehuname;
        $xcontext->kemu         = $catekemu;
        $xcontext->zhanghu      = $zhanghu;
        $xcontext->pro          = $pro;
        $xcontext->kuaidi       = $kuaidi;
        $xcontext->wuliu        = $wuliu;
        $xcontext->print        = $print;
        $xcontext->deliver      = $deliver;
        $xcontext->payin        = $payin;
        $xcontext->recode       = $recode;
        $xcontext->strpid       = $strpid;
        return XNext::useTpl("/order/editorderquery.html");
    }
}
//印刷订单编辑
class Action_order_editprint extends XAction
{
    public function _run($request, $xcontext)
    {
        //分类
        $orderclass = XDao::query("ordercategoryQuery")->findall();
        //店铺
        $shop = XDao::query("systemshopQuery")->findall();
        //仓库
        $store = XDao::query("StoreShowQuery")->listStoreInfo();
        //渠道
        $qudao = XDao::query("companysalesQuery")->allcompanysalesinfo();
        //快递公司
        $kuaidi = XDao::query("ecinfoQuery")->allexp();
        //物流公司
        $wuliu = XDao::query("TransportinfoQuery")->findall();
        $id = $request->attr['orderid'];
        $list = XDao::query("orderinfoQuery")->findforid($id);
        $payin = XDao::query("orderfinanceQuery")->findone($id);
        $username = XDao::query("UserQuery")->userone($list['serviceid']);
        $username = $username['name'];
        $kehuname = XDao::query("CustomerInfoQuery")->find($list['customerid']);
        $kehuname = $kehuname['name'];
        $zhanghu = XDao::query("financebankQuery")->allfinancebank();
        $kemu = XDao::query("financialaccountQuery")->allfinan();
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catekemu = get_sort_by_array($kemu);
        if (count($catekemu)) {
            foreach($catekemu as $k=>&$v) {
                $v['name'] = str_repeat("|--", $v['level'] - 1).$v['name'];
            }
        }
        //查找商品
        $pro = XDao::query("OrderproductQuery")->findfororderid($id);
        $pg = new GetFormatStingByProductId();
        foreach($pro as $k=>$v){
            $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
            $pro[$k]['productname'] = $productname['name'];
            $pro[$k]['image'] = $productname['image'];
            $unitid = XDao::query("ListFormatQuery")->getformatbyproid($v['productid']);
            $unitid = $unitid[0]['unitid'];
            $dwname = XDao::query("ListProunitQuery")->getdwname($unitid);
            $pro[$k]['dwname'] = $dwname['name'];
            $gui = $pg->getformatstr($v['productid']);
            $pro[$k]['gui'] = $gui;
        }
        //查找印刷信息
        $print = XDao::query("orderprintquery")->findfororderid($id);
        //查找发货信息
        $deliver = XDao::query("orderdeliverquery")->findfororderid($id);
        $recode = XDao::query("orderlogQuery")->findfororderid($id);
         //查找所属订单id
        $porderid = XDao::query("orderftersonQuery")->findparent($id);
        foreach ($porderid as $key => $value) {
            $pidarr[] = $value['porderid'];
        }
        $strpid = implode(",",$pidarr);
        $xcontext->list = $list;
        $xcontext->qudao = $qudao;
        $xcontext->orderclass = $orderclass;
        $xcontext->shop = $shop;
        $xcontext->store = $store;
        $xcontext->username = $username;
        $xcontext->kehuname = $kehuname;
        $xcontext->kemu = $catekemu;
        $xcontext->zhanghu = $zhanghu;
        $xcontext->pro = $pro;
        $xcontext->kuaidi = $kuaidi;
        $xcontext->wuliu = $wuliu;
        $xcontext->print = $print;
        $xcontext->deliver = $deliver;
        $xcontext->payin = $payin;
        $xcontext->recode = $recode;
        $xcontext->strpid = $strpid;
        return XNext::useTpl("/order/editprint.html");
    }
}
//印刷订单编辑
class Action_order_editprint1 extends XAction
{
    public function _run($request, $xcontext)
    {
        //分类
        $orderclass = XDao::query("ordercategoryQuery")->findall();
        //店铺
        $shop = XDao::query("systemshopQuery")->findall();
        //仓库
        $store = XDao::query("StoreShowQuery")->listStoreInfo();
        //渠道
        $qudao = XDao::query("companysalesQuery")->allcompanysalesinfo();
        //快递公司
        $kuaidi = XDao::query("ecinfoQuery")->allexp();
        //物流公司
        $wuliu = XDao::query("TransportinfoQuery")->findall();
        $id = $request->attr['orderid'];
        $list = XDao::query("orderinfoQuery")->findforid($id);
        $payin = XDao::query("orderfinanceQuery")->findone($id);
        $username = XDao::query("UserQuery")->userone($list['serviceid']);
        $username = $username['name'];
        $kehuname = XDao::query("CustomerInfoQuery")->find($list['customerid']);
        $kehuname = $kehuname['name'];
        $zhanghu = XDao::query("financebankQuery")->allfinancebank();
        $kemu = XDao::query("financialaccountQuery")->allfinan();
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catekemu = get_sort_by_array($kemu);
        if (count($catekemu)) {
            foreach($catekemu as $k=>&$v) {
                $v['name'] = str_repeat("|--", $v['level'] - 1).$v['name'];
            }
        }
        //查找商品
        $pro = XDao::query("OrderproductQuery")->findfororderid($id);
        $pg = new GetFormatStingByProductId();
        foreach($pro as $k=>$v){
            $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
            $pro[$k]['productname'] = $productname['name'];
            $pro[$k]['image'] = $productname['image'];
            $unitid = XDao::query("ListFormatQuery")->getformatbyproid($v['productid']);
            $unitid = $unitid[0]['unitid'];
            $dwname = XDao::query("ListProunitQuery")->getdwname($unitid);
            $pro[$k]['dwname'] = $dwname['name'];
            $gui = $pg->getformatstr($v['productid']);
            $pro[$k]['gui'] = $gui;
        }
        //查找印刷信息
        $print = XDao::query("orderprintquery")->findfororderid($id);
        //查找发货信息
        $deliver = XDao::query("orderdeliverquery")->findfororderid($id);
        $recode = XDao::query("orderlogQuery")->findfororderid($id);
         //查找所属订单id
        $porderid = XDao::query("orderftersonQuery")->findparent($id);
        foreach ($porderid as $key => $value) {
            $pidarr[] = $value['porderid'];
        }

        /*订单图片*/
        $ordermsgdata = XDao::query('ListOrderMsgImgQuery')->getOrderImg($id);
        foreach ($ordermsgdata as $key => &$value) {
            $value['path'] = ProImage::IMAGEPATH;
        }

        $xcontext->ordermsgdata = $ordermsgdata; //订单图片
        $strpid = implode(",",$pidarr);
        $xcontext->list = $list;
        $xcontext->qudao = $qudao;
        $xcontext->orderclass = $orderclass;
        $xcontext->shop = $shop;
        $xcontext->store = $store;
        $xcontext->username = $username;
        $xcontext->kehuname = $kehuname;
        $xcontext->kemu = $catekemu;
        $xcontext->zhanghu = $zhanghu;
        $xcontext->pro = $pro;
        $xcontext->kuaidi = $kuaidi;
        $xcontext->wuliu = $wuliu;
        $xcontext->print = $print;
        $xcontext->deliver = $deliver;
        $xcontext->payin = $payin;
        $xcontext->recode = $recode;
        $xcontext->strpid = $strpid;
        return XNext::useTpl("/order/editprint1.html");
    }
}
//印刷订单编辑
class Action_order_editprint2 extends XAction
{
    public function _run($request, $xcontext)
    {
        //分类
        $orderclass = XDao::query("ordercategoryQuery")->findall();
        //店铺
        $shop = XDao::query("systemshopQuery")->findall();
        //仓库
        $store = XDao::query("StoreShowQuery")->listStoreInfo();
        //渠道
        $qudao = XDao::query("companysalesQuery")->allcompanysalesinfo();
        //快递公司
        $kuaidi = XDao::query("ecinfoQuery")->allexp();
        //物流公司
        $wuliu = XDao::query("TransportinfoQuery")->findall();
        $id = $request->attr['orderid'];
        $list = XDao::query("orderinfoQuery")->findforid($id);
        $payin = XDao::query("orderfinanceQuery")->findone($id);
        $username = XDao::query("UserQuery")->userone($list['serviceid']);
        $username = $username['name'];
        $kehuname = XDao::query("CustomerInfoQuery")->find($list['customerid']);
        $kehuname = $kehuname['name'];
        $zhanghu = XDao::query("financebankQuery")->allfinancebank();
        $kemu = XDao::query("financialaccountQuery")->allfinan();
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catekemu = get_sort_by_array($kemu);
        if (count($catekemu)) {
            foreach($catekemu as $k=>&$v) {
                $v['name'] = str_repeat("|--", $v['level'] - 1).$v['name'];
            }
        }
        //查找商品
        $pro = XDao::query("OrderproductQuery")->findfororderid($id);
        $pg = new GetFormatStingByProductId();
        foreach($pro as $k=>$v){
            $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
            $pro[$k]['productname'] = $productname['name'];
            $pro[$k]['image'] = $productname['image'];
            $unitid = XDao::query("ListFormatQuery")->getformatbyproid($v['productid']);
            $unitid = $unitid[0]['unitid'];
            $dwname = XDao::query("ListProunitQuery")->getdwname($unitid);
            $pro[$k]['dwname'] = $dwname['name'];
            $gui = $pg->getformatstr($v['productid']);
            $pro[$k]['gui'] = $gui;
        }
        //查找印刷信息
        $print = XDao::query("orderprintquery")->findfororderid($id);
        //查找发货信息
        $deliver = XDao::query("orderdeliverquery")->findfororderid($id);
        $recode = XDao::query("orderlogQuery")->findfororderid($id);
         //查找所属订单id
        $porderid = XDao::query("orderftersonQuery")->findparent($id);
        foreach ($porderid as $key => $value) {
            $pidarr[] = $value['porderid'];
        }
        $strpid = implode(",",$pidarr);
        $xcontext->list = $list;
        $xcontext->qudao = $qudao;
        $xcontext->orderclass = $orderclass;
        $xcontext->shop = $shop;
        $xcontext->store = $store;
        $xcontext->username = $username;
        $xcontext->kehuname = $kehuname;
        $xcontext->kemu = $catekemu;
        $xcontext->zhanghu = $zhanghu;
        $xcontext->pro = $pro;
        $xcontext->kuaidi = $kuaidi;
        $xcontext->wuliu = $wuliu;
        $xcontext->print = $print;
        $xcontext->deliver = $deliver;
        $xcontext->payin = $payin;
        $xcontext->recode = $recode;
        $xcontext->strpid = $strpid;
        return XNext::useTpl("/order/editprint2.html");
    }
}
class Action_order_doeditprint extends XAction
{
    public function _run($request, $xcontext)
    {
        $cookuid = $_COOKIE['U'];
        $uidarr = explode('=',$cookuid);
        $userid = $uidarr['2'];//操作人
        $printcomtent = $request->attr['printcomment'];
        $printinfo = $request->attr['printinfo'];
        $orderid = $request->attr['orderid'];
        $log = "修改印刷内容";
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();
        $result = XDao::dwriter("orderprintWriter")->update($printcomtent,$printinfo,$orderid);
        $ress=OrderlogSvc::ins()->add($orderid,$userid,$log);
        if($result!=1 || $ress!=1){
            $writer->rollback();
            return XNext::gotourl('/order/printorder.php');
        }
        $writer->commit();
        return XNext::gotourl('/order/printorder.php');
    }
}