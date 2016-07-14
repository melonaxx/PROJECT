<?php
/**
 * @breif 调拨单信息列表
 */
class Action_warehouse_packinglist extends XAction
{
    public function _run($request, $xcontext)
    {
		$page      = $request->page;//当前页码
		$pagesize  = $request->pagesize;//当前页大小
		$startdate = $request->startdate;//起始时间
		$enddate   = $request->enddate;//结束时间

    	if (!$startdate)
    	{
    		$startdate = '';
    	}
		if (!$enddate)
		{
			$enddate   = '';
		}
		if (!$page)
		{
			$page   = 1;
		}
		if ($pagesize == 'undefined' || !$pagesize)
		{
			$pagesize   = Core_Lib_Page::PAGESIZE;
		}

		/*获取调拨单的总条数*/
		$allocatetotal = XDao::query('StrMoveQuery')->listMovedata($startdate,$enddate,'total',$page,$pagesize);
		$arr['total_rows'] = $allocatetotal[0]['total'];
		$arr['list_rows'] = $pagesize;
		$pagesvc = new Core_Lib_Page($arr);
		$pagedata = $pagesvc->show(3);

		/*获取调拨单列表*/
    	$allocatedata = XDao::query('StrMoveQuery')->listMovedata($startdate,$enddate,'list',$page,$pagesize);

    	/*仓库的ID变为仓库的名称*/
    	foreach ($allocatedata as $key => &$value) {
    		$stroutdata = XDao::query('StoreShowQuery')->findname($value['moveoutid']);
    		$stroutname = $stroutdata['name'];

    		$strindata = XDao::query('StoreShowQuery')->findname($value['moveinid']);
    		$strinname = $strindata['name'];

			$allocatedata[$key]['stroutname'] = $stroutname;
			$allocatedata[$key]['strinname']  = $strinname;

	    	/*调拨类型*/
	    	if ($value['movetype'] == 'Product')
	    	{
	    		$value['movetype'] = '仅产品本身';
	    	} else if ($value['movetype'] == 'Accessory')
	    	{
	    		$value['movetype'] = '和配件一起';
	    	}
    	}

		$xcontext->allocatedata = $allocatedata;
		$xcontext->pages        = $pagedata; //分页

        return XNext::useTpl("warehouse/packinglist.html");
    }
}


/**
 * @breif 通过strmove表中的ID获取单条调拨单信息
 *
 * @param int $id 调拨单的ID
 *
 * @return obj 单条调拨单的信息
 */
class Action_warehouse_getallocatedata extends XAction
{
    public function _run($request, $xcontext)
    {
    	$id = $request->id;//strmoveID
    	$strmovedata = StrMoveSvc::ins()->getStrMove($id);

    	//信息数组
    	$prolist = array();
    	$prolist['total'] = $strmovedata['total'];

    	$proid = $strmovedata['productid'];
    	$formatetostr = new GetFormatStingByProductId();
    	$formatstr = $formatetostr->getformatstr($proid);
    	$prolist['format'] = $formatstr;

    	//商品名
    	$prodata = XDao::query('ListProductQuery')->findProduct($proid);
    	$prolist['proname'] = $prodata['name'];

    	if (!$prolist) {
            echo ResultSet::jfail(500, "Server Error：getallocatedata Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($prolist);
        return XNext::nothing();
    }
}