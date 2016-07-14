<?php
/**
 * 印刷单中其它查询
 */
class Action_app_seeother extends XAction
{
    public function _run($request, $xcontext)
    {
    	$p  = $request->page;
		$ps = $request->pagesize;
		$q  = trim($request->search);

		$switchtype  = $request->switchtype ? $request->switchtype : '';
		$bnumberdate = $request->bnumberdate ? $request->bnumberdate : '';
		$enumberdate = $request->enumberdate ? $request->enumberdate : '';
		$numbercus   = $request->numbercus ? $request->numbercus : '';
		$ifradio     = $request->ifradio ? $request->ifradio : '';
		$bpaydate    = $request->bpaydate ? $request->bpaydate : '';
		$epaydate    = $request->epaydate ? $request->epaydate : '';
		$payunit     = $request->payunit ? $request->payunit : '';

		$datearr = [
			'switchtype'  => $switchtype,
			'bnumberdate' => $bnumberdate,
			'enumberdate' => $enumberdate,
			'numbercus'   => $numbercus,
			'ifradio'     => $ifradio,
			'bpaydate'    => $bpaydate,
			'epaydate'    => $epaydate,
			'payunit'     => $payunit
		];

		$page     = !empty($p) ? $p : 1;
		$pagesize = !empty($ps) ? $ps : Core_Lib_Page::PAGESIZE;
		$search   = !empty($q) ? $q : '';

		//信息总条数
		$pbilltotal = XDao::query('OtherPrintBillQuery')->listotherprintbill($datearr,$search,$page,$pagesize,'total');

		//信息列表
    	$pbilldata = XDao::query('OtherPrintBillQuery')->listotherprintbill($datearr,$search,$page,$pagesize,'list');

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

		/*印刷单位*/
		$punitid = XDao::query('printunitQuery')->findall();

		/*客服列表*/
		$cusdata = XDao::query('UserQuery')->listuserinfo();

		$xcontext->pbilldata = $pbilldata;//列出未排版印刷单信息列表
		$xcontext->pages     = $pagedata;//分页
		$xcontext->punitdata = $punitid;//单位列表
		$xcontext->userdata  = $cusdata;//客服列表
        return XNext::useTpl("/app/seeother.html");
    }
}