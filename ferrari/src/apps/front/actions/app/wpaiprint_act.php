<?php
/**
 * 未排版印刷单
 */
class Action_app_wpaiprint extends XAction
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
		$pbilltotal = XDao::query('WPaiPrintBillQuery')->listwpaiprintbill($search,$page,$pagesize,'total');

		//信息列表
    	$pbilldata = XDao::query('WPaiPrintBillQuery')->listwpaiprintbill($search,$page,$pagesize,'list');

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

		$tpsetstatusarr = array('N'=>'未排版','Y'=>'已排版');
		foreach ($pbilldata as $key => &$value) {
			$value['tpsetstatus'] = $tpsetstatusarr[$value['tpsetstatus']];
		}

		$xcontext->pbilldata = $pbilldata;//列出未排版印刷单信息列表
		$xcontext->pages     = $pagedata;//分页
        return XNext::useTpl("/app/wpaiprint.html");
    }
}

/**
 * 关闭印刷单
 */
class Action_app_closeprintbill extends XAction
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
    		$flag += XDao::dwriter('WPaiPrintWriter')->editwpaiprint($value);
    	}

    	if ($number != $flag) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：closeprintbill Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}