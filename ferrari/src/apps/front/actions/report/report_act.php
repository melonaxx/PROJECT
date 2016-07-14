<?php
/*采购报表页面*/
class Action_report_purchasereport extends XAction
{
    public function _run($request, $xcontext)
    {
        $productid = $request->attr['productid'];
        $proname = XDao::query("ListProductQuery")->findProduct($productid);
        $gys = $request->attr['gys'];
        $datestart = $request->attr['datestart'];
        $dateend = $request->attr['dateend'];
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
        $shui = $request->attr['shui'];
        if($shui){
            $prices = "taxprice";
        }else{
            $prices = "notaxprice";
        }
        $supplier = XDao::query("purchasesupplierQuery")->findallname();
        $xcontext->supplier = $supplier;
        $pg = new GetFormatStingByProductId();
        $ql = SundialQL::create()
        ->select("$0.price","$0.total","$0.totalrefund","$0.productid","$0.taxrate","$0.$prices as taxprice","$1.createtime","$1.number","$4.name as suppname","$4.level","$2.name","$3.name as companyname","$5.name as dwname")
        ->from("purchaseproduct")
        ->innerJoin("purchase", "$0.purchaseid=$1.id")
        ->innerJoin("product", "$0.productid=$2.productid")
        ->innerJoin("company","$1.purchasecompanyid=$3.id")
        ->innerJoin("purchasesupplier","$1.supplierid=$4.id")
        ->innerJoin("prounit","$0.partsid=$5.id")
        ->where("$1.statusaudit","=","Y")
        ->where("$1.createtime",'$bt',array($dates,$datee));
        if($productid){
        $gui = $pg->getformatstr($productid);
        $proname = $proname['name'];
        $ql->where("$0.productid","=",$productid);
        }
        if($gys){
        $ql->where("$1.supplierid","=",$gys);
        }
        $ql->orderBy("$0.id");
        $result = $ql->querys();
        $totals = 0;
        $tuis = 0;
        $pays = 0; 
        $tuipays =0; 
        foreach ($result as $k => $v) {
            $result[$k]['createtime'] = date("Y-m-d",strtotime($v['createtime']));
            $guige = $pg->getformatstr($v['productid']);
            $result[$k]['guige'] = $guige;
            if($shui){
                $tuipay = $v['totalrefund']*$v['price'];
            }else{
                $tuipay = ($v['totalrefund']*$v['price'])/(1+$v['taxrate']/100);
            }
            $tuipay =sprintf("%.2f",$tuipay);
            $result[$k]['tuipay'] = $tuipay;
            $tuipays += $tuipay;
            $tuipays = sprintf("%.2f",$tuipays);
            $totals += $result[$k]['total'];
            $tuis += $result[$k]['totalrefund'];
            $pays += $result[$k]['taxprice'];
            $pays =sprintf("%.2f",$pays);
        }
        $xcontext->list = $result;
        $xcontext->totals = $totals;
        $xcontext->pays = $pays;
        $xcontext->tuis = $tuis;
        $xcontext->shui = $shui;
        $xcontext->tuipays = $tuipays;
        $xcontext->gys = $gys;
        $xcontext->dates = $datestart;
        $xcontext->datee = $dateend;
        $xcontext->pro = $productid;
        $xcontext->gui = $gui;
        $xcontext->proname = $proname;
        return XNext::useTpl("/report/purchasereport.html");
    }
}
//商品搜索模糊查询
class Action_report_findproduct extends XAction
{
  public function _run($request, $xcontext)
  {
    $comment = $request->attr['comment'];
      //查询商品id和名称
    $pg = new GetFormatStingByProductId();
    $result = XDao::query("ListProductQuery")->likeproduct($comment);
    foreach($result as $k=>$v){
      $id=$v['productid'];
      $result[$k]['guige'] = $pg->getformatstr($id);
    }   
    echo json_encode($result,JSON_UNESCAPED_UNICODE);
  }    
}
class Action_report_goodcollect extends XAction
{
    public function _run($request, $xcontext)
    {
        $productid = $request->attr['productid'];
        $proname = XDao::query("ListProductQuery")->findProduct($productid);
        $datestart = $request->attr['datestart'];
        $dateend = $request->attr['dateend'];
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
        $shui = $request->attr['shui'];
        if($shui){
            $prices = "taxprice";
        }else{
            $prices = "notaxprice";
        }
        $radio = isset($request->attr['radio'])?"1":"1+$0.taxrate/100";
        $taxprice = isset($request->attr['radio'])?"taxprice":"notaxprice";
        $pg = new GetFormatStingByProductId();
        $ql = SundialQL::create()
        ->select("$0.productid","sum($0.total) as total","sum($0.totalrefund) as totalrefund","sum($0.$taxprice) as pay","sum($0.price*$0.totalrefund/($radio)) as tui","$1.number","$1.name as proname","$3.name as dwname")
        ->from("purchaseproduct")
        ->innerJoin("product", "$0.productid=$1.productid")
        ->innerJoin("proinfo", "$0.productid=$2.productid")
        ->innerJoin("prounit", "$2.unitid=$3.id")
        ->innerJoin("purchase", "$0.purchaseid=$4.id")
        ->where("$4.statusaudit","=","Y")
        ->where("$0.createtime",'$bt',array($dates,$datee))
        ->groupBy("$0.productid"); 
        if($productid){
        $gui = $pg->getformatstr($productid);
        $proname = $proname['name'];
        $ql->where("$0.productid","=",$productid);
        }
        $result = $ql->querys();
        foreach ($result as $k => $v) {
            $guige = $pg->getformatstr($v['productid']);
            $result[$k]['guige'] = $guige;
            $tui = $v['tui'];
            $tui =sprintf("%.2f",$tui);
            $result[$k]['tui'] = $tui;
            $tuipays += $tui;
            $tuipays = sprintf("%.2f",$tuipays);
            $totals += $result[$k]['total'];
            $tuis += $result[$k]['totalrefund'];
            $pays += $result[$k]['pay'];
            $pays =sprintf("%.2f",$pays);
        }
        $xcontext->result = $result;
        $xcontext->tuipays = $tuipays;
        $xcontext->totals = $totals;
        $xcontext->pays = $pays;
        $xcontext->tuis = $tuis;
        $xcontext->radio = $radio;
        $xcontext->dates = $datestart;
        $xcontext->datee = $dateend;
        $xcontext->pro = $productid;
        $xcontext->gui = $gui;
        $xcontext->proname = $proname;
        return XNext::useTpl("/report/goodcollect.html");
    }
}

