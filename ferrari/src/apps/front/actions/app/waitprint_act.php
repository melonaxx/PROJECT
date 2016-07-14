<?php
/**
 * 待完工印刷单
 */
class Action_app_waitprint extends XAction
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
		$pbilltotal = XDao::query('WaitPrintBillQuery')->listwaitprintbill($search,$page,$pagesize,'total');

		//信息列表
    	$pbilldata = XDao::query('WaitPrintBillQuery')->listwaitprintbill($search,$page,$pagesize,'list');

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
		$comstatusarr   = array('N'=>'未完工','Y'=>'已完工','R'=>'返工');
		foreach ($pbilldata as $key => &$value) {
			$value['tpsetstatus'] = $tpsetstatusarr[$value['tpsetstatus']];
			$value['comstatus']   = $comstatusarr[$value['comstatus']];
		}

		$xcontext->pbilldata = $pbilldata;//列出未排版印刷单信息列表
		$xcontext->pages     = $pagedata;//分页
        return XNext::useTpl("/app/waitprint.html");
    }
}