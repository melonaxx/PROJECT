<?php
//进入添加采购单页面
class Action_purchase_addpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $company = XDao::query("companyQuery")->allcompanyinfo();
    $store = XDao::query("StoreShowQuery")->listStoreInfo();
    $xcontext->company = $company;
    $xcontext->store = $store;
    return XNext::useTpl("/purchase/addpurchase.html");
  }
}
//执行添加采购单
class Action_purchase_doaddpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $cookuid                  = $_COOKIE['U'];
    $uidarr                   = explode('=',$cookuid);
    $data['uid']              = $uidarr['2'];//操作人
    $data['supplier']         = $request->attr['supplier'];//供应商
    $data['time']             = date('Y-m-d H:i:s',time());//下单时间
    $data['company']          = $request->attr['company'];//采购公司
    $data['store']            = $request->attr['store'];//仓库
    $data['brief']            = $request->attr['brief'];//摘要
    $data['comment']          = $request->attr['comment'];//备注
    $data['num']              = array_sum($request->attr['shuliang']);//数量
    $data['zongjia']          = array_sum($request->attr['zongjia']);//含税价
    $data['shuie']            = array_sum($request->attr['shuie']);//税额
    $data['shuijia']          = array_sum($request->attr['shuijia']);//不含税价
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    $result=PurchaseSvc::ins()->addpurchase($data);
    $shang = $request->attr['shang'];
    foreach($shang as $k=>$v){
      $product['productid']     = $v;//商品id
      $product['purchaseid']    = $result;//采购单id
      $product['storeid']       = $request->attr['store'];//到货库房
      $product['partsid']       = $request->attr['danweiid'][$k];//单位id
      $product['total']         = $request->attr['shuliang'][$k];//商品数量
      $product['totalway']      = $request->attr['shuliang'][$k];//在途数量
      $product['price']         = $request->attr['danjia'][$k];//单价
      $product['taxprice']      = $request->attr['zongjia'][$k];//含税价
      $product['shuilv']        = $request->attr['shuilv'][$k];//税率
      $product['shuie']         = $request->attr['shuie'][$k];//税额
      $product['shuijia']       = $request->attr['shuijia'][$k];//不含税价
      $res=PurchaseproductSvc::ins()->addPurchaseproduct($product);
      if($res != 1){
        $writer->rollback();
        echo 1;
      }
    }
    $bank['purchaseid']       = $result;
    $bank['paymenttotal']     = array_sum($request->attr['zongjia']);
    $bank['paymentremain']    = array_sum($request->attr['zongjia']);
    $bank['supplierid']    = $request->attr['supplier'];
    $ress=PurchasefinanceSvc::ins()->addfinance($bank);
    if($ress != 1){
      $writer->rollback();
      echo 2;
    }
    $writer->commit();
    return XNext::gotourl('/purchase/checkpurchase.php');
  }
}
//执行供应商模糊查询
class Action_purchase_findsupplier extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $comment = $request->attr['comment'];
    $result = XDao::query("purchasesupplierQuery")->likesupplier($comment);
    echo json_encode($result,JSON_UNESCAPED_UNICODE);
  }
}
//根据商品id查找单位和进价
class Action_purchase_finddanwei extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id = $request->attr['proid'];
    $result=XDao::query("ListUnitByIdQuery")->getprice($id);
    $dwid = $result['unitid'];
    $dwname = XDao::query("ListProunitQuery")->getdwname($dwid);
    $result['dwname'] = $dwname['name'];
    echo json_encode($result,JSON_UNESCAPED_UNICODE);
  }
}
//进入审核采购单页面
class Action_purchase_checkpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $name = strval($request->attr['name']);
    $userid = XDao::query("UserQuery")->nameforid($name);
    $userid = $userid['id'];
    $data = strval($request->attr['data']);
    if(strlen($userid)!=0 && strlen($data)!=0){
      $where = "where staffid like '%$userid%' and createtime like '$data%' and statusaudit in ('N','R')";
    }else{
      $where ="where statusaudit in ('N','R')";
    }
    $lists = XDao::query("PurchaseQuery")->findn($where);
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $aaa = new Core_Lib_Page($arr);
    if(strlen($userid)!=0 && strlen($data)!=0){
      $aaa->seach = "&name=$name&data=$data";
    }
    $pages = $aaa->show(3);
    $list = XDao::query("PurchaseQuery")->findpage($where,$aaa->first_row,$arr['list_rows']);
    foreach($list as $k=>$v){
      $aaa = XDao::query("companyQuery")->findname($v['purchasecompanyid']);
      $list[$k]['purchasecompanyid'] = $aaa['name'];
      $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($v['supplierid']);
      $list[$k]['supplierid'] = $bbb['name'];
      $ccc = XDao::query("StoreShowQuery")->findname($v['storeid']);
      $list[$k]['storeid'] = $ccc['name'];
      $ddd = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['staffid'] = $ddd['name'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->name=$name;
    $xcontext->data=$data;
    return XNext::useTpl("/purchase/checkpurchase.html");
  }
}
//打回修改采购单
class Action_purchase_editpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $editarr = $request->attr['edit'];
    $flag = 1;
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    foreach($editarr as $k=>$v){
      $result=PurchaseSvc::ins()->editpurchase($v);
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
//审核通过采购单
class Action_purchase_passpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $passarr = $request->attr['pass'];
    $flag = 1;
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    foreach($passarr as $k=>$v){
      $result=PurchaseSvc::ins()->passpurchase($v);
      if($result!=1){
        $writer->rollback();
        return false;
      }
      $pid = XDao::query("PurchaseproductQuery")->getsid($v);
      foreach($pid as $k1=>$v1){
        $edit=XDao::dwriter("StrProductWriter")->editProductToStr($v1['storeid'],$v1['total'],$v1['productid']);
        if($edit!=1){
          $writer->rollback();
          return false;
        }
      }
    }
    $writer->commit();
    echo $flag;
    return XNext::nothing();
  }
}
//拒绝采购单
class Action_purchase_delpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $delarr = $request->attr['del'];
    $flag = 1;
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    foreach($delarr as $k=>$v){
      $result=PurchaseSvc::ins()->delpurchase($v);
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
//查看采购单
class Action_purchase_purchaselist extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id=$request->attr['id'];
    $list = XDao::query("PurchaseQuery")->find($id);
    $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($list['supplierid']);
    $list['suppliername'] = $bbb['name'];
    $list['supplierlevel'] = $bbb['level'];
    $company = XDao::query("companyQuery")->allcompanyinfo();
    $store = XDao::query("StoreShowQuery")->listStoreInfo();
    $product = XDao::query("PurchaseproductQuery")->getsid($id);
    foreach($product as $k=>$v){
      $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
      $product[$k]['productname'] = $productname['name'];
      $dwname = XDao::query("ListProunitQuery")->getdwname($v['partsid']);
      $product[$k]['dwname'] = $dwname['name'];
      $zhiid = XDao::query("ListUnitByIdQuery")->getzhiid($v['productid']);
      if($zhiid){
        foreach ($zhiid as $k2=>$v2) {
          $zhiarr=XDao::query("getguigevalueQuery")->getguigevalues($v2);
          $zhiname[$k2]=$zhiarr['choice'];
        }
        $product[$k]['zhiname']=$zhiname;
      }
    }
    $xcontext->company = $company;
    $xcontext->store = $store;
    $xcontext->list = $list;
    $xcontext->product = $product;
    return XNext::useTpl("/purchase/purchaselist.html");
  }
}
//执行修改采购单
class Action_purchase_doeditpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id = $request->attr['id'];
    $data['supplier']         = $request->attr['supplier'];//供应商
    $data['company']          = $request->attr['company'];//采购公司
    $data['store']            = $request->attr['store'];//仓库
    $data['brief']            = $request->attr['brief'];//摘要
    $data['comment']          = $request->attr['comment'];//备注
    $data['num']              = array_sum($request->attr['shuliang']);//数量
    $data['zongjia']          = array_sum($request->attr['zongjia']);//含税价
    $data['shuie']            = array_sum($request->attr['shuie']);//税额
    $data['shuijia']          = array_sum($request->attr['shuijia']);//不含税价
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    $result=PurchaseSvc::ins()->editpur($id,$data);
    $del = XDao::dwriter("PurchaseproductWriter")->delproduct($id);
    if($del = 0){
      $writer->rollback();
      return XNext::gotourl('/purchase/purchaselist.php?id='.$id);
    }
    $shang = $request->attr['shang'];
    foreach($shang as $k=>$v){
       $product['productid']     = $v;//商品id
       $product['purchaseid']    = $id;//采购单id
       $product['storeid']       = $request->attr['store'];//到货库房
       $product['partsid']       = $request->attr['danweiid'][$k];//单位id
       $product['total']         = $request->attr['shuliang'][$k];//商品数量
       $product['totalway']      = $request->attr['shuliang'][$k];//在途数量
       $product['price']         = $request->attr['danjia'][$k];//单价
       $product['taxprice']      = $request->attr['zongjia'][$k];//含税价
       $product['shuilv']        = $request->attr['shuilv'][$k];//税率
       $product['shuie']         = $request->attr['shuie'][$k];//税额
       $product['shuijia']       = $request->attr['shuijia'][$k];//不含税价
       $res=PurchaseproductSvc::ins()->addPurchaseproduct($product);
       if($res != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/purchaselist.php?id='.$id);
      }
    }
    $paymenttotal    = array_sum($request->attr['zongjia']);
    $paymentremain    = array_sum($request->attr['zongjia']);
    $ress = XDao::dwriter("PurchasefinanceWriter")->editforpurchaseid($paymenttotal,$paymentremain,$id);
    $writer->commit();
    return XNext::gotourl('/purchase/purchaselist.php?id='.$id);
  }
}
class Action_purchase_purchaselist_change extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    return XNext::useTpl("/purchase/purchaselist_change.html");
  }
}
//待付款列表
class Action_purchase_purchasepay extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $datestart = isset($request->attr['datestart'])?$request->attr['datestart']:"";
    $dateend = isset($request->attr['dateend'])?$request->attr['dateend']:"";
    $pay = isset($request->attr['pay'])?$request->attr['pay']:'0';
    if($datestart){
        $dates = $datestart." 00:00:00";
    }else{
        $dates = "2000-01-01 00:00:00";
    }
    if($dateend){
        $datee = $dateend." 23:59:59";
    }else{
        $datee = date("Y-m-d H:i:s",time());
    }
    $qls = SundialQL::create()
        ->select("$0.*","$1.paymentremain as paymentremain","$1.status as status")
        ->from("purchase")
        ->innerJoin("purchasefinance","$0.id=$1.purchaseid")
        ->where("$1.status","!=","Y")
        ->where("$0.statusaudit","=","Y")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($pay){
      $qls->where("$1.status","=",$pay);
    }
        $qls->orderBy("$0.createtime",false);
    $lists = $qls->querys();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $arr['list_rows'] = intval($arr['list_rows']);
    $aaa = new Core_Lib_Page($arr);
    $seach = "";
    if($pay){
      $seach .= "&pay=$pay";
    }
    $seach .="&datestart=$datestart";
    $seach .="&dateend=$dateend";
    $aaa->seach = $seach;
    $pages = $aaa->show(3);
    $ql = SundialQL::create()
        ->select("$0.*","$1.paymentremain as paymentremain","$1.status as status")
        ->from("purchase")
        ->innerJoin("purchasefinance","$0.id=$1.purchaseid")
        ->where("$1.status","!=","Y")
        ->where("$0.statusaudit","=","Y")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($pay){
      $ql->where("$1.status","=",$pay);
    }
    $ql->orderBy("$0.createtime",false);
    $ql->limit($aaa->first_row,$arr['list_rows']);
    $list = $ql->querys();
    foreach($list as $k=>$v){
      $aaa = XDao::query("companyQuery")->findname($v['purchasecompanyid']);
      $list[$k]['purchasecompanyid'] = $aaa['name'];
      $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($v['supplierid']);
      $list[$k]['supplierid'] = $bbb['name'];
      $ccc = XDao::query("StoreShowQuery")->findname($v['storeid']);
      $list[$k]['storeid'] = $ccc['name'];
      $ddd = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['staffid'] = $ddd['name'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->datestart = $datestart;
    $xcontext->dateend = $dateend;
    $xcontext->pay = $pay;
    $xcontext->seach = $seach;
    return XNext::useTpl("/purchase/purchasepay.html");
  }
}
//执行付款
class Action_purchase_dopayfor extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    //财务科目部分
    $kemuid = $request->attr['kemu'];
    $bankid = $request->attr['zhanghao'];
    $money = $request->attr['money'];
    $comment = $request->attr['comment'];
    $cookuid = $_COOKIE['U'];
    $uidarr = explode('=',$cookuid);
    $userid = $uidarr['2'];//操作人
    $supplierid = $request->attr['supplierid'];
    $number = XDao::query("PurchaseQuery")->find($supplierid);
    $number = $number['number'];
    $type = 'Output';
    $banktype = 'D';
    $bankcomment = "采购单　".$number."　付款";
    $last = XDao::query("subjectbalanceQuery")->findlast($kemuid);
    if($last['endingpce']){
      $qichu = $last['endingpce'];
    }else{
      $qichu = 0;
    }
    $qimo = $qichu-$money;
    // 银行账号部分
    $balance = XDao::query("financebankQuery")->findbalance($bankid);
    $newbalance = $balance['balance']-$money;
    //采购资金流水记录
    $purchaseinfo = XDao::query("PurchasefinanceQuery")->findall($supplierid);
    $yingfu = $purchaseinfo['paymenttotal'];
    $yifu = $purchaseinfo['paymentalready'];
    $yifu = $yifu+$money;
    $qiankuan = $yingfu-$yifu;
    if($qiankuan>0){
      $qiankuan = $qiankuan;
    }else{
      $qiankuan = 0;
    }
    $gysqiankuan = $yifu-$yingfu;
    if($gysqiankuan>0){
      $gysqiankuan = $gysqiankuan;
    }else{
      $gysqiankuan = 0;
    }
    if($yifu==0){
      $status = 'N';
    }else if($qiankuan > 0){
      $status = 'D';
    }else if($qiankuan == 0){
      $status = 'Y';
    }
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    //科目记录
    $result=SubjectbalanceSvc::ins()->addsubjectbalance($kemuid,$money,$qichu,$qimo);
    //修改账号主表里的余额
    $res = FinancebankSvc::ins()->editbalance($bankid,$newbalance);
    //账号交易记录
    $ress = BankactactionSvc::ins()->addbankactaction($bankid,$userid,$banktype,$bankcomment,$money,$newbalance);
    $resss = MoneyrecodeSvc::ins()->addrecode($supplierid,$userid,$kemuid,$comment,$bankid,$type,$money,$yifu,$qiankuan,$gysqiankuan);
    $resu = XDao::dwriter("PurchasefinanceWriter")->payfor($status,$yifu,$qiankuan,$gysqiankuan,$supplierid);
    if($res!=1||$ress!=1||$resss!=1||$resu!=1||$result!=1){
      $writer->rollback();
      return XNext::gotourl('/purchase/purchasepay.php');
    }
    $writer->commit();
    return XNext::gotourl('/purchase/purchasepay.php');
  }
}
//执行收款
class Action_purchase_dopayin extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    // var_dump($_POST);
    // 财务科目部分
    $kemuid = $request->attr['kemu'];
    $bankid = $request->attr['zhanghao'];
    $money = $request->attr['money'];
    $comment = $request->attr['comment'];
    $cookuid = $_COOKIE['U'];
    $uidarr = explode('=',$cookuid);
    $userid = $uidarr['2'];//操作人
    $supplierid = $request->attr['supplierid'];
    $number = XDao::query("PurchaseQuery")->find($supplierid);
    $number = $number['number'];
    $type = 'Input';
    $banktype = 'I';
    $bankcomment = "采购单　".$number."　收款";
    $last = XDao::query("subjectbalanceQuery")->findlast($kemuid);
    if($last['endingpce']){
      $qichu = $last['endingpce'];
    }else{
      $qichu = 0;
    }
    $qimo = $qichu+$money;
    // // 银行账号部分
    $balance = XDao::query("financebankQuery")->findbalance($bankid);
    $newbalance = $balance['balance']+$money;
    // //采购资金流水记录
    $purchaseinfo = XDao::query("PurchasefinanceQuery")->findall($supplierid);
    $yingfu = $purchaseinfo['paymenttotal'];
    $yifu = $purchaseinfo['paymentalready'];
    $gysqiankuan = $purchaseinfo['paymentreturn'];
    $qiankuan = $purchaseinfo['paymentremain'];
    if($qiankuan>0){
      $qiankuan = $qiankuan;
    }else{
      $qiankuan = 0;
    }
    $gysqiankuan = $gysqiankuan-$money;
    if($gysqiankuan>0){
      $gysqiankuan = $gysqiankuan;
    }else{
      $gysqiankuan = 0;
    }
    if($yifu==0){
      $status = 'N';
    }else if($qiankuan > 0){
      $status = 'D';
    }else if($qiankuan == 0){
      $status = 'Y';
    }
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    // // //科目记录
    $result=SubjectbalanceSvc::ins()->addsubjectbalance($kemuid,$money,$qichu,$qimo);
    // // //修改账号主表里的余额
    $res = FinancebankSvc::ins()->editbalance($bankid,$newbalance);
    // // //账号交易记录
    $ress = BankactactionSvc::ins()->addbankactaction($bankid,$userid,$banktype,$bankcomment,$money,$newbalance);
    $resss = MoneyrecodeSvc::ins()->addrecode($supplierid,$userid,$kemuid,$comment,$bankid,$type,$money,$yifu,$qiankuan,$gysqiankuan);
    $resu = XDao::dwriter("PurchasefinanceWriter")->payfor($status,$yifu,$qiankuan,$gysqiankuan,$supplierid);
    if($res!=1||$ress!=1||$resss!=1||$resu!=1||$result!=1){
      $writer->rollback();
      return XNext::gotourl('/purchase/pendingpayment.php');
    }
    $writer->commit();
    return XNext::gotourl('/purchase/pendingpayment.php');
  }
}
/*采购运费页面*/
class Action_purchase_purexpense extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $bank = XDao::query("financebankQuery")->allfinancebank();
    $xcontext->bank = $bank;
    return XNext::useTpl("/purchase/purexpense.html");
  }
}
//执行添加采购运费
class Action_purchase_doaddyunfei extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $pnumber = $request->attr['purchasenumber'];
    $cookuid  = $_COOKIE['U'];
    $uidarr  = explode('=',$cookuid);
    $userid  = $uidarr['2'];
    $time = $request->attr['time'];
    $hanshui = $request->attr['hanshui'];
    $shuilv = $request->attr['shuilv'];
    $shuie = $request->attr['shuie'];
    $noshui = $request->attr['noshui'];
    $comment = $request->attr['comment'];
    $company = $request->attr['company'];
    $waynumber = $request->attr['waynumber'];
    $bankid = $request->attr['bankid'];
    $banktype = 'D';
    $bankcomment = "采购单　".$pnumber."　采购运费";
    $balance = XDao::query("financebankQuery")->findbalance($bankid);
    $newbalance = $balance['balance']-$hanshui;
    $jkemuid = $request->attr['jkemuid'];
    $dkemuid = $request->attr['dkemuid'];
    $jkemujine = $request->attr['jkemujine'];
    $dkemujine = $request->attr['dkemujine'];
    $type = 'F';
    $jenum = 'B';
    $denum = 'I';
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    $result = PurchasefreightinfoSvc::ins()->add($pnumber,$time,$hanshui,$shuilv,$shuie,$noshui,$comment,$company,$waynumber,$bankid,$userid);
    //修改账号主表里的余额
    $resbank = FinancebankSvc::ins()->editbalance($bankid,$newbalance);
    //账号交易记录
    $ressbank = BankactactionSvc::ins()->addbankactaction($bankid,$userid,$banktype,$bankcomment,$hanshui,$newbalance);
    if($resbank !=1 || $ressbank != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/expenserecord.php');
      }
    foreach($jkemuid as $k=>$v){
      $last = XDao::query("subjectbalanceQuery")->findlast($v);
      if($last['endingpce']){
        $qichu = $last['endingpce'];
      }else{
        $qichu = 0;
      }
      $qimo = $qichu+$jkemujine[$k];
      $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v,$jkemujine[$k],$qichu,$qimo);
      $res=FreinvoicefinrelatedSvc::ins()->add($type,$result,$v,$jkemujine[$k],$jenum);

      if($res !=1 || $recode != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/expenserecord.php');
      }
    }
    foreach($dkemuid as $k1=>$v1){
      $lasts = XDao::query("subjectbalanceQuery")->findlast($v1);
      if($lasts['endingpce']){
        $qichu1 = $lasts['endingpce'];
      }else{
        $qichu1 = 0;
      }
      $qimo1 = $qichu1-$dkemujine[$k1];
      $recodes=SubjectbalanceSvc::ins()->addsubjectbalance($v1,$dkemujine[$k1],$qichu1,$qimo1);
      $ress=FreinvoicefinrelatedSvc::ins()->add($type,$result,$v1,$dkemujine[$k1],$denum);
      if($ress !=1 || $recodes != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/expenserecord.php');
      }
    }
    $writer->commit();
    return XNext::gotourl('/purchase/expenserecord.php');
  }
}
/*运费记录页面*/
class Action_purchase_expenserecord extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $lists = XDao::query("PurchasefreightinfoQuery")->findall();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $aaa = new Core_Lib_Page($arr);
    $pages = $aaa->show(3);
    $list = XDao::query("PurchasefreightinfoQuery")->findallpage($aaa->first_row,$arr['list_rows']);
    foreach($list as $k=>$v){
      $bankname = XDao::query("financebankQuery")->findname($v['bankid']);
      $list[$k]['bankname'] = $bankname['name'];
      $JJ = array();
      $DD = array();
      $username = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['username'] = $username['name'];
      $J = XDao::query("FreinvoicefinrelatedQuery")->findb($v['id']);
      $D = XDao::query("FreinvoicefinrelatedQuery")->findi($v['id']);
      if($J){
        foreach ($J as $k1 => $v1) {
          $jname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
          $J[$k1]['kemuname'] = $jname['name'];
          $JJ[] = $J[$k1]['kemuname']."(".$v1['price'].")";
        }
      }
      if($D){
        foreach ($D as $k2 => $v2) {
          $dname = XDao::query("financialaccountQuery")->findname($v2['faccountid']);
          $D[$k2]['kemuname'] = $dname['name'];
          $DD[] = $D[$k2]['kemuname']."(".$v2['price'].")";
        }
      }
      $JJ = implode(",",$JJ);
      $list[$k]['J'] = $JJ;
      $DD = implode(",",$DD);
      $list[$k]['D'] = $DD;
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    return XNext::useTpl("/purchase/expenserecord.html");
  }
}
//收款列表
class Action_purchase_pendingpayment extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $supplier = XDao::query("purchasesupplierQuery")->findallname();
    $xcontext->supplier = $supplier;
    $datestart = isset($request->attr['datestart'])?$request->attr['datestart']:"";
    $dateend = isset($request->attr['dateend'])?$request->attr['dateend']:"";
    $supp = isset($request->attr['supp'])?$request->attr['supp']:'0';
    if($datestart){
        $dates = $datestart." 00:00:00";
    }else{
        $dates = "2000-01-01 00:00:00";
    }
    if($dateend){
        $datee = $dateend." 23:59:59";
    }else{
        $datee = date("Y-m-d H:i:s",time());
    }
    $qls = SundialQL::create()
        ->select("$0.*","$1.paymentreturn")
        ->from("purchase")
        ->innerJoin("purchasefinance","$0.id=$1.purchaseid")
        ->where("$1.paymentreturn",">","0")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($supp){
      $qls->where("$0.supplierid","=",$supp);
    }
    $lists = $qls->querys();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $arr['list_rows'] = intval($arr['list_rows']);
    $aaa = new Core_Lib_Page($arr);
    $seach = "";
    if($supp){
      $seach .= "&supp=$supp";
    }
    $seach .="&datestart=$datestart";
    $seach .="&dateend=$dateend";
    $aaa->seach = $seach;
    $pages = $aaa->show(3);
    $ql = SundialQL::create()
        ->select("$0.*","$1.paymentreturn")
        ->from("purchase")
        ->innerJoin("purchasefinance","$0.id=$1.purchaseid")
        ->where("$1.paymentreturn",">","0")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($supp){
      $ql->where("$0.supplierid","=",$supp);
    }
    $ql->limit($aaa->first_row,$arr['list_rows']);
    $list = $ql->querys();
    foreach($list as $k=>$v){
      $aaa = XDao::query("companyQuery")->findname($v['purchasecompanyid']);
      $list[$k]['companyname'] = $aaa['name'];
      $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($v['supplierid']);
      $list[$k]['suppliername'] = $bbb['name'];
      $list[$k]['supplierlevel'] = $bbb['level'];
      $ccc = XDao::query("StoreShowQuery")->findname($v['storeid']);
      $list[$k]['storename'] = $ccc['name'];
      $list[$k]['storetype'] = $ccc['storetype'];
      $ddd = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['username'] = $ddd['name'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->datestart = $datestart;
    $xcontext->dateend = $dateend;
    $xcontext->gys = $supp;
    $xcontext->seach = $seach;
    return XNext::useTpl("/purchase/pendingpayment.html");
  }
}
//添加收款
class Action_purchase_addmoney extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $number = $request->attr['purchaseid'];
    $money = $request->attr['money'];
    $list = XDao::dwriter("PurchaseWriter")->editreturnmoney($money,$number);
    return XNext::gotourl('/purchase/pendingpayment.php');

  }
}
class Action_purchase_listofdocument extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    // $list = XDao::query("moneyrecodeQuery")->findall();
    $datestart = isset($request->attr['datestart'])?$request->attr['datestart']:"";
    $dateend = isset($request->attr['dateend'])?$request->attr['dateend']:"";
    $tui = isset($request->attr['tui'])?$request->attr['tui']:'0';
    if($datestart){
        $dates = $datestart." 00:00:00";
    }else{
        $dates = "2000-01-01 00:00:00";
    }
    if($dateend){
        $datee = $dateend." 23:59:59";
    }else{
        $datee = date("Y-m-d H:i:s",time());
    }
    $qls = SundialQL::create()
        ->select()
        ->from("moneyrecode")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($tui){
      $qls->where("$0.type","=",$tui);
    }
    $lists = $qls->querys();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $arr['list_rows'] = intval($arr['list_rows']);
    $aaa = new Core_Lib_Page($arr);
    $seach = "";
    if($tui){
      $seach .= "&tui=$tui";
    }
    $seach .="&datestart=$datestart";
    $seach .="&dateend=$dateend";
    $aaa->seach = $seach;
    $pages = $aaa->show(3);
    $ql = SundialQL::create()
        ->select()
        ->from("moneyrecode")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($tui){
      $ql->where("$0.type","=",$tui);
    }
    $ql->limit($aaa->first_row,$arr['list_rows']);
    $list = $ql->querys();
    foreach($list as $k=>$v){
      $bankname = XDao::query("financebankQuery")->findname($v['bankid']);
      $purchasenumber = XDao::query("PurchaseQuery")->find($v['infoid']);
      $username = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['bankname'] = $bankname['name'];
      $list[$k]['purchasenumber'] = $purchasenumber['number'];
      $list[$k]['username'] = $username['name'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->datestart = $datestart;
    $xcontext->dateend = $dateend;
    $xcontext->tui = $tui;
    $xcontext->seach = $seach;
    return XNext::useTpl("/purchase/listofdocument.html");
  }
}