class Action_report_suppliercollect extends XAction
{
    public function _run($request, $xcontext)
    {

        $productid = $request->attr['productid'];
        $proname = XDao::query("ListProductQuery")->findProduct($productid);
        $supplier = XDao::query("purchasesupplierQuery")->findallname();
        $xcontext->supplier = $supplier;
        $gys = $request->attr['gys'];
        $datestart = $request->attr['datestart'];
        $dateend = $request->attr['dateend'];
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
        $radio = isset($request->attr['radio'])?"1":"1+$0.taxrate/100";
        $taxprice = isset($request->attr['radio'])?"taxprice":"notaxprice";
        $pg = new GetFormatStingByProductId();
        $ql = SundialQL::create()
        ->select("$0.productid","$5.name as suppliername","sum($0.total) as total","sum($0.totalrefund) as totalrefund","sum($0.$taxprice) as pay","sum($0.price*$0.totalrefund/($radio)) as tui","$1.number","$1.name as proname","$3.name as dwname")
        ->from("purchaseproduct")
        ->innerJoin("product", "$0.productid=$1.productid")
        ->innerJoin("proinfo", "$0.productid=$2.productid")
        ->innerJoin("prounit", "$2.unitid=$3.id")
        ->innerJoin("purchase", "$0.purchaseid=$4.id")
        ->innerJoin("purchasesupplier", "$4.supplierid=$5.id")
        ->where("$4.statusaudit","=","Y")
        ->where("$0.createtime",'$bt',array($dates,$datee))
        ->orderBy("$4.supplierid")
        ->groupBy("$0.productid","$4.supplierid"); 
        if($productid>0){
        $gui = $pg->getformatstr($productid);
        $proname = $proname['name'];
        $ql->where("$0.productid","=",$productid);
        }
        if($gys>0){
        $ql->where("$4.supplierid","=",$gys);
        }
        $result = $ql->querys(); 
         foreach ($result as $k => $v) {
            $guige = $pg->getformatstr($v['productid']);
            $result[$k]['guige'] = $guige;
            $tui = $v['tui'];
            $tui =sprintf("%.2f",$tui);
            $result[$k]['tui'] = $tui;
            $tuipays += $tui;
            $tuipays = sprintf("%.2f",$tuipays);
            $totals += $result[$k]['total'];
            $tuis += $result[$k]['totalrefund'];
            $pays += $result[$k]['pay'];
            $pays =sprintf("%.2f",$pays);
        }
        $xcontext->result = $result;
        $xcontext->radio = $radio;
        $xcontext->gys = $gys;
        $xcontext->dates = $datestart;
        $xcontext->datee = $dateend;
        $xcontext->pro = $productid;
        $xcontext->gui = $gui;
        $xcontext->proname = $proname;
        $xcontext->tuipays = $tuipays;
        $xcontext->totals = $totals;
        $xcontext->pays = $pays;
        $xcontext->tuis = $tuis;
        return XNext::useTpl("report/suppliercollect.html");
    }
}

