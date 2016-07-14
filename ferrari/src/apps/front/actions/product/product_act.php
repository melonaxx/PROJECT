<?php

class Action_product_product_addlist extends XAction
{
    public function _run($request, $xcontext)
    {
        $storeinfo = XDao::query('StoreShowQuery')->listStoreInfo();
        $array = array('Sales'=>'销售仓','Defective'=>'次品仓','Customer'=>'售后仓','Purchase'=>'采购仓');
        foreach($storeinfo as $k => $v){
            $storeinfo[$k]['storetype']=$array["{$v['storetype']}"];
        }
        $xcontext->storehouse = $storeinfo;
        return XNext::useTpl("product/product_addlist.html");
    }
}
//请求商品信息
class Action_product_product_goodsinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $store_name = $_GET['store_name'];
        $productid = $_GET['productid'];
        $storehouse_id = $_GET['storeid'];

        $where = "where isdelete = 'N'";

        if(!empty($storehouse_id)){
            $where = ",strproduct where strproduct.productid = product.productid and strproduct.storeid={$storehouse_id}";
        }

        if(!empty($store_name)){
            $where .= " and name like '%{$store_name}%'";
        }

        if(!empty($productid)){
            $where .= " and product.productid = {$productid}";
        }


        $storelist = XDao::query("addproductQuery")->goods_list($where);

        $store_info = array();

        foreach($storelist as $k => $v){
            $store_info[$k]['productid'] = $v['productid'];
            //商品图片
            $proimg = XDao::query('ListImageQuery')->getimagebyproid($v['productid']);
            $store_info[$k]['img']=$proimg[0]['url']."/".$proimg[0]['filename'];

            $proflats = XDao::query('addproductQuery')->get_flats($v['productid']);
            
            //获取单位名称
            if(!empty($proflats['unitid'])){
                     $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
                     $store_info[$k]['proflats_name'] = $proflats_name['name'];
            }

             //获取商品规格信息
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            //名称规格值信息
            $formats = '名称:'.$v['name'].' 规格:'.$goodsinfo;
            $store_info[$k]['formats'] = $formats;

            //数量
            $store_info[$k]['totalreal'] = $v['totalreal'];

        }
       echo json_encode($store_info);

    }
}

//保存生产单
class Action_product_product_saveproorder extends XAction
{
    public function _run($request, $xcontext)
    {
       $pro_order_info = $_POST;
       $cookuid     = $_COOKIE['U'];
       $uidarr      = explode('=',$cookuid);
       $uid = $uidarr['2'];//操作人
       $row = ManufactorySvc::ins()->add_pro_order($pro_order_info,$uid);
       if($row){
           return XNext::gotourl('/product/product_addlist.php');
       }
    }
}
//审核生产单
class Action_product_product_checklist extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = " where (statusaudit = 'R' or statusaudit ='N')";

        if(!empty($_GET['peoplename'])){
            $where = ",user where user.id = manufactory.staffid and name like '%{$_GET['peoplename']}%' and (statusaudit = 'R' or statusaudit ='N')";
        }

        if(!empty($_GET['dateinfo'])){
            $where .= " and actiondate like '%{$_GET['dateinfo']}%'";
        }

        $array = array();
        $count = XDao::query("showproductQuery")->count($where);
        //总条数
        $array['total_rows'] = $count['num'];
        //每页多少条

        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):5;

        $page = new Core_Lib_Page($array);

        $page->seach = "&search_name={$search_name}";

        $pro_order_list = XDao::query("showproductQuery")->all_pro_order($where,$page->first_row,$array['list_rows']);
        // var_dump($pro_order_list);
        //声明订单状态的数组
        $array1 = array("R"=>"待修改","N"=>"待审核");
        foreach($pro_order_list as $k => $v){
            $pro_order_list[$k]['status']=$array1["{$v['statusaudit']}"];
            //商品名称和信息
            $goodsname  = XDao::query("showproductQuery")->goodsname($v['productid']);

            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $pro_order_list[$k]['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
            //仓库名称和类型
            $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($v['storeid']);
            $pro_order_list[$k]['storename'] = $storeinfo;
            //操作人
            $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
            $pro_order_list[$k]['peoplename']=$etc_people['name'];
        }

        $pages = $page->show(3);

        $xcontext->pages = $pages;
        $xcontext->pro_order_list=$pro_order_list;
        return XNext::useTpl("product/product_checklist.html");
    }
}

class Action_product_checklist extends XAction
{
    public function _run($request, $xcontext)
    {
        $pro_order_id = intval($_GET['id']);
        $one_pro_list = XDao::query("showproductQuery")->one_pro_info($pro_order_id);
        //仓库名称和类型
        $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($one_pro_list['storeid']);
        $one_pro_list['storeinfo'] = $storeinfo;
        //商品名称和信息
        $goodsname  = XDao::query("showproductQuery")->goodsname($one_pro_list['productid']);
        $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($one_pro_list['productid']);
        $one_pro_list['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
        //商品图片
        $proimg = XDao::query('ListImageQuery')->getimagebyproid($one_pro_list['productid']);
        $one_pro_list['img']=$proimg[0]['url']."/".$proimg[0]['filename'];

        //获取单位名称
        $proflats = XDao::query('addproductQuery')->get_flats($one_pro_list['productid']);
        $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
        $one_pro_list['proflats_name'] = $proflats_name['name'];

        $xcontext->one_pro_list = $one_pro_list;
        return XNext::useTpl("product/checklist.html");
    }
}
//打回修改 审核通过
class Action_product_check_change extends XAction
{
    public function _run($request, $xcontext)
    {
        $status = $_POST['statusaudit'];
        $pro_order_id = $_POST['array'];

        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();

        for($i = 0;$i<count($pro_order_id);$i++){

            $row = ManufactorySvc::ins()->to_change($pro_order_id[$i],$status);



            //改变库存数量
            //拿到生产的数量
            if($status == "Y"){
                //在途数量修改
                $raw = XDao::dwriter("ManufactoryWriter")->change_way($pro_order_id[$i]);

                $one_pro_list = XDao::query("showproductQuery")->one_pro_info($pro_order_id[$i]);
                //要增加的数量
                $number = $one_pro_list['total'];
                //商品的id
                $productid = $one_pro_list['productid'];
                //仓库的id 
                $storeid = $one_pro_list['storeid'];
                /**
                 *
                 * 仓库里有就修改 没有就新增
                 */
                $list = XDao::query("showproductQuery")->strpro($productid);

                if(empty($list)){
                    $row1 = StrProductSvc::ins()->add_newgoods($number,$productid,$storeid);
                    
                }else if(!empty($list)){

                    $row1 = XDao::dwriter("StrProductWriter")->update_newgoods($number,$list['id']);

                }
                
            } 

            if(!$row){
               $writer->rollback();
            }
        }

        if($writer->commit()){
            echo "yes";
        }else{
            echo "no";
        }

    }
}
//更改生产单
class Action_product_checklist_change extends XAction
{
    public function _run($request, $xcontext)
    {
        $pro_order_id = intval($_GET['id']);
        $one_pro_list = XDao::query("showproductQuery")->one_pro_info($pro_order_id);
        //仓库名称和类型
        $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($one_pro_list['storeid']);
        $one_pro_list['storeinfo'] = $storeinfo;
        //商品名称和信息
        $goodsname  = XDao::query("showproductQuery")->goodsname($one_pro_list['productid']);
        $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($one_pro_list['productid']);
        $one_pro_list['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
        //商品图片
        $proimg = XDao::query('ListImageQuery')->getimagebyproid($one_pro_list['productid']);
        $one_pro_list['img']=$proimg[0]['url']."/".$proimg[0]['filename'];

        //获取单位名称
        $proflats = XDao::query('addproductQuery')->get_flats($one_pro_list['productid']);
        $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
        $one_pro_list['proflats_name'] = $proflats_name['name'];

        $xcontext->one_pro_list = $one_pro_list;

        return XNext::useTpl("product/checklist_change.html");
    }
}
//请求仓库
class Action_product_allstorehouse extends XAction
{
    public function _run($request, $xcontext)
    {
        $storeinfo = XDao::query('StoreShowQuery')->listStoreInfo();
        $array = array('Sales'=>'销售仓','Defective'=>'次品仓','Customer'=>'售后仓','Purchase'=>'采购仓');
        foreach($storeinfo as $k => $v){
            $storeinfo[$k]['storetype']=$array["{$v['storetype']}"];
        }
        echo json_encode($storeinfo);
    }
}

//执行生产单修改
class Action_product_dochange extends XAction
{
    public function _run($request, $xcontext)
    {
       $pro_order_info = $_POST;
       $cookuid     = $_COOKIE['U'];
       $uidarr      = explode('=',$cookuid);
       $uid = $uidarr['2'];//操作人
       $row = ManufactorySvc::ins()->change_pro_order($pro_order_info,$uid);
       if($row){
           return XNext::gotourl('/product/product_checklist.php');
       }

    }
}

class Action_product_product_search extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = " where (statusaudit <> 'F')";

