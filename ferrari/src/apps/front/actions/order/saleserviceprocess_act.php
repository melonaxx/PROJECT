<?php
/**
 * 订单售后处理
 */
class Action_order_saleserviceprocess extends XAction
{
    public function _run($request, $xcontext)
    {
    	$p  = $request->page;
		$ps = $request->pagesize;
		$q  = trim($request->search);

		$page     = !empty($p) ? $p : 1;
		$pagesize = !empty($ps) ? $ps : Core_Lib_Page::PAGESIZE;
		$search   = !empty($q) ? $q : '';

		// 总条数
		$saletotal = XDao::query('SaleServiceProcessQuery')->listSaleProcess($search,$page,$pagesize,'total');

		//信息列表
    	$saledata = XDao::query('SaleServiceProcessQuery')->listSaleProcess($search,$page,$pagesize,'list');

		//所在页信息条数大于总条数
		$pagewarning = 'good';
		if (!empty($page) && !empty($pagesize)) {
			if (count($saledata) <= 0 && intval($saletotal[0]['total']) > 0) {
				$pagewarning = 'callback';
			}
		}

		//分页
		$arr['total_rows'] = $saletotal[0]['total'];
		$arr['list_rows']  = $pagesize;
		$pageclass         = new Core_Lib_Page($arr);
		$pagedata          = $pageclass->show(3);

		$saletype = array('Refunds'=>'仅退款','Return'=>'退货退款','Exchange'=>'换货','Repair'=>'维修','Delivery'=>'补发货','Unknown'=>'其它未知');

		foreach ($saledata as $key => &$value) {

			//今后类型
			$value['saletype'] = $saletype[$value['saletype']];

    		//客服
	    	$username = XDao::query("UserQuery")->userone($value['staffid']);
	    	$username = $username['name'];
	    	$value['servicename'] = $username;

    	}

    	/*仓库信息*/
    	$store = XDao::query("StoreShowQuery")->listStoreInfo();

		$xcontext->saledata  = $saledata; //售后处理信息列表
		$xcontext->pages     = $pagedata; //分页信息
		$xcontext->storedata = $store; //仓库信息
        return XNext::useTpl("order/saleservice_process.html");
    }
}

/**
 * 关闭售后单
 */
class Action_order_doclosesale extends XAction
{
    public function _run($request, $xcontext)
    {
		$wflag = 0;
    	$wnumber = 0;
    	//售后单ID
    	$sdata = $request->saleid;
		//开启事务
		$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();

    	foreach ($sdata as $key => $value) {
    		$wnumber++;
    		$wflag += XDao::dwriter('OrderSaleInfoWriter')->closeOrderSale($value);
    	}

    	if ($wnumber != $wflag) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：doclosesale Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}

/**
 * 已解决的售后单
 */
class Action_order_solveordersale extends XAction
{
    public function _run($request, $xcontext)
    {
		$wflag = 0;
    	$wnumber = 0;
    	//售后单ID
    	$sdata = $request->saleid;
		//开启事务
		$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();

    	foreach ($sdata as $key => $value) {
    		$wnumber++;
    		$wflag += XDao::dwriter('OrderSaleInfoWriter')->solveOrderSale($value);
    	}

    	if ($wnumber != $wflag) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：solveordersale Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}

/**
 * 通过售后单ID获取对应的商品列表
 */
class Action_order_listprobysaleid extends XAction
{
    public function _run($request, $xcontext)
    {
    	//售后单ID
    	$saleid = $request->saleid;

		$saledata = XDao::query('SaleServiceProcessQuery')->listProBySaleId($saleid);

		$proarr = array();

		foreach ($saledata as $key => $value) {
    		$pronf = XDao::query('ListProductQuery')->getProByOId($value['productid']);

			$proarr[$key]['name']      = $pronf['name'];
			$proarr[$key]['productid'] = $pronf['productid'];
			$proarr[$key]['total']     = $saledata[$key]['total'];
			//规格
			$proformateclass = new GetFormatStingByProductId();
			$pformate = $proformateclass->getformatstr($value['productid']);
			$proarr[$key]['format'] = $pformate;
		}

    	if (!$proarr) {
            echo ResultSet::jfail(500, "Server Error：listprobysaleid Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($proarr);
        return XNext::nothing();
    }
}


/**
 * 售后单的入库操作
 */
class Action_order_saleinstore extends XAction
{
    public function _run($request, $xcontext)
    {
		$wflag = 0;
    	$wnumber = 0;
    	$storeflag = 0;
    	//售后入库信息
    	$instoredata = $request->instoredata;
		$orderid =$instoredata['orderid'];
		$storeid =$instoredata['storeid'];
		$asaleid =$instoredata['saleid'];
		$shopid  =$instoredata['shopid'];
		$comment =$instoredata['comment'];

		//开启事务
		$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();

		// 添加到售后仓库记录表中
    	foreach ($instoredata['prodata'] as $key => $value) {
    		$wnumber++;
			$inedstore =$value['alinstore'];
			$outstore  =$value['waitinstore'];
			$productid =$value['productid'];
    		$wflag += ASaleInStoreSvc::ins()->addasaleinstore($orderid ,$storeid ,$productid ,$asaleid ,$shopid ,$inedstore ,$outstore ,$comment);

    		//仓库中的商品数量进行增加
    		$storeflag += XDao::dwriter('StrProductWriter')->editNumInOutStore($inedstore,$productid,$storeid,'increase');
    	}



    	if ($wnumber != $wflag || $wnumber != $storeflag) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：doclosesale Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}