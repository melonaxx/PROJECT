<?php
/**
 * 印刷单查询
 */
class Action_app_seeprint extends XAction
{
    public function _run($request, $xcontext)
    {
		$p  = $request->page;
		$ps = $request->pagesize;
		$pm = $request->pmethodid;
		$q  = trim($request->search);

		$page      = !empty($p) ? $p : 1;
		$pagesize  = !empty($ps) ? $ps : Core_Lib_Page::PAGESIZE;
		$search    = !empty($q) ? $q : '';
		$pmethodid = !empty($pm) ? $pm : '';

		//信息总条数
		$pbilltotal = XDao::query('AllPrintBillQuery')->listallprintbill($pmethodid,$search,$page,$pagesize,'total');

		//信息列表
    	$pbilldata = XDao::query('AllPrintBillQuery')->listallprintbill($pmethodid,$search,$page,$pagesize,'list');

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

		/*印刷方式列表*/
		$pmethoddata = XDao::query('PrintMethodQuery')->listprintmethod();

		$xcontext->pbilldata   = $pbilldata;//列出未排版印刷单信息列表
		$xcontext->pages       = $pagedata;//分页
		$xcontext->pmethoddata = $pmethoddata;//印刷方式列表
        return XNext::useTpl("/app/seeprint.html");
    }
}