        if (!empty($_GET['starttime']) && !empty($_GET['stoptime'])) {
            $where .= " and actiondate > '{$_GET['starttime']}' and actiondate < '{$_GET['stoptime']}'";
        }
        if (!empty($_GET['product_status'])) {
            $where .= " and statusreceipt = '{$_GET['product_status']}'";
        }
        if (!empty($_GET['statusrefund'])) {
            $where .= " and statusrefund = '{$_GET['statusrefund']}'";
        }
        if (!empty($_GET['statusaudit'])) {
            $where .= " and statusaudit = '{$_GET['statusaudit']}'";
        }
        $array = array();
        $count = XDao::query("showproductQuery")->count($where);
        //总条数
        $array['total_rows'] = $count['num'];
        //每页多少条

        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):5;

        $page = new Core_Lib_Page($array);

        $page->seach = "&search_name={$search_name}";

        $pro_order_list = XDao::query("showproductQuery")->all_pro_order($where,$page->first_row,$array['list_rows']);
        // var_dump($pro_order_list);
        //声明订单状态的数组
        $array1 = array("R"=>"待修改","N"=>"待审核","Y"=>'通过审核');
        foreach($pro_order_list as $k => $v){
            $pro_order_list[$k]['status']=$array1["{$v['statusaudit']}"];
            //商品名称和信息
            $goodsname  = XDao::query("showproductQuery")->goodsname($v['productid']);

            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $pro_order_list[$k]['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
            //仓库名称和类型
            $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($v['storeid']);
            $pro_order_list[$k]['storename'] = $storeinfo;

            //入库状态
            $storeRec = XDao::query("RecstatusQuery")->showRecstatus($v['statusreceipt']);
            $pro_order_list[$k]['storeRec']=$storeRec;

            //返工状态
            $storeRef = XDao::query("RefstatusQuery")->showRefstatus($v['statusrefund']);
            $pro_order_list[$k]['storeRef']=$storeRef;

            //操作人
            $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
            $pro_order_list[$k]['peoplename']=$etc_people['name'];
        }

        $pages = $page->show(3);

        $xcontext->pages = $pages;
        $xcontext->pro_order_list=$pro_order_list;
        // echo "<pre>";
        // var_dump($pro_order_list);
        return XNext::useTpl("product/product_search.html");
    }
}

//所有代工户
class Action_product_product_foundry extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = " where isdelete = 'N'";

        if ($search_name != ""){
            $where .= " and number like '%{$search_name}%' or name like '%{$search_name}%'";
        }

        $array = array();
        $count = XDao::query("processfactoryQuery")->count($where);
        //总条数
        $array['total_rows'] = $count['num'];
        //每页多少条
        $array['list_rows'] = isset($_GET['num'])?$_GET['num']:5;

        $page = new Core_Lib_Page($array);

 

        $oem_list=XDao::query("processfactoryQuery")->all_oem($where,$page->first_row,$array['list_rows']);
        $array = array("P"=>"主选","A"=>"备选","E"=>"淘汰");
        foreach($oem_list as $k => $v){
           $level = $array["{$v['level']}"];
           $oem_list[$k]['level'] = $level;
        }
        $pages = $page->show(3);
        $xcontext->pages = $pages;
        $xcontext->oem_list=$oem_list;
        return XNext::useTpl("product/product_foundry.html");
    }
}
//代工户删除
class Action_product_product_delfoundry extends XAction
{
    public function _run($request, $xcontext)
    {
        $oemid = $_POST['oemid'];
        $row=processfactorySvc::ins()->del_oem($oemid);
        if ($row) {
            echo "yes";
        }else{
            echo "no";
        }
    }
}

