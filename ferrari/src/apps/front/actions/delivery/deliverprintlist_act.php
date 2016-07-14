<?php
/**
 * 打单配货
 */
class Action_delivery_deliverprintlist extends XAction
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
		$verifytotal = XDao::query('DeliverPrintQuery')->listdeliverprint($search,$page,$pagesize,'total');

		//信息列表
    	$verifydata = XDao::query('DeliverPrintQuery')->listdeliverprint($search,$page,$pagesize,'list');

		//所在页信息条数大于总条数时
		$pagewarning = 'good';
		if (!empty($page) && !empty($pagesize)) {
			if (count($verifydata) <= 0 && intval($verifytotal[0]['total']) > 0) {
				$pagewarning = 'callback';
			}
		}

		//分页
		$arr['total_rows'] = $verifytotal[0]['total'];
		$arr['list_rows']  = $pagesize;
		$pageclass         = new Core_Lib_Page($arr);
		$pagedata          = $pageclass->show(3);

    	foreach ($verifydata as $key => &$value) {

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
    		} else if ($value['deltype'] == 'N') {
    			$value['deltype'] = '无';
    		}

    		//客服
	    	$username = XDao::query("UserQuery")->userone($value['serviceid']);
	    	$username = $username['name'];
	    	$value['servicename'] = $username;
    	}

		$xcontext->verifydata  = $verifydata; //订单信息
		$xcontext->pages       = $pagedata; //分页
		$xcontext->pagewarning = $pagewarning; //分页warning
        return XNext::useTpl("delivery/deliver_printlist.html");
    }
}