class Action_purchase_purenter extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $datestart = isset($request->attr['datestart'])?$request->attr['datestart']:"";
    $dateend = isset($request->attr['dateend'])?$request->attr['dateend']:"";
    $shou = isset($request->attr['shou'])?$request->attr['shou']:'0';
    if($datestart){
        $dates = $datestart." 00:00:00";
    }else{
        $dates = "2000-01-01 00:00:00";
    }
    if($dateend){
        $datee = $dateend." 23:59:59";
    }else{
        $datee = date("Y-m-d H:i:s",time());
    }
    $qls = SundialQL::create()
        ->select()
        ->from("purchase")
        ->where("$0.statusaudit","=","Y")
        ->where("$0.statusreceipt","!=","Y")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($shou){
      $qls->where("$0.statusreceipt","=",$shou);
    }
    $lists = $qls->querys();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $arr['list_rows'] = intval($arr['list_rows']);
    $aaa = new Core_Lib_Page($arr);
    $seach = "";
    if($shou){
      $seach .= "&shou=$shou";
    }
    $seach .="&datestart=$datestart";
    $seach .="&dateend=$dateend";
    $aaa->seach = $seach;
    $pages = $aaa->show(3);
    $ql = SundialQL::create()
        ->select()
        ->from("purchase")
        ->where("$0.statusaudit","=","Y")
        ->where("$0.statusreceipt","!=","Y")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($shou){
      $ql->where("$0.statusreceipt","=",$shou);
    }
    $ql->limit($aaa->first_row,$arr['list_rows']);
    $list = $ql->querys();
    foreach($list as $k=>$v){
      $aaa = XDao::query("companyQuery")->findname($v['purchasecompanyid']);
      $list[$k]['purchasecompanyid'] = $aaa['name'];
      $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($v['supplierid']);
      $list[$k]['supplierid'] = $bbb['name'];
      $list[$k]['level'] = $bbb['level'];
      $ccc = XDao::query("StoreShowQuery")->findname($v['storeid']);
      $list[$k]['storeid'] = $ccc['name'];
      $list[$k]['storetype'] = $ccc['storetype'];
      $ddd = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['staffid'] = $ddd['name'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->datestart = $datestart;
    $xcontext->dateend = $dateend;
    $xcontext->shou = $shou;
    $xcontext->seach = $seach;
    return XNext::useTpl("/purchase/purenter.html");
  }
}
class Action_purchase_returnlibrary extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $datestart = isset($request->attr['datestart'])?$request->attr['datestart']:"";
    $dateend = isset($request->attr['dateend'])?$request->attr['dateend']:"";
    $tui = isset($request->attr['tui'])?$request->attr['tui']:'0';
    if($datestart){
        $dates = $datestart." 00:00:00";
    }else{
        $dates = "2000-01-01 00:00:00";
    }
    if($dateend){
        $datee = $dateend." 23:59:59";
    }else{
        $datee = date("Y-m-d H:i:s",time());
    }
    $qls = SundialQL::create()
        ->select()
        ->from("purchase")
        ->where("$0.statusaudit","=","Y")
        ->where("$0.statusreceipt","!=","N")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($tui){
      $qls->where("$0.statusreceipt","=",$tui);
    }
    $lists = $qls->querys();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $arr['list_rows'] = intval($arr['list_rows']);
    $aaa = new Core_Lib_Page($arr);
    $seach = "";
    if($tui){
      $seach .= "&tui=$tui";
    }
    $seach .="&datestart=$datestart";
    $seach .="&dateend=$dateend";
    $aaa->seach = $seach;
    $pages = $aaa->show(3);
    $ql = SundialQL::create()
        ->select()
        ->from("purchase")
        ->where("$0.statusaudit","=","Y")
        ->where("$0.statusreceipt","!=","N")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($tui){
      $ql->where("$0.statusreceipt","=",$tui);
    }
    $ql->limit($aaa->first_row,$arr['list_rows']);
    $list = $ql->querys();
    foreach($list as $k=>$v){
      $aaa = XDao::query("companyQuery")->findname($v['purchasecompanyid']);
      $list[$k]['companyname'] = $aaa['name'];
      $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($v['supplierid']);
      $list[$k]['supplierid'] = $bbb['name'];
      $list[$k]['level'] = $bbb['level'];
      $ccc = XDao::query("StoreShowQuery")->findname($v['storeid']);
      $list[$k]['storeid'] = $ccc['name'];
      $list[$k]['storetype'] = $ccc['storetype'];
      $ddd = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['staffid'] = $ddd['name'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->datestart = $datestart;
    $xcontext->dateend = $dateend;
    $xcontext->tui = $tui;
    $xcontext->seach = $seach;
    return XNext::useTpl("/purchase/returnlibrary.html");
  }
}
class Action_purchase_inoroutlist extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $datestart = isset($request->attr['datestart'])?$request->attr['datestart']:"";
    $dateend = isset($request->attr['dateend'])?$request->attr['dateend']:"";
    $tui = isset($request->attr['tui'])?$request->attr['tui']:'0';
    if($datestart){
        $dates = $datestart." 00:00:00";
    }else{
        $dates = "2000-01-01 00:00:00";
    }
    if($dateend){
        $datee = $dateend." 23:59:59";
    }else{
        $datee = date("Y-m-d H:i:s",time());
    }
    $qls = SundialQL::create()
        ->select()
        ->from("purchaseoibill")
        ->where("$0.isdelete","=","N")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($tui){
      $qls->where("$0.storetype","=",$tui);
    }
    $lists = $qls->querys();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $arr['list_rows'] = intval($arr['list_rows']);
    $aaa = new Core_Lib_Page($arr);
    $seach = "";
    if($tui){
      $seach .= "&tui=$tui";
    }
    $seach .="&datestart=$datestart";
    $seach .="&dateend=$dateend";
    $aaa->seach = $seach;
    $pages = $aaa->show(3);
    $ql = SundialQL::create()
        ->select()
        ->from("purchaseoibill")
        ->where("$0.isdelete","=","N")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($tui){
      $ql->where("$0.storetype","=",$tui);
    }
    $ql->limit($aaa->first_row,$arr['list_rows']);
    $list = $ql->querys();
    foreach($list as $k=>$v){
      $aaa = XDao::query("companyQuery")->findname($v['companyid']);
      $list[$k]['companyname'] = $aaa['name'];
      $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($v['supplierid']);
      $list[$k]['supplierid'] = $bbb['name'];
      $list[$k]['level'] = $bbb['level'];
      $ccc = XDao::query("StoreShowQuery")->findname($v['storeid']);
      $list[$k]['storeid'] = $ccc['name'];
      $list[$k]['storety'] = $ccc['storetype'];
      $ddd = XDao::query("UserQuery")->userone($v['userid']);
      $list[$k]['username'] = $ddd['name'];
      $eee = XDao::query("PurchaseQuery")->find($v['purchaseid']);
      $list[$k]['number'] = $eee['number'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->datestart = $datestart;
    $xcontext->dateend = $dateend;
    $xcontext->tui = $tui;
    $xcontext->seach = $seach;
    return XNext::useTpl("/purchase/inoroutlist.html");
  }
}