class Action_report_comcollect extends XAction
{
    public function _run($request, $xcontext)
    {

        $productid = $request->attr['productid'];
        $proname = XDao::query("ListProductQuery")->findProduct($productid);
        $company = XDao::query("companyQuery")->allcompanyinfo();
        $xcontext->company = $company;
        $coms = $request->attr['coms'];
        $datestart = $request->attr['datestart'];
        $dateend = $request->attr['dateend'];
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
        $radio = isset($request->attr['radio'])?"1":"1+$0.taxrate/100";
        $taxprice = isset($request->attr['radio'])?"taxprice":"notaxprice";
        $pg = new GetFormatStingByProductId();
        $ql = SundialQL::create()
        ->select("$0.productid","$5.name as companyname","sum($0.total) as total","sum($0.totalrefund) as totalrefund","sum($0.$taxprice) as pay","sum($0.price*$0.totalrefund/($radio)) as tui","$1.number","$1.name as proname","$3.name as dwname")
        ->from("purchaseproduct")
        ->innerJoin("product", "$0.productid=$1.productid")
        ->innerJoin("proinfo", "$0.productid=$2.productid")
        ->innerJoin("prounit", "$2.unitid=$3.id")
        ->innerJoin("purchase", "$0.purchaseid=$4.id")
        ->innerJoin("company", "$4.purchasecompanyid=$5.id")
        ->where("$4.statusaudit","=","Y")
        ->where("$0.createtime",'$bt',array($dates,$datee))
        ->orderBy("$4.purchasecompanyid,$0.productid")
        ->groupBy("$0.productid","$4.purchasecompanyid"); 
        if($productid>0){
        $gui = $pg->getformatstr($productid);
        $proname = $proname['name'];
        $ql->where("$0.productid","=",$productid);
        }
        if($coms>0){
        $ql->where("$4.purchasecompanyid","=",$coms);
        }
        $result = $ql->querys(); 
         foreach ($result as $k => $v) {
            $guige = $pg->getformatstr($v['productid']);
            $result[$k]['guige'] = $guige;
            $tui = $v['tui'];
            $tui =sprintf("%.2f",$tui);
            $result[$k]['tui'] = $tui;
            $tuipays += $tui;
            $tuipays = sprintf("%.2f",$tuipays);
            $totals += $result[$k]['total'];
            $tuis += $result[$k]['totalrefund'];
            $pays += $result[$k]['pay'];
            $pays =sprintf("%.2f",$pays);
        }
        $xcontext->result = $result;
        $xcontext->radio = $radio;
        $xcontext->coms = $coms;
        $xcontext->dates = $datestart;
        $xcontext->datee = $dateend;
        $xcontext->pro = $productid;
        $xcontext->gui = $gui;
        $xcontext->proname = $proname;
        $xcontext->tuipays = $tuipays;
        $xcontext->totals = $totals;
        $xcontext->pays = $pays;
        $xcontext->tuis = $tuis;
        return XNext::useTpl("report/comcollect.html");
    }
}

