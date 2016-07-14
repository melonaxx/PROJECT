<?php
/**
 *  @brief 实时库存中的商品总汇
 */
class Action_warehouse_goodstatus extends XAction
{
	/*进入仓库状态页面时显示仓库列表*/
    public function _run($request, $xcontext)
    {
    	/**列出所有的商品信息**/
    	$salesstatus = 'All'; //商品状态
    	$productid = '';	//商品ID
    	$pagesize = ProTotalListQuery::PAGESIZE;		//页大小
    	$page = 1;			//当前页数

		$sstatus = $request->salesstatus;
    	if (!empty($sstatus))
    	{
    		$salesstatus = $request->salesstatus;
    	}
    	$proids = $request->productid;
    	if (!empty($proids))
    	{
    		$productid = $request->productid;
    	}
    	$pagesiss = $request->pagesize;
    	if (!empty($pagesiss))
    	{
    		$pagesize = $request->pagesize;
    	}
    	$page = $request->page;
    	if (!empty($page))
    	{
    		$page = $request->page;
    	}

    	/**获取商品的总条数**/
    	$prototal = XDao::query('ProTotalListQuery')->listProductInfo($salesstatus,$productid,$page,$pagesize,ProTotalListQuery::TOTALFLAG);

    	if (count($prototal)>0)
    	{
    		/*分页*/
	    	$arr['total_rows'] = $prototal[0]['total'];
	    	$arr['list_rows'] = $pagesize;
	    	$pagesvc = new Core_Lib_Page($arr);
	    	$pagedata = $pagesvc->show(3);
    	}

    	/*商品列表*/
    	$prodatas = XDao::query('ProTotalListQuery')->listProductInfo($salesstatus,$productid,$page,$pagesize,ProTotalListQuery::PRODUCTINFO);

    	$prolist = $prodatas;
    	//商品规格arr
    	$formatarr = array();
    	foreach ($prodatas as $key=>$value)
    	{
    		unset($formatarr);
    		$formatarr['formatname'][] = $value['formatid1'];
    		$formatarr['formatname'][] = $value['formatid2'];
    		$formatarr['formatname'][] = $value['formatid3'];
    		$formatarr['formatname'][] = $value['formatid4'];
    		$formatarr['formatname'][] = $value['formatid5'];

    		$formatarr['formatvalue'][] = $value['valueid1'];
    		$formatarr['formatvalue'][] = $value['valueid2'];
    		$formatarr['formatvalue'][] = $value['valueid3'];
    		$formatarr['formatvalue'][] = $value['valueid4'];
    		$formatarr['formatvalue'][] = $value['valueid5'];

	    	//规格转化为字符串
	    	$formatlist = XDao::query('FormatArrayToStrQuery')->arrayToStr($formatarr['formatname'],$formatarr['formatvalue']);
	    	$prolist[$key]['format'] = $formatlist;

	    	//图片path
	    	$prolist[$key]['path'] =  ProImage::IMAGEPATH;
    	}

    	$xcontext->prolist = $prolist;
    	//页码
    	$xcontext->pages = $pagedata;

    	return XNext::useTpl("warehouse/goodstatus.html");
    }
}


/**
 *  @brief 通过商品名称或商品编号进行商品搜索
 *
 *  @param 商品NAME 商品编号
 *
 * 	@return 商品信息列表
 */
class Action_warehouse_getgoodnamelist extends XAction
{
    public function _run($request, $xcontext)
    {
    	$pname = $request->proname;
    	if (!empty($pname))
    	{
    		$proname = $request->proname; 		//商品名称
    	} else {
    		$proname = '';
    	}

    	$pronamedata = XDao::query('SeachByNameNumProQuery')->listProductInfo($proname);

    	//新的数组
    	$newprolist = array();
    	foreach ($pronamedata as $key => $value) {
    		unset($formatname);
    		if (!empty($value['formatid1']))
    		{
    			$formatname['name'][] 	= $value['formatid1'];
    		}
    		if (!empty($value['formatid2']))
    		{
    			$formatname['name'][] 	= $value['formatid2'];
    		}
    		if (!empty($value['formatid3']))
    		{
    			$formatname['name'][] 	= $value['formatid3'];
    		}
    		if (!empty($value['formatid4']))
    		{
    			$formatname['name'][] 	= $value['formatid4'];
    		}
    		if (!empty($value['formatid5']))
    		{
    			$formatname['name'][] 	= $value['formatid5'];
    		}

    		if (!empty($value['valueid1']))
    		{
    			$formatname['value'][] 	= $value['valueid1'];
    		}
    		if (!empty($value['valueid2']))
    		{
    			$formatname['value'][] 	= $value['valueid2'];
    		}
    		if (!empty($value['valueid3']))
    		{
    			$formatname['value'][] 	= $value['valueid3'];
    		}
    		if (!empty($value['valueid4']))
    		{
    			$formatname['value'][] 	= $value['valueid4'];
    		}
    		if (!empty($value['valueid5']))
    		{
    			$formatname['value'][] 	= $value['valueid5'];
    		}
    		//规格字符串
    		$format = XDao::query('FormatArrayToStrQuery')->arrayToStr($formatname['name'],$formatname['value']);
    		if (empty($format))
    		{
    			$format = '';
    		}

    		$newprolist[$key]['name'] = $value['name'];
    		$newprolist[$key]['productid'] = $value['productid'];
    		$newprolist[$key]['format'] = $format;
    	}
        if (!$newprolist) {
            echo ResultSet::jfail(500, "Server Error：getgoodnamelist Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($newprolist);
        return XNext::nothing();
    }
}