/*采购票据页面*/
class Action_purchase_purbill extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    return XNext::useTpl("/purchase/purbill.html");
  }
}
//ajax查询所有财务科目
class Action_purchase_findallkemu extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $list = XDao::query("financialaccountQuery")->allfinan();

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
    $catelist = get_sort_by_array($list);
    if (count($catelist)) {
        foreach($catelist as $k=>&$v) {
            $v['name'] = str_repeat("　", $v['level'] - 1).$v['name'];
        }
    }

    echo json_encode($catelist,JSON_UNESCAPED_UNICODE);
  }
}
//ajax验证采购单编码是否正确
class Action_purchase_checknumber extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $number = $request->attr['number'];
    $numarr = explode(",",$number);
    foreach($numarr as $k=>$v){
      $result = XDao::query("PurchaseQuery")->findnum($v);
      if($result['count']!=1){
        echo "no";
      }
    }
    echo "ok";
  }
}
//ajax验证采购单编码是否正确
class Action_purchase_checkwaynumber extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $number = $request->attr['number'];
    $numarr = explode(",",$number);
    foreach($numarr as $k=>$v){
      $result = XDao::query("PurchasefreightinfoQuery")->findnum($v);
      if($result['count']!=1){
        echo "no";
      }
    }
    echo "ok";
  }
}
// 执行添加票据
class Action_purchase_dopurbill extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $number = $request->attr['purchasenumber'];
    $cookuid  = $_COOKIE['U'];
    $uidarr  = explode('=',$cookuid);
    $userid  = $uidarr['2'];
    $time = $request->attr['time'];
    $type = $request->attr['radio'];
    $hanshui = $request->attr['hanshui'];
    $shuilv = $request->attr['shuilv'];
    $shuie = $request->attr['shuie'];
    $noshui = $request->attr['noshui'];
    $comment = $request->attr['comment'];
    $jkemuid = $request->attr['jkemuid'];
    $dkemuid = $request->attr['dkemuid'];
    $jkemujine = $request->attr['jkemujine'];
    $dkemujine = $request->attr['dkemujine'];
    $jenum = 'B';
    $denum = 'I';
    $bankid = 0;
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    $result=PurchasebillmainSvc::ins()->add($time,$userid,$type,$number,$hanshui,$shuilv,$shuie,$noshui,$comment,$bankid);
    foreach($jkemuid as $k=>$v){
      $last = XDao::query("subjectbalanceQuery")->findlast($v);
      if($last['endingpce']){
        $qichu = $last['endingpce'];
      }else{
        $qichu = 0;
      }
      $qimo = $qichu+$jkemujine[$k];
      $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v,$jkemujine[$k],$qichu,$qimo);
      $res=PurchasebillattachSvc::ins()->add($result,$v,$jkemujine[$k],$jenum);
      if($res !=1 || $recode != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/purbillrecord.php');
      }
    }
    foreach($dkemuid as $k1=>$v1){
      $lasts = XDao::query("subjectbalanceQuery")->findlast($v1);
      if($lasts['endingpce']){
        $qichu1 = $lasts['endingpce'];
      }else{
        $qichu1 = 0;
      }
      $qimo1 = $qichu1-$dkemujine[$k1];
      $recodes=SubjectbalanceSvc::ins()->addsubjectbalance($v1,$dkemujine[$k1],$qichu1,$qimo1);
      $ress=PurchasebillattachSvc::ins()->add($result,$v1,$dkemujine[$k1],$denum);
      if($ress !=1 || $recodes != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/purbillrecord.php');
      }
    }
    $writer->commit();
    return XNext::gotourl('/purchase/purbillrecord.php');
  }
}
/*采购票据记录页面*/
class Action_purchase_purbillrecord extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $lists = XDao::query("PurchasebillmainQuery")->findall();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $aaa = new Core_Lib_Page($arr);
    $pages = $aaa->show(3);
    $list = XDao::query("PurchasebillmainQuery")->findallpage($aaa->first_row,$arr['list_rows']);
    foreach($list as $k=>$v){
      $JJ = array();
      $DD = array();
      $username = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['username'] = $username['name'];
      $J = XDao::query("PurchasebillattachQuery")->findb($v['id']);
      $D = XDao::query("PurchasebillattachQuery")->findi($v['id']);
      if($J){
        foreach ($J as $k1 => $v1) {
          $jname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
          $J[$k1]['kemuname'] = $jname['name'];
          $JJ[] = $J[$k1]['kemuname']."(".$v1['price'].")";
        }
      }
      if($D){
        foreach ($D as $k2 => $v2) {
          $dname = XDao::query("financialaccountQuery")->findname($v2['faccountid']);
          $D[$k2]['kemuname'] = $dname['name'];
          $DD[] = $D[$k2]['kemuname']."(".$v2['price'].")";
        }
      }
      $JJ = implode(",",$JJ);
      $list[$k]['J'] = $JJ;
      $DD = implode(",",$DD);
      $list[$k]['D'] = $DD;
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    return XNext::useTpl("/purchase/purbillrecord.html");
  }
}
//查看票据详情
class Action_purchase_piaoxq extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $uid = $request->attr['uid'];
    $number = XDao::query("PurchasebillmainQuery")->findpurchaseid($uid);
    $numarr = explode(",",$number['purchaseid']);
    $purchase = array();
    foreach($numarr as $k=>$v){
      $purchase[] = XDao::query("PurchaseQuery")->findnuminfo($v);
    }
    echo json_encode($purchase);
  }
}
//运费开票
class Action_purchase_openbill extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    return XNext::useTpl("/purchase/openbill.html");
  }
}
//执行运费开票
class Action_purchase_doaddopenbill extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $cookuid  = $_COOKIE['U'];
    $uidarr  = explode('=',$cookuid);
    $userid  = $uidarr['2'];
    $number = $request->attr['number'];
    $time = $request->attr['time'];
    $hanshui = $request->attr['hanshui'];
    $shuilv = $request->attr['shuilv'];
    $shuie = $request->attr['shuie'];
    $noshui = $request->attr['noshui'];
    $comment = $request->attr['comment'];
    $jkemuid = $request->attr['jkemuid'];
    $dkemuid = $request->attr['dkemuid'];
    $jkemujine = $request->attr['jkemujine'];
    $dkemujine = $request->attr['dkemujine'];
    $type = 'P';
    $jenum = 'B';
    $denum = 'I';
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    $result=PurchasefreinvoiceinfoSvc::ins()->add($time,$userid,$number,$comment,$hanshui,$shuilv,$shuie,$noshui);
    foreach($jkemuid as $k=>$v){
      $last = XDao::query("subjectbalanceQuery")->findlast($v);
      if($last['endingpce']){
        $qichu = $last['endingpce'];
      }else{
        $qichu = 0;
      }
      $qimo = $qichu+$jkemujine[$k];
      $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v,$jkemujine[$k],$qichu,$qimo);
      $res=FreinvoicefinrelatedSvc::ins()->add($type,$result,$v,$jkemujine[$k],$jenum);

      if($res !=1 || $recode != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/openbillrecord.php');
      }
    }
    foreach($dkemuid as $k1=>$v1){
      $lasts = XDao::query("subjectbalanceQuery")->findlast($v1);
      if($lasts['endingpce']){
        $qichu1 = $lasts['endingpce'];
      }else{
        $qichu1 = 0;
      }
      $qimo1 = $qichu1-$dkemujine[$k1];
      $recodes=SubjectbalanceSvc::ins()->addsubjectbalance($v1,$dkemujine[$k1],$qichu1,$qimo1);
      $ress=FreinvoicefinrelatedSvc::ins()->add($type,$result,$v1,$dkemujine[$k1],$denum);
      if($ress !=1 || $recodes != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/openbillrecord.php');
      }
    }
    $writer->commit();
    return XNext::gotourl('/purchase/openbillrecord.php');
  }
}
//运费开票记录
class Action_purchase_openbillrecord extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $lists = XDao::query("PurchasefreinvoiceinfoQuery")->findall();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $aaa = new Core_Lib_Page($arr);
    $pages = $aaa->show(3);
    $list = XDao::query("PurchasefreinvoiceinfoQuery")->findallpage($aaa->first_row,$arr['list_rows']);
    foreach($list as $k=>$v){
      $bankname = XDao::query("financebankQuery")->findname($v['bankid']);
      $list[$k]['bankname'] = $bankname['name'];
      $JJ = array();
      $DD = array();
      $username = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['username'] = $username['name'];
      $J = XDao::query("FreinvoicefinrelatedQuery")->findb($v['id']);
      $D = XDao::query("FreinvoicefinrelatedQuery")->findi($v['id']);
      if($J){
        foreach ($J as $k1 => $v1) {
          $jname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
          $J[$k1]['kemuname'] = $jname['name'];
          $JJ[] = $J[$k1]['kemuname']."(".$v1['price'].")";
        }
      }
      if($D){
        foreach ($D as $k2 => $v2) {
          $dname = XDao::query("financialaccountQuery")->findname($v2['faccountid']);
          $D[$k2]['kemuname'] = $dname['name'];
          $DD[] = $D[$k2]['kemuname']."(".$v2['price'].")";
        }
      }
      $JJ = implode(",",$JJ);
      $list[$k]['J'] = $JJ;
      $DD = implode(",",$DD);
      $list[$k]['D'] = $DD;
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    return XNext::useTpl("/purchase/openbillrecord.html");
  }
}
/*补交税点页面*/
class Action_purchase_taxpoint extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $bank = XDao::query("financebankQuery")->allfinancebank();
    $xcontext->bank = $bank;
    return XNext::useTpl("/purchase/taxpoint.html");
  }
}
// 执行添加补交税点
class Action_purchase_dotaxpoint extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    // var_dump($_POST);
    $number = $request->attr['purchasenumber'];
    $cookuid  = $_COOKIE['U'];
    $uidarr  = explode('=',$cookuid);
    $userid  = $uidarr['2'];
    $time = $request->attr['time'];
    $type = $request->attr['radio'];
    $hanshui = $request->attr['hanshui'];
    $shuilv = $request->attr['shuilv'];
    $shuie = $request->attr['shuie'];
    $noshui = $request->attr['noshui'];
    $comment = $request->attr['comment'];
    $jkemuid = $request->attr['jkemuid'];
    $dkemuid = $request->attr['dkemuid'];
    $jkemujine = $request->attr['jkemujine'];
    $dkemujine = $request->attr['dkemujine'];
    $jenum = 'B';
    $denum = 'I';
    $bankid = $request->attr['bankid'];
    $banktype = 'D';
    $bankcomment = "采购单　".$number."　补交税点";
    $balance = XDao::query("financebankQuery")->findbalance($bankid);
    $newbalance = $balance['balance']-$hanshui;
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    $result=PurchasebillmainSvc::ins()->add($time,$userid,$type,$number,$hanshui,$shuilv,$shuie,$noshui,$comment,$bankid);
    //修改账号主表里的余额
    $resbank = FinancebankSvc::ins()->editbalance($bankid,$newbalance);
    //账号交易记录
    $ressbank = BankactactionSvc::ins()->addbankactaction($bankid,$userid,$banktype,$bankcomment,$hanshui,$newbalance);
    if($resbank !=1 || $ressbank != 1){
        $writer->rollback();
        return XNext::gotourl('/purchase/taxpointrecord.php');
      }
    foreach($jkemuid as $k=>$v){
      $res=PurchasebillattachSvc::ins()->add($result,$v,$jkemujine[$k],$jenum);
      if($res !=1){
        $writer->rollback();
        return XNext::gotourl('/purchase/taxpointrecord.php');
      }
    }
    foreach($dkemuid as $k1=>$v1){
      $ress=PurchasebillattachSvc::ins()->add($result,$v1,$dkemujine[$k1],$denum);
      if($ress !=1){
        $writer->rollback();
        return XNext::gotourl('/purchase/taxpointrecord.php');
      }
    }
    $writer->commit();
    return XNext::gotourl('/purchase/taxpointrecord.php');
  }
}
/*税点记录页面*/
class Action_purchase_taxpointrecord extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $lists = XDao::query("PurchasebillmainQuery")->findal();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $aaa = new Core_Lib_Page($arr);
    $pages = $aaa->show(3);
    $list = XDao::query("PurchasebillmainQuery")->findalpage($aaa->first_row,$arr['list_rows']);
    foreach($list as $k=>$v){
      $bankname = XDao::query("financebankQuery")->findname($v['bankid']);
      $list[$k]['bankname'] = $bankname['name'];
      $JJ = array();
      $DD = array();
      $username = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['username'] = $username['name'];
      $J = XDao::query("PurchasebillattachQuery")->findb($v['id']);
      $D = XDao::query("PurchasebillattachQuery")->findi($v['id']);
      if($J){
        foreach ($J as $k1 => $v1) {
          $jname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
          $J[$k1]['kemuname'] = $jname['name'];
          $JJ[] = $J[$k1]['kemuname']."(".$v1['price'].")";
        }
      }
      if($D){
        foreach ($D as $k2 => $v2) {
          $dname = XDao::query("financialaccountQuery")->findname($v2['faccountid']);
          $D[$k2]['kemuname'] = $dname['name'];
          $DD[] = $D[$k2]['kemuname']."(".$v2['price'].")";
        }
      }
      $JJ = implode(",",$JJ);
      $list[$k]['J'] = $JJ;
      $DD = implode(",",$DD);
      $list[$k]['D'] = $DD;
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    return XNext::useTpl("/purchase/taxpointrecord.html");
  }
}

