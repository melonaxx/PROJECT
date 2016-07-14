<?php
/**
 * 订单售后列表
 */
class Action_order_saleservice extends XAction
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
		$saletotal = XDao::query('OrderSaleQuery')->listOrderSaleData($search,$page,$pagesize,'total');

		//信息列表
    	$saledata = XDao::query('OrderSaleQuery')->listOrderSaleData($search,$page,$pagesize,'list');

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

		$transporarr = array('K'=>'快递','W'=>'物流','N'=>'无');

    	foreach ($saledata as $key => &$value) {
    		// 快递与物流公司名称
    		if ($value['deltype'] == 'K') {
    			$expdata = XDao::query('ecinfoQuery')->findexp($value['transportid']);
    			$value['transportname'] = $expdata['name'];
    		} else if ($value['deltype'] == 'W') {
    			$transdata = XDao::query('TransportInfoQuery')->getTransById($value['transportid']);
    			$value['transportname'] = $transdata['name'];
    		}

    		//发货方式
			$value['deltype'] = $transporarr[$value['deltype']];

    		//客服
	    	$username = XDao::query("UserQuery")->userone($value['serviceid']);
	    	$username = $username['name'];
	    	$value['servicename'] = $username;
    	}

    	/*售后分类*/
    	$asalecatedata = XDao::query('AfterSaleCateQuery')->listSaleCateData();

    	/*退款帐号*/
    	$bankdata = XDao::query('financebankQuery')->allfinancebank();

		$xcontext->saledata    = $saledata; //印刷信息列表
		$xcontext->pages       = $pagedata; //分页信息
		$xcontext->pagewarning = $pagewarning; //分页信息
		$xcontext->ascatedata  = $asalecatedata; //售后分类信息
		$xcontext->bankdata    = $bankdata; //退款账号
        return XNext::useTpl("order/saleservice.html");
    }
}


/**
 * 获取商品
 */
class Action_order_getprofromorder extends XAction
{
    public function _run($request, $xcontext)
    {
    	$orderid = $request->orderid;
    	//通过orderid获取商品ID
    	$prodata = XDao::query('OrderproductQuery')->findfororderid($orderid);

    	$proarr = array();
    	foreach ($prodata as $key => $value) {
    		$pronf = XDao::query('ListProductQuery')->getProByOId($value['productid']);
			$proarr['data'][$key]['productid'] = $pronf['productid'];
			$proarr['data'][$key]['name']      = $pronf['name'];
			$proarr['data'][$key]['pricesell'] = $pronf['pricesell'];
			$proarr['data'][$key]['image']     = $pronf['image'];
			$pathclass           = new ProImage();
			$proarr['data'][$key]['path']      = $pathclass::IMAGEPATH;

			//规格
			$proformateclass = new GetFormatStingByProductId();
			$pformate = $proformateclass->getformatstr($value['productid']);
			$proarr['data'][$key]['format'] = $pformate;

			//数量
			$proarr['data'][$key]['total'] = $value['total'];
			//合计
			$proarr['sum'] += $value['total'];
    	}

    	if (!$proarr) {
            echo ResultSet::jfail(500, "Server Error：getprofromorder Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($proarr);
        return XNext::nothing();
    }
}


/**
 * 添加售后单信息
 */
class Action_order_addaftersale extends XAction
{
    public function _run($request, $xcontext)
    {
		$saledata    = $request->asaleobj;

		$saletype    = $saledata['saletype'];
		$orderid     = $saledata['orderid'];
		$cateid      = $saledata['cateid'];
		$backbankid  = $saledata['backbankid'];
		$backpay     = $saledata['backpay'];
		$contents    = $saledata['contents'];
		$backexpress = $saledata['backexpress'];
		$number      = $saledata['number'];
		$freight     = $saledata['freight'];
		$backfee     = $saledata['backfee'];

		$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();

    	/*添加售后信息*/
    	$addsaleres = OrderSaleInfoSvc::ins()->addasaleinfo($saletype ,$orderid ,$cateid ,$backbankid ,$backpay ,$contents ,$backexpress ,$number ,$freight ,$backfee);
    	$saleid = $addsaleres['id'];

    	//商品的个数
    	$prosum = count($saledata['salepro']);
    	$proflag = 0;

    	foreach ($saledata['salepro'] as $key => $value) {
			$total     = $value['total'];
			$productid = $value['productid'];
			$price     = $value['price'];
			$asaleid   = $saleid;

			//添加商品售后关联表
			$proflag += ASaleProductSvc::ins()->addasaleproduct($asaleid ,$productid ,$total ,$price);
    	}

    	if (!$saleid && $proflag == $prosum) {
    		$writer->callback();
            echo ResultSet::jfail(500, "Server Error：addsaleservice Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();

    }
}