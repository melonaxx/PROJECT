<?php
/**
 * @brief 库存盘点单列表
 */
class Action_warehouse_inventorylist extends XAction
{
    public function _run($request, $xcontext)
    {
		$page      = $request->page;
		$pagesize  = $request->pagesize;
		$startdate = $request->startdate;
		$enddate   = $request->enddate;
		$storedata = $request->storeid;

		empty($startdate) || $startdate == 'undefined' ? $startdate='' : '';
		empty($enddate) || $enddate     == 'undefined' ? $enddate='' : '';
		empty($page) || $page           == 'undefined' ? $page=1 : '';
		empty($pagesize) || $pagesize   == 'undefined' ? $pagesize=Core_Lib_Page::PAGESIZE : '';

    	//仓库列表信息
    	$storelist = XDao::query('StoreShowQuery')->listStoreInfo();

    	//是否有仓库
    	empty($storedata) ? $storeid = $storelist[0]['id'] : $storeid = $storedata;

    	/*获取信息的总条数*/
    	$datatotal = XDao::query('ShowStoreCheckListQuery')->listCheciInfo($storeid,$startdate,$enddate,$page,$pagesize,'total');
    	//分页
    	$arr['total_rows'] = $datatotal[0]['total'];
		$arr['list_rows'] = $pagesize;
		$pagesvc = new Core_Lib_Page($arr);
		$pagedata = $pagesvc->show(3);

    	$checkdata = XDao::query('ShowStoreCheckListQuery')->listCheciInfo($storeid,$startdate,$enddate,$page,$pagesize,'list');

    	//规格类
    	$formatcalss = new GetFormatStingByProductId();

    	foreach ($checkdata as $key => &$value) {
    		$formatstr = $formatcalss->getformatstr($value['productid']);
			$value['format'] = $formatstr;
			$value['path']   = ProImage::IMAGEPATH;
    	}

		$xcontext->checkdata = $checkdata; //信息列表
		$xcontext->storelist = $storelist;	//仓库列表
		$xcontext->pages     = $pagedata;	//分页
        return XNext::useTpl("warehouse/inventorylist.html");
    }
}