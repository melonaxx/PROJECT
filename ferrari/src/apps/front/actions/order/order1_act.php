<?php
//新增主订单
class Action_order_mainorder extends XAction
{
    public function _run($request, $xcontext)
    {	//分类
    	$orderclass = XDao::query("ordercategoryQuery")->findall();
    	//店铺
    	$shop = XDao::query("systemshopQuery")->findall();
    	//仓库
    	$store = XDao::query("StoreShowQuery")->listStoreInfo();
    	//渠道
    	$qudao = XDao::query("companysalesQuery")->allcompanysalesinfo();
    	//操作人
    	$cookuid                  = $_COOKIE['U'];
    	$uidarr                   = explode('=',$cookuid);
    	$username = XDao::query("UserQuery")->userone($uidarr['2']);
    	$username = $username['name'];
      $xcontext->username = $username;
      $xcontext->qudao = $qudao;
    	$xcontext->orderclass = $orderclass;
    	$xcontext->shop = $shop;
    	$xcontext->store = $store;
    	$xcontext->userid = $uidarr['2'];
        return XNext::useTpl("/order/mainorder.html");
    }
}

//查找商品信息
class Action_order_findproinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $pid = $request->attr['proid'];
        $ql = SundialQL::create()
          ->select("$0.image","$1.pricesell as price","$2.name as dwname")
          ->from("product")
          ->innerJoin("proinfo", "$0.productid=$1.productid")
          ->innerJoin("prounit", "$1.unitid=$2.id")
          ->where("$0.productid", "=", $pid);
        $result = $ql->query();
        echo json_encode($result);
    }
}