class Action_product_product_addfoundry extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("product/product_addfoundry.html");
    }
}
//执行代工户添加
class Action_product_product_doaddfoundry extends XAction
{
    public function _run($request, $xcontext)
    {
        $oem_info=$_POST;
        $row=processfactorySvc::ins()->add_oem($oem_info);
        if($row){
            return XNext::gotourl($_SERVER['DOMAIN'].'/product/product_foundry.php');
        }else{
            echo "添加失败";
        }
    }
}
//代工户详情
class Action_product_product_foundryinfor extends XAction
{
    public function _run($request, $xcontext)
    {
        $oemid=$_GET['oemid'];
        $one_oem=XDao::query("processfactoryQuery")->one_oem($oemid);
        $xcontext->one_oem=$one_oem;
        return XNext::useTpl("product/product_foundryinfor.html");
    }
}
//代工户修改
class Action_product_product_updatefoundry extends XAction
{
    public function _run($request, $xcontext)
    {
        $oem_info=$_POST;
        $row=processfactorySvc::ins()->update_oem($oem_info);
        if($row){
            return XNext::gotourl($_SERVER['DOMAIN'].'/product/product_foundry.php');
        }else{
            echo "修改失败";
        }
    }
}
/*安排生产*/
class Action_product_arrangeproduct extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = " where statusaudit = 'Y' and prostatus = 'N'";

        $array = array();
        $count = XDao::query("showproductQuery")->count($where);
        //总条数
        $array['total_rows'] = $count['num'];
        //每页多少条

        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):5;

        $page = new Core_Lib_Page($array);

        $page->seach = "&search_name={$search_name}";

        $pro_order_list = XDao::query("showproductQuery")->all_pro_order($where,$page->first_row,$array['list_rows']);
        // var_dump($pro_order_list);
        //声明订单状态的数组
        $array1 = array("Y"=>"审核通过");
        foreach($pro_order_list as $k => $v){
            $pro_order_list[$k]['status']=$array1["{$v['statusaudit']}"];
            //商品名称和信息
            $goodsname  = XDao::query("showproductQuery")->goodsname($v['productid']);

            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $pro_order_list[$k]['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
            //仓库名称和类型
            $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($v['storeid']);
            $pro_order_list[$k]['storename'] = $storeinfo;
            //操作人
            $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
            $pro_order_list[$k]['peoplename']=$etc_people['name'];
        }
        $pages = $page->show(3);

        $xcontext->pages = $pages;
        $xcontext->pro_order_list=$pro_order_list;

        return XNext::useTpl("product/arrangeproduct.html");
    }
}
//安排生产
class Action_product_product_arrange extends XAction
{
    public function _run($request, $xcontext)
    {
        $pro_order_id = intval($_GET['id']);
        $one_pro_list = XDao::query("showproductQuery")->one_pro_info($pro_order_id);
        //仓库名称和类型
        $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($one_pro_list['storeid']);
        $one_pro_list['storeinfo'] = $storeinfo;
        //商品名称和信息
        $goodsname  = XDao::query("showproductQuery")->goodsname($one_pro_list['productid']);
        $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($one_pro_list['productid']);
        $one_pro_list['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
        //商品图片
        $proimg = XDao::query('ListImageQuery')->getimagebyproid($one_pro_list['productid']);
        $one_pro_list['img']=$proimg[0]['url']."/".$proimg[0]['filename'];

        //获取单位名称
        $proflats = XDao::query('addproductQuery')->get_flats($one_pro_list['productid']);
        $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
        $one_pro_list['proflats_name'] = $proflats_name['name'];

        $xcontext->one_pro_list = $one_pro_list;

        return XNext::useTpl("product/product_arrange.html");
    }
}


//请求代工户
class Action_product_product_oneoem extends XAction
{
    public function _run($request, $xcontext)
    {
        $search_content = $_POST['search_content'];

        if(!empty($search_content)){
             $where = "where isdelete = 'N' and name like '%{$search_content}%'";

             $oem_list=XDao::query("processfactoryQuery")->all_oem($where,0,5);

             echo json_encode($oem_list);
        }
    }
}

class Action_product_product_alloem extends XAction
{
    public function _run($request, $xcontext)
    {

             $where = "where isdelete = 'N'";

             $oem_list=XDao::query("processfactoryQuery")->all_oem($where,0,100);

             echo json_encode($oem_list);

    }
}

//调拨原料保存
class Action_product_product_savecateraw extends XAction
{
    public function _run($request, $xcontext)
    {
         //商品id
         $goods_info = $_POST['goods_info'];
         //调出库id
         $store_outid = $_POST['store_outid'];
         //代工户id
         $store_putnum = $_POST['store_putnum'];
         //增减数量
         $number = $_POST['number'];
         //备注
         $remarks = $_POST['remarks'];

         //操作人
         $cookuid     = $_COOKIE['U'];
         $uidarr      = explode('=',$cookuid);
         $uid = $uidarr['2'];//操作人

         //开启事务
         $writer = XDao::dwriter('DWriter');
         $writer->beginTrans();
         foreach($goods_info as $k => $v){
            if(!empty($goods_info[$k]) && !empty($store_outid[$k]) && !empty($store_putnum[$k]) && !empty($number[$k]) && !empty($uid)){

                 $row=AllocaterawSvc::ins()->insertraw($goods_info[$k],$store_outid[$k],$store_putnum[$k],$uid,$number[$k],$remarks[$k]);

                 $list = XDao::query("FstoresyncQuery")->one_raw($store_putnum[$k],$goods_info[$k]);

                 if(empty($list)){

                    $rows=FstoresyncSvc::ins()->insertraw($goods_info[$k],$store_putnum[$k],$number[$k]);

                 }else{

                    $rows=XDao::dwriter("FstoresyncWriter")->changestore_num($number[$k],$store_putnum[$k],$goods_info[$k]);

                 }


                 if(!$row){

                    $writer->rollback();

                 }else{
                     $row1 = XDao::dwriter("StrProductWriter")->modify_store_num($number[$k],$goods_info[$k]);
                     
                     if(!$row1){
                        $writer->rollback();
                     }
                 }
            }else{
                 echo "参数请填写完整";
            }

         }

        if($writer->commit()){
            echo "yes";
        }else{
            echo "no";
        }
    }
}

//生产
class Action_product_produce_start extends XAction
{
    public function _run($request, $xcontext)
    {   
        $pro_info = $_POST;
        //生产单id
        $pro_id = intval($pro_info['proid']);
        //仓库id
        $storeid = intval($pro_info['storeid']);

        $row = ManufactorySvc::ins()->to_product($pro_id);

        //开启事务
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();
        if(!$row){
             $writer->rollback();
        }
        foreach($pro_info['oemid'] as $k => $v){
             //代工户id
             $oemid = $v;
             //数量
             $number = $pro_info['number'][$k];
             //备注
             $remarks = $pro_info['remarks'][$k];

             $row1 = ProcessmanufactorySvc::ins()->addProcess($pro_id,$oemid,$storeid,$number,$remarks);  

             if(!$row1){
                $writer->rollback();
             }
        }
        if($writer->commit()){
            echo "yes";
        }else{
            echo "no";
        }
    }
}

class Action_product_product_listenter extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = " where prostatus = 'Y'";

        if (!empty($_GET['starttime']) && !empty($_GET['stoptime'])) {
            $where .= " and actiondate > '{$_GET['starttime']}' and actiondate < '{$_GET['stoptime']}'";
        }
        if (!empty($_GET['product_status'])) {
            $where .= " and statusreceipt = '{$_GET['product_status']}'";
        }
    
        $array = array();

        $count = XDao::query("showproductQuery")->count($where);
        //总条数
        $array['total_rows'] = $count['num'];
        //每页多少条

        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):5;

        $page = new Core_Lib_Page($array);

        $pro_order_list = XDao::query("showproductQuery")->all_pro_order($where,$page->first_row,$array['list_rows']);

        foreach($pro_order_list as $k => $v){
            $pro_order_list[$k]['status']=$array1["{$v['statusaudit']}"];
            //商品名称和信息
            
            $goodsname  = XDao::query("showproductQuery")->goodsname($v['productid']);
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $pro_order_list[$k]['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';

            //仓库名称和类型
            $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($v['storeid']);
            $pro_order_list[$k]['storename'] = $storeinfo;

            //入库状态
            $storeRec = XDao::query("RecstatusQuery")->showRecstatus($v['statusreceipt']);
            $pro_order_list[$k]['storeRec']=$storeRec;

            //返工状态
            $storeRef = XDao::query("RefstatusQuery")->showRefstatus($v['statusrefund']);
            $pro_order_list[$k]['storeRef']=$storeRef;

            //操作人
            $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
            $pro_order_list[$k]['peoplename']=$etc_people['name'];
        }
        $pages = $page->show(3);

        $xcontext->pages = $pages;
        $xcontext->pro_order_list=$pro_order_list;

        return XNext::useTpl("product/product_listenter.html");
    }
}
class Action_product_product_enterware extends XAction
{
    public function _run($request, $xcontext)
    {
        $pro_order_id = intval($_GET['id']);
        $one_pro_list = XDao::query("showproductQuery")->one_pro_info($pro_order_id);
        //仓库名称和类型
        $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($one_pro_list['storeid']);
        $one_pro_list['storeinfo'] = $storeinfo;
        //商品名称和信息
        $goodsname  = XDao::query("showproductQuery")->goodsname($one_pro_list['productid']);
        $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($one_pro_list['productid']);
        $one_pro_list['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
        //商品图片
        $proimg = XDao::query('ListImageQuery')->getimagebyproid($one_pro_list['productid']);
        $one_pro_list['img']=$proimg[0]['url']."/".$proimg[0]['filename'];

        //获取单位名称
        $proflats = XDao::query('addproductQuery')->get_flats($one_pro_list['productid']);
        $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
        $one_pro_list['proflats_name'] = $proflats_name['name'];

        //入库状态
        $storeRec = XDao::query("RecstatusQuery")->showRecstatus($one_pro_list['statusreceipt']);
        $one_pro_list['storeRec']=$storeRec;

        //返工状态
        $storeRef = XDao::query("RefstatusQuery")->showRefstatus($one_pro_list['statusrefund']);
        $one_pro_list['storeRef']=$storeRef;

        //获取此生产单的所有代工户
        $where = " where productinfoid = {$pro_order_id}";
        $alloem  = XDao::query("processfactoryQuery")->pro_alloem($where);

        foreach($alloem as $k => $v){
             //查出代工户的信息
             $one_oem_info = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);

             $alloem[$k]['oemname'] = $one_oem_info['name'];

        }

        $xcontext->one_pro_list = $one_pro_list;
        $xcontext->alloem = $alloem;
        return XNext::useTpl("product/product_enterware.html");
    }
}
/*
修改生产单的库存
 */