//查询采购单
class Action_purchase_searchpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $datestart = isset($request->attr['datestart'])?$request->attr['datestart']:"";
    $dateend = isset($request->attr['dateend'])?$request->attr['dateend']:"";
    $status = isset($request->attr['status'])?$request->attr['status']:'0';
    $tui = isset($request->attr['tui'])?$request->attr['tui']:'0';
    $shou = isset($request->attr['shou'])?$request->attr['shou']:'0';
    if($datestart){
        $dates = $datestart." 00:00:00";
    }else{
        $dates = "2000-01-01 00:00:00";
    }
    if($dateend){
        $datee = $dateend." 23:59:59";
    }else{
        $datee = date("Y-m-d H:i:s",time());
    }
    $qls = SundialQL::create()
        ->select()
        ->from("purchase")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($status){
      $qls->where("$0.statusaudit","=",$status);
    }else{
      $qls->where("$0.statusaudit","!=","F");
    }
    if($tui){
      $qls->where("$0.statusrefund","=",$tui);
    }
    if($shou){
      $qls->where("$0.statusreceipt","=",$shou);
    }
    $lists = $qls->querys();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $arr['list_rows'] = intval($arr['list_rows']);
    $aaa = new Core_Lib_Page($arr);
    $seach = "";
    if($status){
      $seach .= "&status=$status";
    }
    if($tui){
      $seach .= "&tui=$tui";
    }
    if($shou){
      $seach .= "&shou=$shou";
    }
    $seach .="&datestart=$datestart";
    $seach .="&dateend=$dateend";
    $aaa->seach = $seach;
    $pages = $aaa->show(3);
    $ql = SundialQL::create()
        ->select()
        ->from("purchase")
        ->where("$0.createtime",'$bt',array($dates,$datee));
    if($status){
      $ql->where("$0.statusaudit","=",$status);
    }else{
      $ql->where("$0.statusaudit","!=","F");
    }
    if($tui){
      $ql->where("$0.statusrefund","=",$tui);
    }
    if($shou){
      $ql->where("$0.statusreceipt","=",$shou);
    }
    $ql->limit($aaa->first_row,$arr['list_rows']);
    $list = $ql->querys();
    foreach($list as $k=>$v){
      $aaa = XDao::query("companyQuery")->findname($v['purchasecompanyid']);
      $list[$k]['purchasecompanyid'] = $aaa['name'];
      $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($v['supplierid']);
      $list[$k]['supplierid'] = $bbb['name'];
      $list[$k]['level'] = $bbb['level'];
      $ccc = XDao::query("StoreShowQuery")->findname($v['storeid']);
      $list[$k]['storeid'] = $ccc['name'];
      $list[$k]['storetype'] = $ccc['storetype'];
      $ddd = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['staffid'] = $ddd['name'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->datestart = $datestart;
    $xcontext->dateend = $dateend;
    $xcontext->tui = $tui;
    $xcontext->shou = $shou;
    $xcontext->status = $status;
    $xcontext->seach = $seach;
    return XNext::useTpl("/purchase/searchpurchase.html");
  }
}
//拒绝采购单列表
class Action_purchase_rejectpurchase extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $lists = XDao::query("PurchaseQuery")->findf();
    $arr['total_rows'] = count($lists);
    $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
    $aaa = new Core_Lib_Page($arr);
    $pages = $aaa->show(3);
    $list = XDao::query("PurchaseQuery")->findpagef($aaa->first_row,$arr['list_rows']);
    foreach($list as $k=>$v){
      $aaa = XDao::query("companyQuery")->findname($v['purchasecompanyid']);
      $list[$k]['purchasecompanyid'] = $aaa['name'];
      $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($v['supplierid']);
      $list[$k]['supplierid'] = $bbb['name'];
      $list[$k]['level'] = $bbb['level'];
      $ccc = XDao::query("StoreShowQuery")->findname($v['storeid']);
      $list[$k]['storeid'] = $ccc['name'];
      $list[$k]['storetype'] = $ccc['storetype'];
      $ddd = XDao::query("UserQuery")->userone($v['staffid']);
      $list[$k]['staffid'] = $ddd['name'];
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    return XNext::useTpl("/purchase/rejectpurchase.html");
  }
}
class Action_purchase_enterware extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id=$request->attr['id'];
    $list = XDao::query("PurchaseQuery")->find($id);
    $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($list['supplierid']);
    $ccc = XDao::query("companyQuery")->findname($list['purchasecompanyid']);
    $sss = XDao::query("StoreShowQuery")->findname($list['storeid']);
    $list['suppliername'] = $bbb['name'];
    $list['supplierlevel'] = $bbb['level'];
    $list['companyname'] = $ccc['name'];
    $list['storename'] = $sss['name'];
    $product = XDao::query("PurchaseproductQuery")->getsid($id);
    foreach($product as $k=>$v){
      $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
      $product[$k]['productname'] = $productname['name'];
      $dwname = XDao::query("ListProunitQuery")->getdwname($v['partsid']);
      $product[$k]['dwname'] = $dwname['name'];
      $zhiid = XDao::query("ListUnitByIdQuery")->getzhiid($v['productid']);
      if($zhiid){
        foreach ($zhiid as $k2=>$v2) {
          $zhiarr=XDao::query("getguigevalueQuery")->getguigevalues($v2);
          $zhiname[$k2]=$zhiarr['choice'];
        }
        $product[$k]['zhiname']=$zhiname;
      }
    }
    $bank = XDao::query("PurchasefinanceQuery")->findall($id);
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
    $xcontext->list = $list;
    $xcontext->product = $product;
    $xcontext->bank = $bank;
    $xcontext->catekemu = $catekemu;
    return XNext::useTpl("/purchase/enterware.html");
  }
}
class Action_purchase_outware extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id=$request->attr['id'];
    $list = XDao::query("PurchaseQuery")->find($id);
    $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($list['supplierid']);
    $ccc = XDao::query("companyQuery")->findname($list['purchasecompanyid']);
    $sss = XDao::query("StoreShowQuery")->findname($list['storeid']);
    $list['suppliername'] = $bbb['name'];
    $list['supplierlevel'] = $bbb['level'];
    $list['companyname'] = $ccc['name'];
    $list['storename'] = $sss['name'];
    $product = XDao::query("PurchaseproductQuery")->getsid($id);
    foreach($product as $k=>$v){
      $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
      $product[$k]['productname'] = $productname['name'];
      $dwname = XDao::query("ListProunitQuery")->getdwname($v['partsid']);
      $product[$k]['dwname'] = $dwname['name'];
      $zhiid = XDao::query("ListUnitByIdQuery")->getzhiid($v['productid']);
      if($zhiid){
        foreach ($zhiid as $k2=>$v2) {
          $zhiarr=XDao::query("getguigevalueQuery")->getguigevalues($v2);
          $zhiname[$k2]=$zhiarr['choice'];
        }
        $product[$k]['zhiname']=$zhiname;
      }
    }
    $bank = XDao::query("PurchasefinanceQuery")->findall($id);
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
    $xcontext->list = $list;
    $xcontext->product = $product;
    $xcontext->bank = $bank;
    $xcontext->catekemu = $catekemu;
    return XNext::useTpl("/purchase/outware.html");
  }
}

