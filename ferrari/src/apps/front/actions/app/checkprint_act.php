<?php
/**
 * 显示印刷单信息列表
 */
class Action_app_checkprint extends XAction
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
		$pbilltotal = XDao::query('PrintBillQuery')->listprintbill($search,$page,$pagesize,'total');

		//信息列表
    	$pbilldata = XDao::query('PrintBillQuery')->listprintbill($search,$page,$pagesize,'list');

    	//分页
		$arr['total_rows'] = $pbilltotal[0]['total'];
		$arr['list_rows']  = $pagesize;
		$pageclass         = new Core_Lib_Page($arr);
		$pagedata          = $pageclass->show(3);

		//所在页信息条数大于总条数时
		$pagewarning = 'good';
		if (!empty($page) && !empty($pagesize)) {
			if (count($verifydata) <= 0 && intval($verifytotal[0]['total']) > 0) {
				$pagewarning = 'callback';
			}
		}

    	foreach ($pbilldata as $key => $value) {
    		$pmethod = XDao::query('PrintMethodQuery')->listpmethod($value['printmethodid']);
    	}

		$xcontext->pbilldata = $pbilldata;//列出印刷单信息列表
		$xcontext->pages     = $pagedata;//分页
        return XNext::useTpl("/app/checkprint.html");
    }
}

/**
 * 确认审核印刷单信息
 */
class Action_app_checkprintbill extends XAction
{
    public function _run($request, $xcontext)
    {
		$flag = 0;
    	$number = 0;
    	//印刷单ID
    	$pdata = $request->pbillid;

		$writer = XDao::dwriter('DWriter');
		//开启事务
		$writer->beginTrans();

    	foreach ($pdata as $key => $value) {
    		$number++;
    		$flag += XDao::dwriter('PrintBillWriter')->checkpbill($value);
    	}

    	if ($number != $flag) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：checkprintbill Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}