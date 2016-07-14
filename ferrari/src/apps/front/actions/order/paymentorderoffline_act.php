<?php
/**
 * 线下待收定单
 */
class Action_order_paymentorderoffline extends XAction
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
		$waitpaytotal = XDao::query('OrderWaitPayQuery')->listWaitPayData($search,$page,$pagesize,'total');

		//信息列表
    	$waitpaydata = XDao::query('OrderWaitPayQuery')->listWaitPayData($search,$page,$pagesize,'list');

		//所在页信息条数大于总条数时
		$pagewarning = 'good';
		if (!empty($page) && !empty($pagesize)) {
			if (count($verifydata) <= 0 && intval($verifytotal[0]['total']) > 0) {
				$pagewarning = 'callback';
			}
		}

		//分页
		$arr['total_rows'] = $waitpaytotal[0]['total'];
		$arr['list_rows']  = $pagesize;
		$pageclass         = new Core_Lib_Page($arr);
		$pagedata          = $pageclass->show(3);

    	foreach ($waitpaydata as $key => &$value) {

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

    		//财务入账状态
    		if ($value['waitpaystatus'] == 'N') {
    			$value['waitpaystatus'] = '未入帐';
    		} else if ($value['waitpaystatus'] == 'A') {
    			$value['waitpaystatus'] = '自动入帐';
    		} else if ($value['waitpaystatus'] == 'H') {
    			$value['waitpaystatus'] = '手动入帐';
    		} else if ($value['waitpaystatus'] == 'P') {
    			$value['waitpaystatus'] = '部分入帐';
    		}

    		//客服
	    	$username = XDao::query("UserQuery")->userone($value['serviceid']);
	    	$username = $username['name'];
	    	$value['servicename'] = $username;
    	}


		$xcontext->waitpaydata = $waitpaydata;//待收款定单列表
		$xcontext->pages       = $pagedata;//分页信息
		$xcontext->pagewarning = $pagewarning; //分页warning
        return XNext::useTpl("order/paymentorder_offline.html");
    }
}


/**
 * 关闭订单
 */
class Action_order_docloseorder extends XAction
{
    public function _run($request, $xcontext)
    {
		$wflag = 0;
    	$wnumber = 0;
    	//订单ID
    	$odata = $request->orderid;

		$writer = XDao::dwriter('DWriter');
		//开启事务
		$writer->beginTrans();

    	foreach ($odata as $key => $value) {
    		$wnumber++;
    		$wflag += XDao::query('WaitPayOrderWriter')->doCloseOrderById($value);
    	}

    	if ($wnumber != $wflag) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：docloseorder Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}