class Action_purchase_payfor extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id=$request->attr['id'];
    $list = XDao::query("PurchaseQuery")->find($id);
    $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($list['supplierid']);
    $ccc = XDao::query("companyQuery")->findname($list['purchasecompanyid']);
    $sss = XDao::query("StoreShowQuery")->findname($list['storeid']);
    $list['suppliername'] = $bbb['name'];
    $list['supplierlevel'] = $bbb['level'];
    $list['companyname'] = $ccc['name'];
    $list['storename'] = $sss['name'];
    $product = XDao::query("PurchaseproductQuery")->getsid($id);
    foreach($product as $k=>$v){
      $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
      $product[$k]['productname'] = $productname['name'];
      $dwname = XDao::query("ListProunitQuery")->getdwname($v['partsid']);
      $product[$k]['dwname'] = $dwname['name'];
      $zhiid = XDao::query("ListUnitByIdQuery")->getzhiid($v['productid']);
      if($zhiid){
        foreach ($zhiid as $k2=>$v2) {
          $zhiarr=XDao::query("getguigevalueQuery")->getguigevalues($v2);
          $zhiname[$k2]=$zhiarr['choice'];
        }
        $product[$k]['zhiname']=$zhiname;
      }
    }
    $bank = XDao::query("PurchasefinanceQuery")->findall($id);
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
    $xcontext->list = $list;
    $xcontext->product = $product;
    $xcontext->bank = $bank;
    $xcontext->kemu = $catekemu;
    $xcontext->zhanghu = $zhanghu;
    $xcontext->id = $id;
    return XNext::useTpl("/purchase/payfor.html");
  }
}