/*综合报表*/
class Action_report_traderecord extends XAction
{
    public function _run($request, $xcontext)
    {
        $seach = strval($request->attr['seach']);
        if(strlen($seach)!=0){
            $where = "where bankid like '%$seach%'";
        }else{
            $where ="";
        }
        $lists = XDao::query("bankactactionQuery")->allbank($where);
        $arr['total_rows'] = count($lists);
        $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
        $aaa = new Core_Lib_Page($arr);
        if(strlen($seach)!=0){
            $aaa->seach = "&seach=$seach";
        }
        $pages = $aaa->show(3);
        $list = XDao::query("bankactactionQuery")->allbanks($where,$aaa->first_row,$arr['list_rows']);
        foreach ($list as $k => $v) {
            $bankname = XDao::query("financebankQuery")->findname($v['bankid']);
            $username = XDao::query("UserQuery")->userone($v['staffid']);
            $list[$k]['bankname'] = $bankname['name'];
            $list[$k]['username'] = $username['name'];
        }
        $zhanghu = XDao::query("financebankQuery")->allfinancebank();
        $xcontext->zhanghu = $zhanghu;
        $xcontext->list = $list;
        $xcontext->pages = $pages;
        $xcontext->seach=$seach;
        return XNext::useTpl("/report/traderecord.html");
    }
}
/*财务报表*/
class Action_report_debttable extends XAction
{
    public function _run($request, $xcontext)
    {

        return XNext::useTpl("report/debttable.html");
    }
}
class Action_report_profitstable extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("report/profitstable.html");
    }
}
class Action_report_cashflowtable extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("report/cashflowtable.html");
    }
}
/*生产明细表*/
class Action_report_prodetail extends XAction
{
    public function _run($request, $xcontext)
    {

        $where = "where manufactory.id=processmanufactory.productinfoid and processmanufactory.status = 'Y'";

        if(!empty($_GET['goods_id'])){
            $where .= " and manufactory.productid = '{$_GET['goods_id']}'";
        }

        if(!empty($_GET['oem'])){
            $where .= " and processmanufactory.profactoryid = '{$_GET['oem']}'";
        }

        if(!empty($_GET['starttime']) && !empty($_GET['stoptime'])){
            $where .= " and manufactory.actiondate > '{$_GET["starttime"]}' and manufactory.actiondate < '{$_GET["stoptime"]}'";
        }

        $pro_order_list = XDao::query("ProcessmanufactoryQuery")->all_pro_order($where);


        $real_info = array();

        foreach($pro_order_list as $k => $v){
             //生产单时间
             $real_info[$k]['actiontime'] = $v['actiondate'];
             //生产单编号
             $real_info[$k]['number'] = $v['number'];
             //代工户
             $oemlist = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);
             $real_info[$k]['oemname'] = $oemlist['name'];

             //商品名称和信息
             $goodsname  = XDao::query("showproductQuery")->goodsname($v['productid']);
             $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
             $real_info[$k]['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
            
             //获取单位名称
             if($v['productid'] != ""){
                $proflats = XDao::query('addproductQuery')->get_flats($v['productid']);
                $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
                $real_info[$k]['proflats_name'] = $proflats_name['name'];
             }

             //入库数量
             $real_info[$k]['totalfinish'] = $v['totalfinish'];
             //返工数量
             $real_info[$k]['totalrefund'] = $v['totalrefund'];
        }

        $xcontext->pro_order_list=$real_info;

        return XNext::useTpl("report/prodetail.html");
    }
}
/*生产汇总(商品)*/
class Action_report_allgoodreport extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = " where statusaudit = 'Y' and product.productid = manufactory.productid";

        if(!empty($_GET['goods_id'])){
            $where .= " and manufactory.productid = '{$_GET['goods_id']}'";
        }

        if(!empty($_GET['starttime']) && !empty($_GET['stoptime'])){
            $where .= " and manufactory.actiondate > '{$_GET["starttime"]}' and manufactory.actiondate < '{$_GET["stoptime"]}'";
        }

        $pro_order_list = XDao::query("showproductQuery")->all_goods_count($where);
        
        $goods_info=array();

        //声明订单状态的数组
        foreach($pro_order_list as $k => $v){

            //商品名称和信息
            $goodsname_number  = XDao::query("showproductQuery")->goodsname($v['productid']);
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $goods_info[$k]['goodsinfo']=$goodsname_number['name'].'&nbsp;('.$goodsinfo.')';

            //商品编码
            $goods_info[$k]['number']=$goodsname_number['number'];
            //获取单位名称
            $proflats = XDao::query('addproductQuery')->get_flats($v['productid']);
            if(!empty($proflats['unitid'])){
                 $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
                 $goods_info[$k]['proflats_name'] = $proflats_name['name'];
            }

            $goods_info[$k]['finish'] = $v['finish'];
            $goods_info[$k]['refund'] = $v['refund'];
        }

        $xcontext->goods_info = $goods_info;
