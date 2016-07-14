<?php
/**
 * 订单售后退货信息列表
 */
class Action_order_saleservicebackwarehouse extends XAction
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
		$saletotal = XDao::query('ASaleProductQuery')->listASaleProduct($search,$page,$pagesize,'total');

		//信息列表
    	$saledata = XDao::query('ASaleProductQuery')->listASaleProduct($search,$page,$pagesize,'list');

		//所在页信息条数大于总条数
		$pagewarning = 'good';
		if (!empty($page) && !empty($pagesize)) {
			if (count($saledata) <= 0 && intval($saletotal[0]['total']) > 0) {
				$pagewarning = 'callback';
			}
		}

		//分页
		$arr['total_rows'] = $saletotal[0]['total'];
		$arr['list_rows']  = $pagesize;
		$pageclass         = new Core_Lib_Page($arr);
		$pagedata          = $pageclass->show(3);


		$xcontext->saledata  = $saledata; //售后入库单信息列表
		$xcontext->pages     = $pagedata; //分页信息
        return XNext::useTpl("order/saleservice_backwarehouse.html");
    }
}