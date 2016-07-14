<?php
/**
 * 称重计费页面
 */
class Action_delivery_weighthandle extends XAction
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
		$weighttotal = XDao::query('OrderWaitListQuery')->listOrderWait($search,$page,$pagesize,'total','C');

		//信息列表
    	$weightdata = XDao::query('OrderWaitListQuery')->listOrderWait($search,$page,$pagesize,'list','C');

		//所在页信息条数大于总条数时
		$pagewarning = 'good';
		if (!empty($page) && !empty($pagesize)) {
			if (count($weightdata) <= 0 && intval($weighttotal[0]['total']) > 0) {
				$pagewarning = 'callback';
			}
		}

		//分页
		$arr['total_rows'] = $weighttotal[0]['total'];
		$arr['list_rows']  = $pagesize;
		$pageclass         = new Core_Lib_Page($arr);
		$pagedata          = $pageclass->show(3);

    	foreach ($weightdata as $key => &$value) {
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

    		//客服
	    	$username = XDao::query("UserQuery")->userone($value['serviceid']);
	    	$username = $username['name'];
	    	$value['servicename'] = $username;
    	}

		$xcontext->weightdata = $weightdata;//扫单验货待处理信息列表
		$xcontext->pages         = $pagedata;//分页
		$xcontext->pagewarning   = $pagewarning;//pagewarning
         return XNext::useTpl("delivery/deliver_weight_handle.html");
    }
}