// echo "<pre>";
// var_dump($goods_info);
        return XNext::useTpl("report/allgoodreport.html");
    }
}

/*生产汇总(代工户)*/
class Action_report_alldaireport extends XAction
{
    public function _run($request, $xcontext)
    {

        $where = " where processmanufactory.status = 'Y' and manufactory.id = processmanufactory.productinfoid";

        if(!empty($_GET['goods_id'])){
            $where .= " and manufactory.productid = '{$_GET['goods_id']}'";
        }

        if(!empty($_GET['oem'])){
            $where .= " and processmanufactory.profactoryid = '{$_GET['oem']}'";
        }

        if(!empty($_GET['starttime']) && !empty($_GET['stoptime'])){
            $where .= " and manufactory.actiondate > '{$_GET["starttime"]}' and manufactory.actiondate < '{$_GET["stoptime"]}'";
        }
        
        $pro_order_list = XDao::query("showproductQuery")->oem_goods($where);

        $goods_info=array();
        foreach($pro_order_list as $k => $v){

            //商品名称和信息
            $goodsname_number  = XDao::query("showproductQuery")->goodsname($v['productid']);
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $goods_info[$k]['goodsinfo']=$goodsname_number['name'].'&nbsp;('.$goodsinfo.')';

            //商品编码
            $goods_info[$k]['number']=$goodsname_number['number'];
            //获取单位名称
            $proflats = XDao::query('addproductQuery')->get_flats($v['productid']);
            if(!empty($proflats['unitid'])){
                 $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
                 $goods_info[$k]['proflats_name'] = $proflats_name['name'];
            }

            //代工户
            $oemlist = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);
            $goods_info[$k]['oemname'] = $oemlist['name'];

            $goods_info[$k]['finish'] = $v['finish'];
            $goods_info[$k]['refund'] = $v['refund'];
        }
        $xcontext->goodsinfo = $goods_info;
        return XNext::useTpl("report/alldaireport.html");
    }
}

/*销售明细表*/
class Action_report_salereport extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("report/salereport.html");
    }
}
/*销售汇总(按商品)*/
class Action_report_salegoodreport extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("report/salegoodreport.html");
    }
}
/*销售汇总(按客户)*/
class Action_report_customreport extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("report/customreport.html");
    }
}
/*销售汇总(按销售员)*/
class Action_report_salemanreport extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("report/salemanreport.html");
    }
}




