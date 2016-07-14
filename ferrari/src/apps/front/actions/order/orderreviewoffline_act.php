<?php
/**
 * 订单审核页面显示
 */
class Action_order_orderreviewoffline extends XAction
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
		$verifytotal = XDao::query('OrderVerifyQuery')->listverifydata($search,$page,$pagesize,'total');

		//信息列表
    	$verifydata = XDao::query('OrderVerifyQuery')->listverifydata($search,$page,$pagesize,'list');

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
    		}

    		//客服
	    	$username = XDao::query("UserQuery")->userone($value['serviceid']);
	    	$username = $username['name'];
	    	$value['servicename'] = $username;
    	}

    	/*异常列表信息*/
    	$unusualdata = XDao::query('OrderUnusualQuery')->listOrderUnusualData();

    	/*快递信息列表*/
    	$expressdata = XDao::query('ecinfoQuery')->allexp();

		$xcontext->verifydata  = $verifydata; //订单信息
		$xcontext->pages       = $pagedata; //分页
		$xcontext->pagewarning = $pagewarning; //分页warning
		$xcontext->unusualdata = $unusualdata; //异常信息列表
		$xcontext->expressdata = $expressdata; //快递信息列表
        return XNext::useTpl("order/orderreview_offline.html");
    }
}

/**
 * 订单审核操作
 */
class Action_order_doorderverify extends XAction
{
    public function _run($request, $xcontext)
    {
    	$vflag = 0;
    	$vnumber = 0;
    	//订单ID
    	$odata = $request->orderid;

		$writer = XDao::dwriter('DWriter');
		//开启事务
		$writer->beginTrans();

    	foreach ($odata as $key => $value) {
    		$vnumber++;
    		$vflag += XDao::query('OrderVerifyWriter')->doVerifySuccess($value);
    	}

    	if ($vnumber != $vflag) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：doorderverify Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}

/**
 * 订单异常处理操作
 */
class Action_order_doorderunusual extends XAction
{
    public function _run($request, $xcontext)
    {
		$unusualres   = 0;
		$unusualnumber = 0;
    	//异常信息
		$unusualdata    = $request->orderid;
		$unusualid      = $unusualdata['unusualid'];
		$unusualcomment = $unusualdata['unusualcomment'];
    	array_pop($unusualdata);
    	array_pop($unusualdata);

		$writer = XDao::dwriter('DWriter');
		//开启事务
		$writer->beginTrans();

    	foreach ($unusualdata as $key => $value) {
    		$unusualnumber++;
    		$unusualres += XDao::query('OrderVerifyWriter')->doUnusualByOId($value,$unusualid,$unusualcomment);
    	}

    	if ($unusualres != $unusualnumber) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：doorderunusual Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}

/**
 * 订单批量修改快递
 */
class Action_order_doorderexpress extends XAction
{
    public function _run($request, $xcontext)
    {
		$expressres   = 0;
		$expressnumber = 0;
    	//异常信息
		$expressdata    = $request->orderid;
		$expressid      = $expressdata['expressid'];
    	array_pop($expressdata);

		$writer = XDao::dwriter('DWriter');
		//开启事务
		$writer->beginTrans();

    	foreach ($expressdata as $key => $value) {
    		$expressnumber++;
    		$expressres += XDao::dwriter('OrderDeliverWriter')->editDeliverByOId($value,$expressid);
    	}

    	if ($expressres != $expressnumber) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：doorderexpress Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}