class Action_product_changeproduct_num extends XAction
{
    public function _run($request, $xcontext)
    {
        $info = $_POST;
        //入库数量
        $totalfinish = 0;
        $total = 0;

        //开启事务
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();

        // 声明数组装代工户id
        $profactoryid = array();
        foreach($info['pro_oem'] as $k => $v){
            $pre_oemid = $v;//关联表的自增id
            $to_storenum = $info['to_store'][$k];//入库的数量
            $row = XDao::dwriter("OemproWriter")->updatestore($to_storenum,$pre_oemid);
            $count = XDao::query("ProcessmanufactoryQuery")->count_totalfinish($pre_oemid);
            $totalfinish += $count['totalfinish'];
            $total += $count['total'];
            //代工户id
            $profactoryid[$k]=$count['profactoryid'];

            if(!is_numeric($row)){
             $writer->rollback();
            }
        }

        $cookuid     = $_COOKIE['U'];
        $uidarr      = explode('=',$cookuid);
        $uid = $uidarr['2'];//操作人
        //入库总数量
        $to_storecount = $info['to_storecount'];
        //商品id
        $productid = $info['productid'];
        //仓库id
        $storeid = $info['storeid'];
        //生产单id
        $pro_order_id = $info['pro_order_id'];
        //改变生产单入库状态
        if($totalfinish - $total == 0){
            $row1 = ManufactorySvc::ins()->change_stasreceipt("Y",$pro_order_id);
            
        }else{
            $row1 = ManufactorySvc::ins()->change_stasreceipt("p",$pro_order_id);
            
        }

        //改变实时库存
        $row4 = XDao::dwriter("StrProductWriter")->update_store_num($to_storecount,$productid);

        //出入库数据增加
        $row2 = FprobillSvc::ins()->insert_store_data($storeid,$pro_order_id,$productid,$to_storecount,$uid,"I");
        
        //改变主生产单表的在途数量
        $row5 = XDao::dwriter("ManufactoryWriter")->update_way($to_storecount,$pro_order_id);
        if(!$row1 || !$row2 || !$row4 || !$row5){
           $writer->rollback();
        }
        foreach($profactoryid as $k => $v){
            $to_storenum = $info['to_store'][$k];//入库的数量
            $proid=$v;//代工户id
            // 备注
            $remart = $info['remart'][$k];
            $row3 = FprochangeSvc::ins()->insert_store_data($row2,$proid,$productid,$to_storenum,$remart);

            if(!$row3){
                $writer->rollback();
            }
        }

        if($writer->commit()){
            echo "yes";
        }else{
            echo "no";
        }
        // return XNext::useTpl("product/product_listouter.html");
    }
}

class Action_product_product_listouter extends XAction
{
    public function _run($request, $xcontext)
    {

        $where = " where prostatus = 'Y'";

        if (!empty($_GET['starttime']) && !empty($_GET['stoptime'])) {
            $where .= " and actiondate > '{$_GET['starttime']}' and actiondate < '{$_GET['stoptime']}'";
        }
        if (!empty($_GET['statusrefund'])) {
            $where .= " and statusrefund = '{$_GET['statusrefund']}'";
        }

        $array = array();

        $count = XDao::query("showproductQuery")->count($where);
        //总条数
        $array['total_rows'] = $count['num'];
        //每页多少条

        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):5;

        $page = new Core_Lib_Page($array);

        $page->seach = "&search_name={$search_name}";

        $pro_order_list = XDao::query("showproductQuery")->all_pro_order($where,$page->first_row,$array['list_rows']);

        foreach($pro_order_list as $k => $v){
            $pro_order_list[$k]['status']=$array1["{$v['statusaudit']}"];
            //商品名称和信息
            
            $goodsname  = XDao::query("showproductQuery")->goodsname($v['productid']);
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $pro_order_list[$k]['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';

            //仓库名称和类型
            $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($v['storeid']);
            $pro_order_list[$k]['storename'] = $storeinfo;

            //入库状态
            $storeRec = XDao::query("RecstatusQuery")->showRecstatus($v['statusreceipt']);
            $pro_order_list[$k]['storeRec']=$storeRec;

            //返工状态
            $storeRef = XDao::query("RefstatusQuery")->showRefstatus($v['statusrefund']);
            $pro_order_list[$k]['storeRef']=$storeRef;

            //操作人
            $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
            $pro_order_list[$k]['peoplename']=$etc_people['name'];
        }
        $pages = $page->show(3);

        $xcontext->pages = $pages;
        $xcontext->pro_order_list=$pro_order_list;

        // var_dump($pro_order_list);
        return XNext::useTpl("product/product_listouter.html");
    }
}

class Action_product_product_outerware extends XAction
{
    public function _run($request, $xcontext)
    {

        $pro_order_id = intval($_GET['id']);
        $one_pro_list = XDao::query("showproductQuery")->one_pro_info($pro_order_id);
        //仓库名称和类型
        $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($one_pro_list['storeid']);
        $one_pro_list['storeinfo'] = $storeinfo;
        //商品名称和信息
        $goodsname  = XDao::query("showproductQuery")->goodsname($one_pro_list['productid']);
        $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($one_pro_list['productid']);
        $one_pro_list['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
        //商品图片
        $proimg = XDao::query('ListImageQuery')->getimagebyproid($one_pro_list['productid']);
        $one_pro_list['img']=$proimg[0]['url']."/".$proimg[0]['filename'];

        //获取单位名称
        $proflats = XDao::query('addproductQuery')->get_flats($one_pro_list['productid']);
        $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
        $one_pro_list['proflats_name'] = $proflats_name['name'];

        //入库状态
        $storeRec = XDao::query("RecstatusQuery")->showRecstatus($one_pro_list['statusreceipt']);
        $one_pro_list['storeRec']=$storeRec;

        //返工状态
        $storeRef = XDao::query("RefstatusQuery")->showRefstatus($one_pro_list['statusrefund']);
        $one_pro_list['storeRef']=$storeRef;

        //获取此生产单的所有代工户
        $where = " where productinfoid = {$pro_order_id}";
        $alloem  = XDao::query("processfactoryQuery")->pro_alloem($where);

        foreach($alloem as $k => $v){
             //查出代工户的信息
             $one_oem_info = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);

             $alloem[$k]['oemname'] = $one_oem_info['name'];

        }

        $xcontext->one_pro_list = $one_pro_list;
        $xcontext->alloem = $alloem;

        return XNext::useTpl("product/product_outerware.html");
    }
}