//模糊查找客户信息
class Action_order_findkehu extends XAction
{
    public function _run($request, $xcontext)
    {
        $name = $request->attr['name'];
        $result = XDao::query("CustomerInfoQuery")->getforname($name);
     	echo json_encode($result);
    }
}
//查找选中客户信息
class Action_order_findkehuinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['khid'];
        $result = XDao::query("CustomerInfoQuery")->find($id);
     	echo json_encode($result);
    }
}
//执行添加注定单
class Action_order_doaddmainorder extends XAction
{
    public function _run($request, $xcontext)
    {
  		$time = $request->attr['time'];
  		$onlineid = $request->attr['onlineid'];
      if(!$onlineid){
        $onlineid = time();
      }
  		$orderclass = $request->attr['orderclass'];
  		$qudao = $request->attr['qudao'];
  		$shop = $request->attr['shop'];
  		$store = $request->attr['store'];
  		$guanlian = $request->attr['guanlian'];
  		$serviceid = $request->attr['serviceid'];
  		$isreceive = $request->attr['isreceive'];
  		$status = $request->attr['status'];
  		$isbill = $request->attr['isbill'];
  		$billtype = $request->attr['billtype'];
  		$comment = $request->attr['comment'];
  		$type = 'Y';
      $deliversta = 'N';
      $desstoresta = 'B';
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
  		$log = "添加主订单";
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
  		//订单财务表
  		$res=OrderfinanceSvc::ins()->add($orderid,$pays);
  		//操作记录表
  		$ress=OrderlogSvc::ins()->add($orderid,$serviceid,$log);
  		if($res!=1 || $ress!=1){
  			$writer->rollback();
  		}
  		$writer->commit();
  		return XNext::gotourl('/order/checkmain.php');
    }
}
//审核主订单
class Action_order_checkmain extends XAction
{
    public function _run($request, $xcontext)
    {
    	$seach = strval($request->attr['seach']);
        if(strlen($seach)!=0){
            $where = "where id like '%$seach%' and type = 'Y' and orstatus = 'N' and isdelete = 'N'";
        }else{
            $where ="where type = 'Y' and orstatus = 'N' and isdelete = 'N'";
        }
        $lists = XDao::query("orderinfoQuery")->findnmain($where);
        $arr['total_rows'] = count($lists);
        $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
        $aaa = new Core_Lib_Page($arr);
        if(strlen($seach)!=0){
            $aaa->seach = "&seach=$seach";
        }
        $pages = $aaa->show(3);
        $list = XDao::query("orderinfoQuery")->findnmainpage($where,$aaa->first_row,$arr['list_rows']);
        foreach($list as $k=>$v){
        	$user = XDao::query("UserQuery")->userone($v['serviceid']);
      		$list[$k]['kefu'] = $user['name'];
      		$class = XDao::query("ordercategoryQuery")->findone($v['categoryid']);
      		$list[$k]['classname'] = $class['name'];
      		$shop = XDao::query("systemshopQuery")->findone($v['companyid']);
      		$list[$k]['shopname'] = $shop['name'];
      		$qudao = XDao::query("companysalesQuery")->findone($v['channelid']);
      		$list[$k]['qudao'] = $qudao['name'];
      		$cus = XDao::query("CustomerInfoQuery")->find($v['customerid']);
      		$list[$k]['cusname'] = $cus['name'];
      		$list[$k]['mobile'] = $cus['mobile'];
	    }
	    $xcontext->list = $list;
	    $xcontext->pages = $pages;
	    $xcontext->seach=$seach;
	    return XNext::useTpl("/order/checkmain.html");
    }
}
//审核通过主订单
class Action_order_docheckmain extends XAction
{
    public function _run($request, $xcontext)
    {
        $arrid = $request->attr['edit'];
	    $flag = 1;
	    $writer = XDao::dwriter('DWriter');
	    $writer->beginTrans();
	    foreach($arrid as $k=>$v){
	      $result=OrderinfoSvc::ins()->checkmain($v);
	      if($result!=1){
	        $writer->rollback();
	        return false;
	      }
	    }
	    $writer->commit();
	    echo $flag;
	    return XNext::nothing();
    }
}
//删除主订单
class Action_order_dodelmain extends XAction
{
    public function _run($request, $xcontext)
    {
        $arrid = $request->attr['del'];
	    $flag = 1;
	    $writer = XDao::dwriter('DWriter');
	    $writer->beginTrans();
	    foreach($arrid as $k=>$v){
	      $result=OrderinfoSvc::ins()->delmain($v);
	      if($result!=1){
	        $writer->rollback();
	        return false;
	      }
	    }
	    $writer->commit();
	    echo $flag;
	    return XNext::nothing();
    }
}
//主订单列表
class Action_order_mainlist extends XAction
{
    public function _run($request, $xcontext)
    {
    	$number = strval($request->attr['number']);
    	$radio = $request->attr['radio'];
    	$where = "where type = 'Y' and orstatus = 'P' and isdelete = 'N'";
    	$seach = "";
        if(strlen($number)!=0){
            $where .= " and id like '%".$number."%'";
            $seach .= "&number=$number";
        }
        if($radio){
        	$where .= " and billtype = '".$radio."'";
        	$seach .= "&radio=$radio";
        }
        $lists = XDao::query("orderinfoQuery")->findnmain($where);
        $arr['total_rows'] = count($lists);
        $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
        $aaa = new Core_Lib_Page($arr);
        $aaa->seach = $seach;
        $pages = $aaa->show(3);
        $list = XDao::query("orderinfoQuery")->findnmainpage($where,$aaa->first_row,$arr['list_rows']);
        foreach($list as $k=>$v){
        	$user = XDao::query("UserQuery")->userone($v['serviceid']);
      		$list[$k]['kefu'] = $user['name'];
      		$finance = XDao::query("orderfinanceQuery")->findone($v['id']);
      		$list[$k]['financestatus'] = $finance['status'];
      		$class = XDao::query("ordercategoryQuery")->findone($v['categoryid']);
      		$list[$k]['classname'] = $class['name'];
      		$shop = XDao::query("systemshopQuery")->findone($v['companyid']);
      		$list[$k]['shopname'] = $shop['name'];
      		$qudao = XDao::query("companysalesQuery")->findone($v['channelid']);
      		$list[$k]['qudao'] = $qudao['name'];
      		$cus = XDao::query("CustomerInfoQuery")->find($v['customerid']);
      		$list[$k]['cusname'] = $cus['name'];
      		$list[$k]['mobile'] = $cus['mobile'];
	    }
	    $xcontext->list = $list;
	    $xcontext->pages = $pages;
	    $xcontext->seach=$seach;
	    $xcontext->radio=$radio;
	    $xcontext->number=$number;
        return XNext::useTpl("order/mainlist.html");
    }
}
//审查主订单页面编辑
class Action_order_editmain extends XAction
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
        $id = $request->attr['id'];
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
        $xcontext->list = $list;
        $xcontext->qudao = $qudao;
        $xcontext->orderclass = $orderclass;
        $xcontext->shop = $shop;
        $xcontext->store = $store;
        $xcontext->username = $username;
        $xcontext->kehuname = $kehuname;
        $xcontext->pro = $pro;
        return XNext::useTpl("/order/editmain.html");
    }
}
//审查主订单页面编辑
class Action_order_doeditmainorder extends XAction
{
    public function _run($request, $xcontext)
    { 
        $id = $request->attr['orderid'];
        $time = $request->attr['time'];
        $onlineid = $request->attr['onlineid'];
        $orderclass = $request->attr['orderclass'];
        $qudao = $request->attr['qudao'];
        $shop = $request->attr['shop'];
        $store = $request->attr['store'];
        $guanlian = $request->attr['guanlian'];
        $serviceid = $request->attr['serviceid'];
        $isreceive = $request->attr['isreceive'];
        $status = $request->attr['status'];
        $isbill = $request->attr['isbill'];
        $billtype = $request->attr['billtype'];
        $comment = $request->attr['comment'];
        $type = 'Y';
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
        $log = "编辑主订单";
        $cookuid = $_COOKIE['U'];
        $uidarr = explode('=',$cookuid);
        $userid = $uidarr['2'];
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();
        if(!$khid){
          //客户信息
            $khid=CustomerInfoSvc::ins()->add($khname,$nick,$mobile,$telphone,$postcode,$companyname,$stateid,$cityid,$districtid,$address);
        }
        // 修改订单主表
        $orderres=OrderinfoSvc::ins()->editmain($id,$time,$onlineid,$orderclass,$qudao,$shop,$store,$guanlian,$serviceid,$isreceive,$status,$isbill,$billtype,$comment,$youhuis,$pays,$cusmsg,$khid,$type);
        //商品部分
        $pro = XDao::query("OrderproductQuery")->findfororderid($id);
        $pronum = count($pro);
        $del= XDao::dwriter("OrderproductWriter")->del($id);
        if($pronum != $del){
          $writer->rollback();
          return XNext::gotourl('/order/checkmain.php');
        }
        if($proid){
          foreach($proid as $k=>$v){
            // 订单商品
            $result=OrderproductSvc::ins()->add($id,$v,$singleprice[$k],$goodsnum[$k],$youhui[$k],$pay[$k],$procomment[$k]);
            if($result!=1){
              $writer->rollback();
              return XNext::gotourl('/order/checkmain.php');
            }
          }
        }
        // //订单财务表
        $res=XDao::dwriter("OrderfinanceWriter")->editfororderid($pays,$pays,$id);
        // //操作记录表
        $ress=OrderlogSvc::ins()->add($id,$userid,$log);
        if($res!=1 || $ress!=1){
          $writer->rollback();
          return XNext::gotourl('/order/checkmain.php');
        }
        $writer->commit();
        return XNext::gotourl('/order/checkmain.php');
    }
}
//主订单列表编辑
class Action_order_editmainlist extends XAction
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
        $id = $request->attr['id'];
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
        $recode = XDao::query("orderlogQuery")->findfororderid($id);
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
        $xcontext->payin = $payin;
        $xcontext->recode = $recode;
        return XNext::useTpl("/order/editmainlist.html");
    }
}

