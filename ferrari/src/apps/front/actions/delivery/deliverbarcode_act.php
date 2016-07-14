<?php
/**
 * 扫码验货操作页面
 */
class Action_delivery_deliverbarcode extends XAction
{
    public function _run($request, $xcontext)
    {
    	//操作者
		$cookuid  = $_COOKIE['U'];
		$uidarr   = explode('=',$cookuid);
		$username = XDao::query("UserQuery")->userone($uidarr['2']);
		$username = $username['name'];

		$xcontext->username = $username; //操作者信息
        return XNext::useTpl("delivery/deliver_barcode.html");
    }
}

/**
 * 通过订单ID或是快递单ID查询订单商品信息
 */
class Action_delivery_getoprodata extends XAction
{
    public function _run($request, $xcontext)
    {
    	//订单或快递编号及类型
		$prodata    = $request->prodata;
		$numberid   = $prodata['numberid'];
		$searchtype = $prodata['searchtype'];
		//判断是那种查询方式
		if ($searchtype == 'order') {

			$orderdata = XDao::query('OrderActionQuery')->listOrderByordAction($numberid);

		} else if ($searchtype == 'express') {

			$orderdata = XDao::query('OrderActionQuery')->listOrderByexpAction($numberid);
		}

		//买家信息拼接
		$orderdata['cusname'] = $orderdata['cusname'].','.$orderdata['mobile'];

		// 快递与物流公司名称
		if ($orderdata['transtype'] == 'K') {
			$expdata = XDao::query('ecinfoQuery')->findexp($orderdata['transportid']);
			$orderdata['transportname'] = $expdata['name'].','.$orderdata['transportid'];
		} else if ($orderdata['transtype'] == 'W') {
			$transdata = XDao::query('TransportInfoQuery')->getTransById($orderdata['transportid']);
			$orderdata['transportname'] = $transdata['name'].','.$orderdata['transportid'];
		}

		/*商品信息*/
		$orderid = $orderdata['orderid'];
		$productdata = XDao::query('OrderActionQuery')->listProByOidAction($orderid);

		$proformateclass = new GetFormatStingByProductId();
		foreach ($productdata as $key => &$value) {
			//规格
			$pformate = $proformateclass->getformatstr($value['productid']);
			$value['format'] = $pformate;
		}

		$resultdata = ['orderdata'=>$orderdata,'productdata'=>$productdata];

    	if (!$resultdata) {
            echo ResultSet::jfail(500, "Server Error：getoprodata Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($resultdata);
        return XNext::nothing();
    }
}