//出库修改库存
class Action_product_product_changestore extends XAction
{
    public function _run($request, $xcontext)
    {

        $info = $_POST;
        //入库数量
        $totalrefund = 0;
        $total = 0;

        //开启事务
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();

        // 声明数组装代工户id
        $profactoryid = array();
        foreach($info['pro_oem'] as $k => $v){
            $pre_oemid = $v;//关联表的自增id

            $to_storenum = $info['to_store'][$k] ? $info['to_store'][$k] : 0;//入库的数量

            $row = XDao::dwriter("OemproWriter")->changestore_num($to_storenum,$pre_oemid);

            $count = XDao::query("ProcessmanufactoryQuery")->count_totalfinish($pre_oemid);

            $totalrefund += $count['totalrefund'];

            $total += $count['total'];

            //代工户id
            $profactoryid[$k]=$count['profactoryid'];

            if(!is_numeric($row)){
             $writer->rollback();
            }
        }

        $cookuid     = $_COOKIE['U'];
        $uidarr      = explode('=',$cookuid);
        $uid = $uidarr['2'];//操作人
        //出库总数量
        $to_storecount = $info['to_storecount'] ? $info['to_storecount'] :0;
        //商品id
        $productid = $info['productid'];
        //仓库id
        $storeid = $info['storeid'];
        //生产单id
        $pro_order_id = $info['pro_order_id'];
        //改变生产单出库状态
        if($totalrefund - $total == 0){
            $row1 = ManufactorySvc::ins()->change_statusrefund("Y",$pro_order_id);

        }else{
            $row1 = ManufactorySvc::ins()->change_statusrefund("p",$pro_order_id);

        }

        //改变实时库存
        $row4 = XDao::dwriter("StrProductWriter")->change_store_num($to_storecount,$productid);

        //出入库数据增加
        $row2 = FprobillSvc::ins()->insert_store_data($storeid,$pro_order_id,$productid,$to_storecount,$uid,"O");
        
        //修改生产单主表的返工数量
        $row5 = XDao::dwriter("ManufactoryWriter")->update_rework($to_storecount,$pro_order_id);

        if(!$row1 || !$row2 || !$row4 || !$row5){
           $writer->rollback();
        }
        
        foreach($profactoryid as $k => $v){
            $to_storenum = $info['to_store'][$k] ? $info['to_store'][$k] : 0;//入库的数量
            $proid=$v;//代工户id
            // 备注
            $remart = $info['remart'][$k];
            $row3 = FprochangeSvc::ins()->insert_store_data($row2,$proid,$productid,$to_storenum,$remart);

            if(!$row3){
             $writer->rollback();
            }
        }

        if($writer->commit()){
            echo "yes";
        }else{
            echo "no";
        }

        // return XNext::useTpl("product/product_outerware.html");
    }
}


class Action_product_product_listtable extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = " where isdelete = 'N'";

        if (!empty($_GET['starttime']) && !empty($_GET['stoptime'])) {
            $where .= " and actiontime > '{$_GET['starttime']}' and actiontime < '{$_GET['stoptime']}'";
        }
        if (!empty($_GET['storetype'])) {
            $where .= " and storetype = '{$_GET['storetype']}'";
        }

        $array = array();

        $count = XDao::query("FprobillQuery")->count($where);
        //总条数
        $array['total_rows'] = $count['num'];
        //每页多少条

        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):5;

        $page = new Core_Lib_Page($array);



        $pro_order_data = XDao::query("FprobillQuery")->all_data($where,$page->first_row,$array['list_rows']);

        foreach($pro_order_data as $k => $v){

            //入库还是出库
            $pro_order_data[$k]['storetype_name'] = XDao::query("StoretypeQuery")->showstatus($v['storetype']);

            //仓库名称和类型
            $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($v['storeid']);
            $pro_order_data[$k]['storename'] = $storeinfo;

            //生产单编号
            $productinfo_number=XDao::query("showproductQuery")->one_pro_info($v['productinfoid']);

            $pro_order_data[$k]['productinfo_number']=$productinfo_number['number'];

            //操作人
            $etc_people = XDao::query("showproductQuery")->act_people($v['userid']);
            $pro_order_data[$k]['peoplename']=$etc_people['name'];

        }
        $pages = $page->show(3);

        $xcontext->pages = $pages;
        $xcontext->pro_order_data=$pro_order_data;

        return XNext::useTpl("product/product_listtable.html");
    }
}

// 请求代工户生产的信息
class Action_product_product_dopro extends XAction
{
    public function _run($request, $xcontext)
    {
        $infoid = $_POST['infoid'];
        //代工户信息
        $fprochange_list = XDao::query("FprobillQuery")->one_info($infoid);

        foreach($fprochange_list as $k => $v){
            $one_oem = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);
            $fprochange_list[$k]['name'] = $one_oem['name'];
        }
        echo json_encode($fprochange_list);

    }
}

class Action_product_product_searchdetail extends XAction
{
    public function _run($request, $xcontext)
    {
        $pro_order_id = intval($_GET['id']);
        $one_pro_list = XDao::query("showproductQuery")->one_pro_info($pro_order_id);
        //仓库名称和类型
        $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($one_pro_list['storeid']);
        $one_pro_list['storeinfo'] = $storeinfo;
        //商品名称和信息
        $goodsname  = XDao::query("showproductQuery")->goodsname($one_pro_list['productid']);
        $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($one_pro_list['productid']);
        $one_pro_list['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';
        //商品图片
        $proimg = XDao::query('ListImageQuery')->getimagebyproid($one_pro_list['productid']);
        $one_pro_list['img']=$proimg[0]['url']."/".$proimg[0]['filename'];

        //获取单位名称
        $proflats = XDao::query('addproductQuery')->get_flats($one_pro_list['productid']);
        $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
        $one_pro_list['proflats_name'] = $proflats_name['name'];

        //入库状态
        $storeRec = XDao::query("RecstatusQuery")->showRecstatus($one_pro_list['statusreceipt']);
        $one_pro_list['storeRec']=$storeRec;

        //返工状态
        $storeRef = XDao::query("RefstatusQuery")->showRefstatus($one_pro_list['statusrefund']);
        $one_pro_list['storeRef']=$storeRef;

        //获取此生产单的所有代工户
        $where = " where productinfoid = {$pro_order_id}";
        $alloem  = XDao::query("processfactoryQuery")->pro_alloem($where);

        foreach($alloem as $k => $v){
             //查出代工户的信息
             $one_oem_info = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);

             $alloem[$k]['oemname'] = $one_oem_info['name'];

        }

        $xcontext->one_pro_list = $one_pro_list;
        $xcontext->alloem = $alloem;
        // echo "<pre>";
        // var_dump($alloem);
        return XNext::useTpl("product/product_searchdetail.html");
    }
}

/*代工库管理*/
class Action_product_product_foundry_manage extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = "where isdelete = 'N'";

        if(!empty($_GET['oemname'])){
            $where .= " and profactoryid = {$_GET['oemname']}";
        }

        $array = array();

        $count = XDao::query("FstoresyncQuery")->count_raw($where);

        // 总条数
        $array['total_rows'] = $count['num'];

        //每页多少条
        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):5;

        $page = new Core_Lib_Page($array);

        $list = XDao::query("FstoresyncQuery")->all_raw($where,$page->first_row,$array['list_rows']);

        $goods_info=array();

        foreach($list as $k => $v){

            //商品名称和信息
            $goodsname_number  = XDao::query("showproductQuery")->goodsname($v['productid']);
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $goods_info[$k]['goodsinfo']=$goodsname_number['name'].'&nbsp;('.$goodsinfo.')';

            //商品编码
            $goods_info[$k]['number']=$goodsname_number['number'];

            //商品图片
            $proimg = XDao::query('ListImageQuery')->getimagebyproid($v['productid']);
            $goods_info[$k]['img']=$proimg[0]['url']."/".$proimg[0]['filename'];
            
            //获取单位名称
            $proflats = XDao::query('addproductQuery')->get_flats($v['productid']);
            $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
            $goods_info[$k]['proflats_name'] = $proflats_name['name'];

            // 数量
            $goods_info[$k]['count'] = $v['total'];

            //代工户名称
            $oem = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);
            $goods_info[$k]['oemname'] = $oem['name'];
        }

        $oem_list=XDao::query("processfactoryQuery")->all_oem("where isdelete = 'N'",0,100);

        $array = array("P"=>"主选代工户","A"=>"备选代工户","E"=>"淘汰代工户");

        foreach($oem_list as $k => $v){
             $oem_list[$k]['name']=$v['name'].' ('.$array[$v['level']].')';
        }

        $pages = $page->show(3);

        $xcontext->goods_info = $goods_info;

        $xcontext->oem_list = $oem_list;

        $xcontext->pages = $pages;

        return XNext::useTpl("product/product_foundry_manage.html");
    }
}

