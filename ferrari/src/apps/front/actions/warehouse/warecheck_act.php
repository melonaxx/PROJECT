<?php
/**
 * @brief 库存盘点
 */
class Action_warehouse_warecheck extends XAction
{
    public function _run($request, $xcontext)
    {
    	//获取数据
    	$page = $request->page;
    	$pagesize = $request->pagesize;
    	$storedata = $request->storeid;

    	//仓库列表信息
    	$storelist = XDao::query('StoreShowQuery')->listStoreInfo();

    	//是否有仓库
    	empty($storedata) ? $storeid = $storelist[0]['id'] : $storeid = $request->storeid;

    	//求总条数
    	$prodatatotal = XDao::query('SeachProByStrIdQuery')->listProductInfo($storeid,$proname,$page,$pagesize,'total');
		$arr['total_rows'] = $prodatatotal[0]['total'];
		$arr['list_rows'] = empty($pagesize) ? Core_Lib_Page::PAGESIZE : $request->pagesize;
		$pagesvc = new Core_Lib_Page($arr);
		$pagedata = $pagesvc->show(3);

    	//列出商品列表信息
    	$prodata = XDao::query('SeachProByStrIdQuery')->listProductInfo($storeid,$proname,$page,$pagesize,'list');

    	//拼接规格值
    	$formatcalss = new GetFormatStingByProductId();
    	foreach ($prodata as $key => &$value) {
    		$formatstr = $formatcalss->getformatstr($value['productid']);
     		$value['format'] 	= $formatstr;
     		$value['path'] 		= ProImage::IMAGEPATH;
    	}

		$xcontext->storedata = $storelist;//仓库列表
		$xcontext->prodata   = $prodata;// 商品列表
		$xcontext->pages   = $pagedata;// 分页列表
        return XNext::useTpl("warehouse/warecheck.html");
    }
}

/**
 * @brief 库存盘点
 */
class Action_warehouse_showcheckbyproidstrid extends XAction
{
    public function _run($request, $xcontext)
    {
		$storeid = $request->storeid;
		$proarr  = $request->productarr;

		//信息对象
		$checkobj = array();
		//format类
		$formatClass = new GetFormatStingByProductId();

		foreach ($proarr as $key => $value) {
			$prodata = XDao::query('ListProInfoByIdQuery')->listProductInfoById($storeid,$value);

			$formatstr = $formatClass->getformatstr($value);
			$prodata['format'] = $formatstr;
			$prodata['path']   = ProImage::IMAGEPATH;

			$checkobj[] = $prodata;
		}

    	if (count($checkobj) < 0) {
            echo ResultSet::jfail(500, "Server Error：showcheckbyproidstrid Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($checkobj);
        return XNext::nothing();
    }
}

/**
 * @brief 添加仓库盘点单
 */
class Action_warehouse_addstorecheckbill extends XAction
{
    public function _run($request, $xcontext)
    {
		$numbers = 0;
		$rows    = 0;
		$checksuccobj = $request->checksuccobj;

		foreach ($checksuccobj as $key => $value) {
			$comment   = $value['comment'];
			$newtotal  = $value['totalreal'];
			$productid = $value['productid'];
			$storeid   = $value['storeid'];
			$total     = $value['total'];
			$oldtotal  = $value['number'];
			$rows++;

			$numbers+=StrCheckSvc::ins()->addStrCheck($comment,$newtotal,$productid,$storeid,$total,$oldtotal);

            //修改实际数量
            $changetotalreal = XDao::dwriter('StrProductWriter')->editTotalReal($storeid,$productid,$newtotal);

		}

    	if ($numbers < $rows) {
            echo ResultSet::jfail(500, "Server Error：addstorecheckbill Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}