//显示子订单
class Action_order_childorder extends XAction
{
    public function _run($request, $xcontext)
    {   
        $id = $request->attr['id'];
        $childid = XDao::query("orderftersonQuery")->findchild($id);
        if($childid){
        foreach ($childid as $key => $value) {
          $list[] = XDao::query("orderinfoQuery")->findforids($value['orderid']);
          $arrid[] = $value['orderid'];
        }
        $strid = implode(",",$arrid);
        $pg = new GetFormatStingByProductId();
        $cpro = XDao::query("OrderproductQuery")->findchildproduct($strid);
        foreach($cpro as $kp => $vp){
            $productname = XDao::query("ListProductQuery")->findProduct($vp['productid']);
            $cpro[$kp]['productname'] = $productname['name'];
            $gui = $pg->getformatstr($vp['productid']);
            $cpro[$kp]['gui'] = $gui;
        }
        foreach($list as $k => $v){
          $orderclass = XDao::query("ordercategoryQuery")->findone($v['categoryid']);
          $list[$k]['classname'] = $orderclass['name'];
          $qudao = XDao::query("companysalesQuery")->findone($v['channelid']);
          $list[$k]['qudao'] = $qudao['name'];
          $cus = XDao::query("CustomerInfoQuery")->find($v['customerid']);
          $list[$k]['cusname'] = $cus['name'];
          $list[$k]['cusmobile'] = $cus['mobile'];
          $shop = XDao::query("systemshopQuery")->findone($v['companyid']);
          $list[$k]['shopname'] = $shop['name'];
          $user = XDao::query("UserQuery")->userone($v['serviceid']);
          $list[$k]['username'] = $user['name'];
          $finance = XDao::query("orderfinanceQuery")->findone($v['id']);
          $list[$k]['finance'] = $finance['status'];
        }
          
        }
        $xcontext->list = $list;
        $xcontext->cpro = $cpro;
        return XNext::useTpl("/order/childorder.html");
    }
}
// //主订单收款
class Action_order_dopayin extends XAction
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
          return XNext::gotourl('/order/mainlist.php');
        }
        $writer->commit();
        return XNext::gotourl('/order/mainlist.php');
    }
}
//添加售后
class Action_order_doaddshouhou extends XAction
{
    public function _run($request, $xcontext)
    {   
        $orderid = $request->attr['orderid'];
        $connect = $request->attr['connect'];
        $cookuid = $_COOKIE['U'];
        $uidarr = explode('=',$cookuid);
        $userid = $uidarr['2'];//操作人
        $re=AftersalemsgSvc::ins()->add($orderid,$userid,$connect);
        echo $re;
    }
}
//查询售后
class Action_order_showshouhou extends XAction
{
    public function _run($request, $xcontext)
    {   
        $orderid = $request->attr['orderid'];
        $result = XDao::query("aftersalemsgQuery")->findallfororderid($orderid);
        foreach($result as $k=>$v){
          $username = XDao::query("UserQuery")->userone($v['staffid']);
          $username = $username['name'];
          $result[$k]['username'] = $username;
        }
        echo json_encode($result);
    }
}
//删除售后
class Action_order_delshouhou extends XAction
{
    public function _run($request, $xcontext)
    {   
        $id = $request->attr['id'];
        $re=AftersalemsgSvc::ins()->del($id);
        echo $re;
    }
}