// 按代工户请求商品信息
class Action_product_oemgoodsinfo extends XAction
{
    public function _run($request, $xcontext)
    {
       
        $oem_id = $_GET['oem_id'];

        $goodsname = $_GET['goodsname'];
        
        $productid = $_GET['productid'];

        $where = ",fstoresync where fstoresync.productid = product.productid";

        if(!empty($oem_id)){
            $where .= " and fstoresync.profactoryid={$oem_id}";
        }
        if(!empty($goodsname)){
            $where .= " and product.name like '%{$goodsname}%'";
        }
        if(!empty($productid)){
            $where .= " and product.productid = {$productid}";
        }

        $goodlist = XDao::query("addproductQuery")->goods_list($where);

        $goodsallinfo = array();
        foreach($goodlist as $k => $v){
            //商品名称和信息
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $goodsallinfo[$k]['goodsinfo']=$v['name'].'&nbsp;('.$goodsinfo.')';   
            $goodsallinfo[$k]['total']=$v['total']; 
            $goodsallinfo[$k]['productid']=$v['productid']; 
        }

        echo json_encode($goodsallinfo);

    }
}
//调拨材料明显减库
class Action_product_changestore extends XAction
{
    public function _run($request, $xcontext)
    {

        //代工户id
        $oem_outid = $_POST['oem_outid'];
        //商品id
        $goods_info = $_POST['goods_info'];
        //减库的数量
        $number = $_POST['number'];
        //备注
        $remark = $_POST['remark'];
        //操作人
        $cookuid     = $_COOKIE['U'];
        $uidarr      = explode('=',$cookuid);
        $uid = $uidarr['2'];//操作人

        //开启事务
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();
        foreach($oem_outid as $k => $v){
            if(empty($oem_outid[$k]) || empty($goods_info[$k]) || empty($number[$k])){
                 echo "参数请填写完整";
            }else{
                $row = XDao::dwriter("FstoresyncWriter")->updatestore_num($number[$k],$v,$goods_info[$k]);

                $row1 = FstoredesSvc::ins()->insertlog($goods_info[$k],$oem_outid[$k],$uid,$number[$k],$remark[$k]);

                if(!$row || !$row1){
                   $writer->rollback();
                }
            }
        }

        if($writer->commit()){
            echo "yes";
        }else{
            echo "no";
        }

        // return XNext::useTpl("product/product_allocate.html");
    }
}

/*调拨原料*/
class Action_product_product_allocate extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("product/product_allocate.html");
    }
}
/*调拨记录*/
class Action_product_allocaterecord extends XAction
{
    public function _run($request, $xcontext)
    {

        $where = "where isdelete = 'N'";

        $array = array();

        $count = XDao::query("AllocaterawQuery")->count_raw($where);

        //总条数
        $array['total_rows'] = $count['num'];

        //每页多少条
        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):2;

        $page = new Core_Lib_Page($array);

        $list = XDao::query("AllocaterawQuery")->all_raw($where,$page->first_row,$array['list_rows']);

        $goods_info=array();

        foreach($list as $k => $v){

            //商品名称和信息
            $goodsname  = XDao::query("showproductQuery")->goodsname($v['productid']);
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $goods_info[$k]['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';

            //商品图片
            $proimg = XDao::query('ListImageQuery')->getimagebyproid($v['productid']);
            $goods_info[$k]['img']=$proimg[0]['url']."/".$proimg[0]['filename'];
            
            //获取单位名称
            $proflats = XDao::query('addproductQuery')->get_flats($v['productid']);
            $proflats_name = XDao::query('addproductQuery')->get_flats_name($proflats['unitid']);
            $goods_info[$k]['proflats_name'] = $proflats_name['name'];
            // 数量
            $goods_info[$k]['count'] = $v['total'];
            // 操作人
            $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
            $goods_info[$k]['peoplename']=$etc_people['name'];
            //备注
            $goods_info[$k]['comment']=$v['comment'];

            //领出库房
            $storeinfo = XDao::query("StoreinfoQuery")->showstoreinfo($v['moveoutid']);
            $goods_info[$k]['storename'] = $storeinfo;
            
            //代工库
             $one_oem_info = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);

             $goods_info[$k]['oemname'] = $one_oem_info['name'];

             //时间
             $goods_info[$k]['time']=$v['createtime'];
        }

        $pages = $page->show(3);
        
        $xcontext->goods_info = $goods_info;

        $xcontext->pages = $pages;


        return XNext::useTpl("product/allocaterecord.html");
    }
}
/*代工库手工减库*/
class Action_product_product_reduce extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("product/product_reduce.html");
    }
}
/*减库记录*/
class Action_product_reducerecord extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = "where isdelete = 'N'";

        $array = array();

        $count = XDao::query("FstoredescQuery")->count($where);
        //总条数
        $array['total_rows'] = $count['num'];

        //每页多少条
        
        $array['list_rows'] = isset($_GET['num'])?intval($_GET['num']):5;

        $page = new Core_Lib_Page($array);

        $log_data = XDao::query("FstoredescQuery")->all_row($where,$page->first_row,$array['list_rows']);

        foreach($log_data as $k => $v){
            //代工户
            $one_oem=XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);
            $log_data[$k]['oemname']=$one_oem['name'];

            //商品名称和信息
            $goodsname  = XDao::query("showproductQuery")->goodsname($v['productid']);
            $goodsinfo  = XDao::query("GetFormatStingByProductId")->getformatstr($v['productid']);
            $log_data[$k]['goodsinfo']=$goodsname['name'].'&nbsp;('.$goodsinfo.')';

            //操作人
            $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
            $log_data[$k]['peoplename']=$etc_people['name'];

        }

        $pages = $page->show(3);

        $xcontext->pages = $pages;

        $xcontext->log_data = $log_data;
        return XNext::useTpl("product/reducerecord.html");
    }
}

//查看生产单是否存在
class Action_product_proexist extends XAction
{
    public function _run($request, $xcontext)
    {
        $number = $_POST['pro_number'];
        $where = "where statusaudit = 'Y'";
        $pro_order_list = XDao::query("showproductQuery")->pro_orderlist($where);

        $array=array();
        foreach($pro_order_list as $k => $v){
            $array[]=$v['number'];
        }
        
        if(!in_array($number,$array)){
            echo "no";
        }else{
            echo "yes";
        }
    }
}

/*生产单结算*/
class Action_product_settlement extends XAction
{
    public function _run($request, $xcontext)
    {   
        $bank = XDao::query("financebankQuery")->allfinancebank();
        $xcontext->bank = $bank;
        // 代工户
        $oem_list=XDao::query("processfactoryQuery")->all_oem($where,0,100);
        $xcontext->oem_list = $oem_list;
        return XNext::useTpl("product/settlement.html");
    }
}

