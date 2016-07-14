<?php
/**
 * 订单中印刷单信息列表
 */
class Action_order_printorder extends XAction
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
		$printtotal = XDao::query('OrderPrintQuery')->listOrderPrintData($search,$page,$pagesize,'total','R','N');

		//信息列表
    	$printdata = XDao::query('OrderPrintQuery')->listOrderPrintData($search,$page,$pagesize,'list','R','N');

		//所在页信息条数大于总条数时
		$pagewarning = 'good';
		if (!empty($page) && !empty($pagesize)) {
			if (count($printdata) <= 0 && intval($printtotal[0]['total']) > 0) {
				$pagewarning = 'callback';
			}
		}

		//分页
		$arr['total_rows'] = $printtotal[0]['total'];
		$arr['list_rows']  = $pagesize;
		$pageclass         = new Core_Lib_Page($arr);
		$pagedata          = $pageclass->show(3);

        $transporarr = array('K'=>'快递','W'=>'物流','N'=>'无');

    	foreach ($printdata as $key => &$value) {
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

		$xcontext->printdata   = $printdata; //印刷信息列表
		$xcontext->pages       = $pagedata; //分页信息
		$xcontext->pagewarning = $pagewarning; //分页信息
        return XNext::useTpl("order/printorder.html");
    }
}

/**
 * 确认印刷信息
 */
class Action_order_doorderprint extends XAction
{
    public function _run($request, $xcontext)
    {
		$flag = 0;
    	$number = 0;
    	//订单ID
    	$odata = $request->printidobj;

		$writer = XDao::dwriter('DWriter');
		//开启事务
		$writer->beginTrans();

    	foreach ($odata as $key => $value) {
    		$number++;
    		$flag += XDao::query('OrderPrintWriter')->doPrintSuccess($value);
    	}

    	if ($number != $flag) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：doorderprint Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}