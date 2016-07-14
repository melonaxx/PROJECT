<?php
/**
 * 全部订单列表
 */
class Action_order_orderquery extends XAction
{
    public function _run($request, $xcontext)
    {
    	$p  = $request->page;
		$ps = $request->pagesize;
		$q  = trim($request->search);

		$page     = !empty($p) ? $p : 1;
		$pagesize = !empty($ps) ? $ps : Core_Lib_Page::PAGESIZE;
		$search   = !empty($q) ? $q : '';

		//信息总条数
		$orderlisttotal = XDao::query('OrderListQuery')->listTotalOrderData($search,$page,$pagesize,'total');

		//信息列表
    	$orderlistdata = XDao::query('OrderListQuery')->listTotalOrderData($search,$page,$pagesize,'list');

		//所在页信息条数大于总条数时
		$pagewarning = 'good';
		if (!empty($page) && !empty($pagesize)) {
			if (count($verifydata) <= 0 && intval($verifytotal[0]['total']) > 0) {
				$pagewarning = 'callback';
			}
		}

		//分页
		$arr['total_rows'] = $orderlisttotal[0]['total'];
		$arr['list_rows']  = $pagesize;
		$pageclass         = new Core_Lib_Page($arr);
		$pagedata          = $pageclass->show(3);

        $orstatuarr = array('N'=>'未审核','P'=>'打单配货','T'=>'条码验货','C'=>'称重计费','F'=>'扫单发货','Y'=>'已发货');

    	foreach ($orderlistdata as $key => &$value) {
    		// 快递与物流公司名称
    		if ($value['deltype'] == 'K') {
    			$expdata = XDao::query('ecinfoQuery')->findexp($value['transportid']);
    			$value['transportname'] = $expdata['name'];
    		} else if ($value['deltype'] == 'W') {
    			$transdata = XDao::query('TransportInfoQuery')->getTransById($value['transportid']);
    			$value['transportname'] = $transdata['name'];
    		}

    		//发货方式
    		if ($value['deltype'] == 'K') {
    			$value['deltype'] = '快递';
    		} else if ($value['deltype'] == 'W') {
    			$value['deltype'] = '物流';
    		}

    		//所在位置
            $value['orstatus'] = $orstatuarr[$value['orstatus']];

    		//客服
	    	$username = XDao::query("UserQuery")->userone($value['serviceid']);
	    	$username = $username['name'];
	    	$value['servicename'] = $username;
    	}

    	/*异常列表信息*/
    	$unusualdata = XDao::query('OrderUnusualQuery')->listOrderUnusualData();

		$xcontext->orderlistdata = $orderlistdata;//全部订单列表信息
		$xcontext->pages         = $pagedata;//分页
		$xcontext->unusualdata   = $unusualdata; //异常信息列表
		$xcontext->pagewarning   = $pagewarning;//pagewarning
        return XNext::useTpl("order/orderquery.html");
    }
}