//保存结算
class Action_product_savesettle extends XAction
{
    public function _run($request, $xcontext)
    {
        $info = $_POST;
        //生产单num
        $pro_order_num = $info['pro_order_num'];
        //交易帐号
        $account       = $info['account'];
        //代工户id
        $oem_id        = $info['oem'];
        //金额(含税)
        $req_tax       = $info['req_tax'];
        //税率
        $tax_rate      = $info['tax_rate'];
        //税额
        $tax_brow      = $info['tax_brow'];
        //金额(不含税)
        $noreq_tax     = $info['noreq_tax'];
        //备注
        $comment       = $info['comment'];

        // 财务科目(借)
        $subject_lend  = $info['subject_lend'];
        $jie_price     = $info['jie_price'];

        //财务科目(贷)
        $subject_loan  = $info['subject_loan'];
        $dai_price     = $info['dai_price'];

        //操作人
        $cookuid       = $_COOKIE['U'];
        $uidarr        = explode('=',$cookuid);
        $uid           = $uidarr['2'];//操作人

        //生产单是否存在
        $where = "where statusaudit = 'Y'";

        $pro_order_list = XDao::query("showproductQuery")->pro_orderlist($where);
        
        $pro_num = explode(",",$pro_order_num);
        
        $array=array();
        foreach($pro_order_list as $k => $v){
            $array[]=$v['number'];
        }
        
        for ($i = 0; $i<count($pro_num);$i++) {
            if(!in_array($pro_num[$i],$array)){
                echo $pro_num[$i]."生产单编号不对";
                return false;
            }
        }

        // 开启事务
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();

        $rid = FprosettleSvc::ins()->insert_settle_log($pro_order_num,$account,$oem_id,$req_tax,$tax_rate,$tax_brow,$noreq_tax,$comment,$uid);

        //添加交易记录
        $balance = XDao::query("financebankQuery")->findbalance($account);
        $newbalance = $balance['balance']-$req_tax;

        //修改账号主表里的余额
        $resbank = FinancebankSvc::ins()->editbalance($account,$newbalance);

        //账号交易记录
        $bankcomment = "生产单".$pro_order_num."结算";
        $ressbank = BankactactionSvc::ins()->addbankactaction($account,$uid,$banktype='D',$bankcomment,$req_tax,$newbalance);

        if(!$rid || $ressbank != 1){
            $writer->rollback();
        }else{

            $infoid = $rid;

            if(empty($subject_lend) || empty($subject_loan) || empty($jie_price) || empty($dai_price)){

                echo "参数填写不正确";
                return false;
            }else{

                    foreach($subject_lend as $k => $v){

                        if(!empty($v) && !empty($jie_price[$k])){

                            $row = FprofinanceSvc::ins()->insert_link_log('S',$infoid,$v,$direction="B",$jie_price[$k]);

                            $last = XDao::query("subjectbalanceQuery")->findlast($v);

                            if ($last['endingpce']) {

                                $qichu = $last['endingpce'];

                               } else {

                                $qichu = 0;

                               }

                            $qimo = $qichu+$jie_price[$k];

                            $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v,$jie_price[$k],$qichu,$qimo);

                            if( $recode != 1 || !$row){

                                $writer->rollback();

                            }

                        }else{
                            echo "参数不正确!";
                            return false;
                        }
     
                    }
                    
                    foreach($subject_loan as $k1 => $v1){

                        if(!empty($v1) && !empty($dai_price[$k1])){

                            $row1 = FprofinanceSvc::ins()->insert_link_log('S',$infoid,$v1,$direction="I",$dai_price[$k1]);

                            $last = XDao::query("subjectbalanceQuery")->findlast($v1);

                            if ($last['endingpce']) {

                                $qichu = $last['endingpce'];

                               } else {

                                $qichu = 0;

                               }

                            $qimo = $qichu-$dai_price[$k1];

                            $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v1,$dai_price[$k1],$qichu,$qimo);


                            if( $recode != 1 || !$row){

                                $writer->rollback();

                            }

                        }else{
                            echo "参数不正确!";
                            return false;
                        }
                        
                    }

                 }

                if($writer->commit()){
                    echo "yes";
                }else{
                    echo "no";
                }
        }
    }
}

/*生产单结算记录*/
class Action_product_setrecord extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = "where isdelete='N'";

        $settle_log_list = XDao::query("FprosettleQuery")->all_settle_log($where);

        $settle_log=array();

        foreach($settle_log_list as $k => $v){

            //生产单编号
            $settle_log[$k]['productinfo_number'] = $v['productinfoid'];

            //银行帐号
            $bankname = XDao::query("financebankQuery")->findname($v['bankid']);
            $settle_log[$k]['bankname'] = $bankname['name'];

            //财务科目
            $subject_info=XDao::query("FprosettleQuery")->all_subject($v['id'],$type='S');

            foreach($subject_info as $k1 => $v1){
                if($v1['direction'] == "B"){
                    $subjectname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
                    $lead_str .= $subjectname['name'].'('.$v1['price'].')';
                }else if($v1['direction'] == "I"){
                    $subjectname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
                    $lent_str .= $subjectname['name'].'('.$v1['price'].')';
                }
            }
            $settle_log[$k]['lead_str'] = $lead_str;
            $settle_log[$k]['lent_str'] = $lent_str;

            $lead_str="";
            $lent_str="";

             //查出代工户的信息
             $one_oem_info = XDao::query("processfactoryQuery")->one_oem($v['profactoryid']);

             $settle_log[$k]['oemname'] = $one_oem_info['name'];

             //备注
             $settle_log[$k]['comment'] = $v['comment'];

             //操作人
             $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
             $settle_log[$k]['peoplename']=$etc_people['name'];

             //时间
             $settle_log[$k]['actiontime']=$v['actiontime'];

             //金额
             $settle_log[$k]['taxprice']=$v['taxprice'];

        }

        $xcontext->settle_log = $settle_log;

        return XNext::useTpl("product/setrecord.html");
    }
}
/*日常开票*/
class Action_product_dailyopen extends XAction
{
    public function _run($request, $xcontext)
    {

        return XNext::useTpl("product/dailyopen.html");
    }
}

//保存日常开票
class Action_product_save_incoice extends XAction
{
    public function _run($request, $xcontext)
    {

        $info = $_POST;

        //生产单num
        $pro_order_num = $info['pro_order_num'];
        //金额(含税)
        $req_tax       = $info['req_tax'];
        //税率
        $tax_rate      = $info['tax_rate'];
        //税额
        $tax_brow      = $info['tax_brow'];
        //金额(不含税)
        $noreq_tax     = $info['noreq_tax'];
        //备注
        $comment       = $info['comment'];
        //类型
        $stamptype     = $info['radio'];
        //时间
        $actiontime    = $info['time'];

        // 财务科目(借)
        $subject_lend  = $info['subject_lend'];
        $jie_price     = $info['jie_price'];

        //财务科目(贷)
        $subject_loan  = $info['subject_loan'];
        $dai_price     = $info['dai_price'];

        //操作人
        $cookuid       = $_COOKIE['U'];
        $uidarr        = explode('=',$cookuid);
        $uid           = $uidarr['2'];//操作人

        //生产单是否存在
        $where = "where statusaudit = 'Y'";

        $pro_order_list = XDao::query("showproductQuery")->pro_orderlist($where);
        
        $pro_num = explode(",",$pro_order_num);
        
        $array=array();
        foreach($pro_order_list as $k3 => $v3){
            $array[]=$v3['number'];
        }
        
        for ($i = 0; $i<count($pro_num);$i++) {
            if(!in_array($pro_num[$i],$array)){
                echo $pro_num[$i]."生产单编号不对";
                return false;
            }
        }

        // 开启事务
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();

        $rid = MakinvoicelogSvc::ins()->addinvoice_log($stamptype,$pro_order_num,$req_tax,$tax_rate,$tax_brow,$noreq_tax,$uid,$comment,$actiontime);


        if(!$rid){
            $writer->rollback();
        }else{

            $infoid = $rid;

            if(empty($subject_lend) || empty($subject_loan) || empty($jie_price) || empty($dai_price)){

                echo "参数填写不正确";
                return false;

            }else{

                    foreach($subject_lend as $k => $v){

                        if(!empty($v) && !empty($jie_price[$k])){

                            $row = FprofinanceSvc::ins()->insert_link_log('B',$infoid,$v,$direction="B",$jie_price[$k]);

                            $last = XDao::query("subjectbalanceQuery")->findlast($v);

                            if ($last['endingpce']) {

                                $qichu = $last['endingpce'];

                               } else {

                                $qichu = 0;

                               }

                            $qimo = $qichu+$jie_price[$k];

                            $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v,$jie_price[$k],$qichu,$qimo);

                            if( $recode != 1 || !$row){

                                $writer->rollback();

                            }

                        }else{
                            echo "参数不正确!";
                            return false;
                        }
     
                    }
                    
                    foreach($subject_loan as $k1 => $v1){

                        if(!empty($v1) && !empty($dai_price[$k1])){

                            $row1 = FprofinanceSvc::ins()->insert_link_log('B',$infoid,$v1,$direction="I",$dai_price[$k1]);

                            $last = XDao::query("subjectbalanceQuery")->findlast($v1);

                            if ($last['endingpce']) {

                                $qichu = $last['endingpce'];

                               } else {

                                $qichu = 0;

                               }

                            $qimo = $qichu-$dai_price[$k1];

                            $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v1,$dai_price[$k1],$qichu,$qimo);


                            if( $recode != 1 || !$row){

                                $writer->rollback();

                            }

                        }else{
                            echo "参数不正确!";
                            return false;
                        }
                        
                    }

                 }
         }

                if($writer->commit()){
                    echo "yes";
                }else{
                    echo "no";
                }
        echo "<pre>";
        var_dump($_POST);
    }
}