class Action_purchase_purchaserecive extends XLoginAction
{
  public function _run($request, $xcontext)
  {
   $id=$request->attr['id'];
   $list = XDao::query("PurchaseQuery")->find($id);
   $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($list['supplierid']);
   $ccc = XDao::query("companyQuery")->findname($list['purchasecompanyid']);
   $sss = XDao::query("StoreShowQuery")->findname($list['storeid']);
   $list['suppliername'] = $bbb['name'];
   $list['supplierlevel'] = $bbb['level'];
   $list['companyname'] = $ccc['name'];
   $list['storename'] = $sss['name'];
   $product = XDao::query("PurchaseproductQuery")->getsid($id);
   foreach($product as $k=>$v){
    $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
    $product[$k]['productname'] = $productname['name'];
    $dwname = XDao::query("ListProunitQuery")->getdwname($v['partsid']);
    $product[$k]['dwname'] = $dwname['name'];
    $zhiid = XDao::query("ListUnitByIdQuery")->getzhiid($v['productid']);
    if($zhiid){
      foreach ($zhiid as $k2=>$v2) {
        $zhiarr=XDao::query("getguigevalueQuery")->getguigevalues($v2);
        $zhiname[$k2]=$zhiarr['choice'];
      }
      $product[$k]['zhiname']=$zhiname;
    }
  }
  $bank = XDao::query("PurchasefinanceQuery")->findall($id);
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
    $xcontext->list = $list;
    $xcontext->product = $product;
    $xcontext->bank = $bank;
    $xcontext->kemu = $catekemu;
    $xcontext->zhanghu = $zhanghu;
    $xcontext->id = $id;
    return XNext::useTpl("/purchase/purchaserecive.html");
  }
}
//查询采购单详细页面
class Action_purchase_purchasedetail extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id=$request->attr['id'];
    $list = XDao::query("PurchaseQuery")->find($id);
    $bbb = XDao::query("purchasesupplierQuery")->findsuppliers($list['supplierid']);
    $ccc = XDao::query("companyQuery")->findname($list['purchasecompanyid']);
    $sss = XDao::query("StoreShowQuery")->findname($list['storeid']);
    $list['suppliername'] = $bbb['name'];
    $list['supplierlevel'] = $bbb['level'];
    $list['companyname'] = $ccc['name'];
    $list['storename'] = $sss['name'];
    $product = XDao::query("PurchaseproductQuery")->getsid($id);
    foreach($product as $k=>$v){
      $productname = XDao::query("ListProductQuery")->findProduct($v['productid']);
      $product[$k]['productname'] = $productname['name'];
      $dwname = XDao::query("ListProunitQuery")->getdwname($v['partsid']);
      $product[$k]['dwname'] = $dwname['name'];
      $zhiid = XDao::query("ListUnitByIdQuery")->getzhiid($v['productid']);
      if($zhiid){
        foreach ($zhiid as $k2=>$v2) {
          $zhiarr=XDao::query("getguigevalueQuery")->getguigevalues($v2);
          $zhiname[$k2]=$zhiarr['choice'];
        }
        $product[$k]['zhiname']=$zhiname;
      }
    }
    $bank = XDao::query("PurchasefinanceQuery")->findall($id);
    $xcontext->list = $list;
    $xcontext->product = $product;
    $xcontext->bank = $bank;
    return XNext::useTpl("/purchase/purchasedetail.html");
  }
}

//公司列表
class Action_purchase_purchase_companyset extends XLoginAction
{
 public function _run($request, $xcontext)
 {
    $company = XDao::query("companyQuery")->allcompanyinfo();
    $xcontext->company=$company;
    return XNext::useTpl("/purchase/purchase_companyset.html");
  }
}
//添加公司
class Action_purchase_addcompany extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $data['name'] = $request->attr['cname'];
    $data['comment'] = $request->attr['comment'];
    $result = CompanySvc::ins()->addcompany($data);
    return XNext::gotourl('/purchase/purchase_companyset.php');
  }
}
//删除公司
class Action_purchase_delcompany extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id = $_POST['id'];
    $list = XDao::dwriter("CompanyWriter")->delcompany($id);
    echo $list;
    return XNext::nothing();
  }
}
//修改公司
class Action_purchase_updatecompany extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $name = $request->attr['name'];
    $comment = $request->attr['comment'];
    $id = $request->attr['id'];
    $list = XDao::dwriter("CompanyWriter")->upcompany($name,$comment,$id);
    return XNext::gotourl('/purchase/purchase_companyset.php');
  }
}
//执行采购入库
class Action_purchase_doinput extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $cookuid  = $_COOKIE['U'];
    $uidarr  = explode('=',$cookuid);
    $userid  = $uidarr['2'];//操作人
    $storeid = $request->attr['storeid'];
    $gysid = $request->attr['gysid'];
    $company = $request->attr['cid'];
    $purchaseid = $request->attr['purchaseid'];
    $status = $request->attr['status'];
    $kemuid = $request->attr['kemuid'];
    $type = 'Input';
    $comment = $request->attr['comment'];
    $productid = $request->attr['pid'];
    $num = $request->attr['num'];
    $price = $request->attr['price'];
    $prices = 0;
    $nums = array_sum($num);
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    //修改采购单主表的数量
    $res = XDao::dwriter("PurchaseWriter")->purchaseruku($nums,$nums,$status,$purchaseid);
    if($res !=1){
      $writer->rollback();
    }
    foreach($productid as $k=>$v){
      if($num[$k]>0){
        //修改采购单商品详情表的数量
        $ress = XDao::dwriter("PurchaseproductWriter")->productruku($num[$k],$num[$k],$v,$purchaseid);
        if($ress != 1){
          $writer->rollback();
        }
        $prices += $num[$k]*$price[$k];
        //修改实时库存表的数量
        $resu = XDao::dwriter("StrProductWriter")->editnuminruku($num[$k],$num[$k],$v);
        if($resu != 1){
          $writer->rollback();
        }
      }
    }
    $money = $prices;
    $last = XDao::query("subjectbalanceQuery")->findlast($kemuid);
    if($last['endingpce']){
      $qichu = $last['endingpce'];
    }else{
      $qichu = 0;
    }
    $qimo = $qichu+$money;
    //财务科目表记录一条数据
    $result=SubjectbalanceSvc::ins()->addsubjectbalance($kemuid,$money,$qichu,$qimo);
    if($result!=1){
      $writer->rollback();
    }
    // 采购单据主表添加一条数据
    $danju=PurchaseoibillSvc::ins()->adddanju($gysid,$storeid,$purchaseid,$company,$userid,$nums,$prices,$kemuid,$type);
    foreach($productid as $k=>$v){
      $gold = $num[$k]*$price[$k];
      if($num[$k]>0){
        //采购单据附表
        $sdanju=GstorageinfoSvc::ins()->addsdj($danju,$storeid,$v,$num[$k],$price[$k],$gold,$comment[$k]);
        if($sdanju!=1){
          $writer->rollback();
        }
      }
    }
    $writer->commit();
    return XNext::gotourl('/purchase/inoroutlist.php');

  }
}
//执行采购单出库
class Action_purchase_dooutput extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $cookuid  = $_COOKIE['U'];
    $uidarr  = explode('=',$cookuid);
    $userid  = $uidarr['2'];//操作人
    $storeid = $request->attr['storeid'];
    $gysid = $request->attr['gysid'];
    $company = $request->attr['cid'];
    $purchaseid = $request->attr['purchaseid'];
    $status = $request->attr['status'];
    $kemuid = $request->attr['kemuid'];
    $type = 'Output';
    $comment = $request->attr['comment'];
    $productid = $request->attr['pid'];
    $num = $request->attr['num'];
    $price = $request->attr['price'];
    $prices = 0;
    $nums = array_sum($num);
    $writer = XDao::dwriter('DWriter');
    $writer->beginTrans();
    //修改采购单主表数量和状态
    $res = XDao::dwriter("PurchaseWriter")->purchasechuku($nums,$nums,$status,$purchaseid);
    if($res !=1){
      $writer->rollback();
    }
    foreach($productid as $k=>$v){
      if($num[$k]>0){
        //修改采购单商品详情表的数量
        $ress = XDao::dwriter("PurchaseproductWriter")->productchuku($num[$k],$num[$k],$v,$purchaseid);
        if($ress != 1){
          $writer->rollback();
        }
        $prices += $num[$k]*$price[$k];
        //修改实时库存表的数量
        $resu = XDao::dwriter("StrProductWriter")->editNumInOutStore($num[$k],$v,'','decrease');
        if($resu != 1){
          $writer->rollback();
        }
      }
    }
    $money = $prices;
    $last = XDao::query("subjectbalanceQuery")->findlast($kemuid);
    if($last['endingpce']){
      $qichu = $last['endingpce'];
    }else{
      $qichu = 0;
    }
    $qimo = $qichu-$money;
    //财务科目表记录一条数据
    $result=SubjectbalanceSvc::ins()->addsubjectbalance($kemuid,$money,$qichu,$qimo);
    if($result != 1){
      $writer->rollback();
    }
    //采购单据主表
    $danju=PurchaseoibillSvc::ins()->adddanju($gysid,$storeid,$purchaseid,$company,$userid,$nums,$prices,$kemuid,$type);
    foreach($productid as $k=>$v){
      $gold = $num[$k]*$price[$k];
      if($num[$k]>0){
        //采购单据附表
        $sdanju=GstorageinfoSvc::ins()->addsdj($danju,$storeid,$v,$num[$k],$price[$k],$gold,$comment[$k]);
        if($sdanju!=1){
          $writer->rollback();
        }
      }
    }
    $writer->commit();
    return XNext::gotourl('/purchase/inoroutlist.php');
  }
}

//采购单据详情
class Action_purchase_danjuinfo extends XLoginAction
{
  public function _run($request, $xcontext)
  {
    $id = $request->attr['id'];
    $list = XDao::query("GstorageinfolQuery")->findforid($id);
    foreach($list as $k=>$v){
      $valuename1 = XDao::query("getguigevalueQuery")->getguigevalues($v['valueid1']);
      $valuename2 = XDao::query("getguigevalueQuery")->getguigevalues($v['valueid2']);
      $valuename3 = XDao::query("getguigevalueQuery")->getguigevalues($v['valueid3']);
      $valuename4 = XDao::query("getguigevalueQuery")->getguigevalues($v['valueid4']);
      $valuename5 = XDao::query("getguigevalueQuery")->getguigevalues($v['valueid5']);
      $list[$k]['valuename1'] = $valuename1['choice'];
      $list[$k]['valuename2'] = $valuename2['choice'];
      $list[$k]['valuename3'] = $valuename3['choice'];
      $list[$k]['valuename4'] = $valuename4['choice'];
      $list[$k]['valuename5'] = $valuename5['choice'];
    }
    echo json_encode($list,JSON_UNESCAPED_UNICODE);
    return XNext::nothing();
  }
}