/*日常开票记录*/
class Action_product_dailyrecord extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = "where isdelete='N'";

        $invoice_log_list = XDao::query("FprosettleQuery")->all_invoice($where);

        foreach($invoice_log_list as $k => $v){

            //财务科目
            $subject_info=XDao::query("FprosettleQuery")->all_subject($v['id'],$type='B');

            foreach($subject_info as $k1 => $v1){
                if($v1['direction'] == "B"){
                    $subjectname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
                    $lead_str .= $subjectname['name'].'('.$v1['price'].')';
                }else if($v1['direction'] == "I"){
                    $subjectname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
                    $lent_str .= $subjectname['name'].'('.$v1['price'].')';
                }
            }

            $invoice_log_list[$k]['lead_str'] = $lead_str;
            $invoice_log_list[$k]['lent_str'] = $lent_str;

            $lead_str="";
            $lent_str="";

             //操作人
             $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
             $invoice_log_list[$k]['peoplename']=$etc_people['name'];
        }

        $xcontext->invoice_log_list = $invoice_log_list;

        return XNext::useTpl("product/dailyrecord.html");
    }
}
/*补交税点*/
class Action_product_paytax extends XAction
{
    public function _run($request, $xcontext)
    {
        $bank = XDao::query("financebankQuery")->allfinancebank();
        $xcontext->bank = $bank;
        return XNext::useTpl("product/paytax.html");
    }
}

//保存税点记录
class Action_product_savepaytax extends XAction
{
    public function _run($request, $xcontext)
    {
        $info = $_POST;
        //生产单num
        $pro_order_num = $info['pro_order_num'];
        //金额(含税)
        $req_tax       = $info['req_tax'];
        //税率
        $tax_rate      = $info['tax_rate'];
        //税额
        $tax_brow      = $info['tax_brow'];
        //金额(不含税)
        $noreq_tax     = $info['noreq_tax'];
        //备注
        $comment       = $info['comment'];
        //类型
        $stamptype     = $info['radio'];
        //时间
        $actiontime    = $info['time'];
        //银行帐号
        $account       = $info['account'];

        // 财务科目(借)
        $subject_lend  = $info['subject_lend'];
        $jie_price     = $info['jie_price'];

        //财务科目(贷)
        $subject_loan  = $info['subject_loan'];
        $dai_price     = $info['dai_price'];

        //操作人
        $cookuid       = $_COOKIE['U'];
        $uidarr        = explode('=',$cookuid);
        $uid           = $uidarr['2'];//操作人

        //生产单是否存在
        $where = "where statusaudit = 'Y'";

        $pro_order_list = XDao::query("showproductQuery")->pro_orderlist($where);
        
        $pro_num = explode(",",$pro_order_num);


        // 开启事务
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();

        $rid = MakinvoicelogSvc::ins()->insertinvoice_log($stamptype,$pro_order_num,$req_tax,$tax_rate,$tax_brow,$noreq_tax,$uid,$comment,$actiontime,$account);
        
        //添加交易记录
        $balance = XDao::query("financebankQuery")->findbalance($account);
        $newbalance = $balance['balance']-$req_tax;

        //修改账号主表里的余额
        $resbank = FinancebankSvc::ins()->editbalance($account,$newbalance);

        //账号交易记录
        $bankcomment = "生产单".$pro_order_num."税点记录";
        $ressbank = BankactactionSvc::ins()->addbankactaction($account,$uid,$banktype='D',$bankcomment,$req_tax,$newbalance);

        if(!$rid || $ressbank != 1){
            $writer->rollback();
        }else{

            $infoid = $rid;

            if(empty($subject_lend) || empty($subject_loan) || empty($jie_price) || empty($dai_price)){

                echo "参数填写不正确";
                return false;
            }else{

                    foreach($subject_lend as $k => $v){

                        if(!empty($v) && !empty($jie_price[$k])){

                            $row = FprofinanceSvc::ins()->insert_link_log('B',$infoid,$v,$direction="B",$jie_price[$k]);

                            $last = XDao::query("subjectbalanceQuery")->findlast($v);

                            if ($last['endingpce']) {

                                $qichu = $last['endingpce'];

                               } else {

                                $qichu = 0;

                               }

                            $qimo = $qichu+$jie_price[$k];

                            $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v,$jie_price[$k],$qichu,$qimo);

                            if( $recode != 1 || !$row){

                                $writer->rollback();

                            }

                        }else{
                            echo "参数不正确!";
                            return false;
                        }
     
                    }
                    
                    foreach($subject_loan as $k1 => $v1){

                        if(!empty($v1) && !empty($dai_price[$k1])){

                            $row1 = FprofinanceSvc::ins()->insert_link_log('B',$infoid,$v1,$direction="I",$dai_price[$k1]);

                            $last = XDao::query("subjectbalanceQuery")->findlast($v1);

                            if ($last['endingpce']) {

                                $qichu = $last['endingpce'];

                               } else {

                                $qichu = 0;

                               }

                            $qimo = $qichu-$dai_price[$k1];

                            $recode=SubjectbalanceSvc::ins()->addsubjectbalance($v1,$dai_price[$k1],$qichu,$qimo);


                            if( $recode != 1 || !$row){

                                $writer->rollback();

                            }

                        }else{
                            echo "参数不正确!";
                            return false;
                        }
                        
                    }

                 }

                if($writer->commit()){
                    echo "yes";
                }else{
                    echo "no";
                }
        }
    }
}

/*税点记录*/
class Action_product_taxrecord extends XAction
{
    public function _run($request, $xcontext)
    {
        $where = "where isdelete='N'";

        $invoice_log_list = XDao::query("FprosettleQuery")->all_invoice($where);

        foreach($invoice_log_list as $k => $v){

            //财务科目
            $subject_info=XDao::query("FprosettleQuery")->all_subject($v['id'],$type='B');

            foreach($subject_info as $k1 => $v1){
                if($v1['direction'] == "B"){
                    $subjectname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
                    $lead_str .= $subjectname['name'].'('.$v1['price'].')';
                }else if($v1['direction'] == "I"){
                    $subjectname = XDao::query("financialaccountQuery")->findname($v1['faccountid']);
                    $lent_str .= $subjectname['name'].'('.$v1['price'].')';
                }
            }

            $invoice_log_list[$k]['lead_str'] = $lead_str;
            $invoice_log_list[$k]['lent_str'] = $lent_str;

            $lead_str="";
            $lent_str="";

             //操作人
             $etc_people = XDao::query("showproductQuery")->act_people($v['staffid']);
             $invoice_log_list[$k]['peoplename']=$etc_people['name'];
        }

        $xcontext->invoice_log_list = $invoice_log_list;

        return XNext::useTpl("product/taxrecord.html");
    }
}

/*生产运费*/
class Action_product_yunfei extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("product/yunfei.html");
    }
}
/*运费记录*/
class Action_product_yunrecord extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("product/yunrecord.html");
    }
}
/*运费开票*/
class Action_product_yunopen extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("product/yunopen.html");
    }
}
/*运费开票记录*/
class Action_product_yunopenrecord extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("product/yunopenrecord.html");
    }
}




