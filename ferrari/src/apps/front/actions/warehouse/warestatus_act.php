<?php
/**
 *  @brief 实时库存
 */
class Action_warehouse_warestatus extends XAction
{
	/*进入仓库状态页面时显示仓库列表*/
    public function _run($request, $xcontext)
    {
    	//当前页数
    	if ($request->page)
    	{
    		$page = $request->page;
    	}else {
    		$page = 1;
    	}

		//页大小
    	if ($request->pagesize)
    	{
    		$pagesize = $request->pagesize;
    	}else {
    		$pagesize = Core_Lib_Page::PAGESIZE;
    	}

    	//查询仓库名称与ID
    	$storedata = XDao::query('StoreShowQuery')->listStoreInfo();

    	//仓库ID
    	if ($request->storeid)
    	{
    		$storeid = $request->storeid;
    	} else {
    		$storeid = $storedata[0]['id'];
    	}

    	//仓库列表
    	$xcontext->storedata = $storedata;

    	//商品状态
    	$goodstares = $request->goodstatus;
    	if (!empty($goodstares) && $goodstares != 'All')
    	{
    		$goodstatus = $request->goodstatus;
    	}

    	//当前页数
    	$pageres = $request->page;
    	if (!empty($pageres))
    	{
    		$page = $pageres;
    	}

    	//页大小
    	$pagesizeres = $request->pagesize;
    	if (!empty($pagesizeres))
    	{
    		$pagesize = $pagesizeres;
    	}

    	/*通过仓库ID获取仓库中商品列表*/
    	if ($storeid)
    	{
    		$prodatas = XDao::query('StrProductQuery')->listProByStrorId($storeid,$goodstatus,$page,$pagesize);
    		$prolist = $prodatas;

	    	/*获取商品总条数*/
	    	$prototal = XDao::query('StrProductQuery')->getStoreGoodsTotal($storeid,$goodstatus);
    	}

    	if ($prototal)
    	{
    		/*分页*/
	    	$arr['total_rows'] = $prototal['total'];
	    	$arr['list_rows'] = $pagesize;
	    	$page = new Core_Lib_Page($arr);
	    	$pages = $page->show(3);
    	}

    	/*通过商品ID获取商品信息*/
    	foreach ($prodatas as $key => $value) {
    		$productid = $value['productid'];
    		$prodata = ProductSvc::ins()->getgoodsbyid($productid);
    		//放商品信息到数组中
    		$proname = $prodata['name'];
    		if (!empty($proname))
    		{
    			$prolist[$key]['name'] 			= $prodata['name'];
    		} else {
    			$prolist[$key]['name']			= '';
    		}

    		$proproductid = $prodata['productid'];
    		if (!empty($proproductid))
    		{
    			$prolist[$key]['productid'] 		= $prodata['productid'];
    		} else {
    			$prolist[$key]['productid']			= '';
    		}

    		$pronumber = $prodata['number'];
    		if (!empty($pronumber))
    		{
    			$prolist[$key]['number'] 			= $prodata['number'];
    		} else {
    			$prolist[$key]['number']			= '';
    		}

    		$proimage = $prodata['image'];
    		if (!empty($proimage))
    		{
    			$prolist[$key]['image'] 		= $prodata['image'];
    		} else {
    			$prolist[$key]['image']			= '';
    		}


    		/*通过商品ID获取商品规格信息*/
    		$proformatedata = XDao::query('ListFormatQuery')->getformatbyproid($productid);

    		unset($formatid);
    		unset($valueid);
    		//规格名称与规格值ID
    		$formatid[] = $proformatedata[0]['formatid1'];
    		$formatid[] = $proformatedata[0]['formatid2'];
    		$formatid[] = $proformatedata[0]['formatid3'];
    		$formatid[] = $proformatedata[0]['formatid4'];
    		$formatid[] = $proformatedata[0]['formatid5'];

    		$valueid[] = $proformatedata[0]['valueid1'];
    		$valueid[] = $proformatedata[0]['valueid2'];
    		$valueid[] = $proformatedata[0]['valueid3'];
    		$valueid[] = $proformatedata[0]['valueid4'];
    		$valueid[] = $proformatedata[0]['valueid5'];

    		/*获取商品的规格串*/
            $formatsrc = new FormatArrayToStrQuery();
            $formatdata = $formatsrc->arrayToStr($formatid,$valueid);
    		if (!empty($formatdata))
    		{
    			$prolist[$key]['format'] 		= $formatdata;
    		} else {
    			$prolist[$key]['format']			= '';
    		}

            //均价
            if ($proformatedata[0]['proformatedata'] == null)
            {
            	$prolist[$key]['pricepurchase'] = '0.00';
            } else {
            	$prolist[$key]['pricepurchase'] = $proformatedata[0]['proformatedata'];
            }

            //实际数量
            $prolist[$key]['totalmoney'] = $prolist['totalreal']*$proformatedata[0]['proformatedata'];

            //图片路径
           	$prolist[$key]['imgpath'] = ProImage::IMAGEPATH;
    	}

    	$totaldata = array();
    	$totaldata['prolist'] = $prolist;
    	$totaldata['page'] = $pages;

    	$xcontext->totaldata = $totaldata;

        return XNext::useTpl("warehouse/warestatus.html");
    }

}


/**
 * @breif 通过商品ID获取拥有该商品的仓库中商品数量
 *
 * @param productid
 *
 * @return   [<仓库中商品的个数>]
 */
class Action_warehouse_showproinstroe extends XAction
{
     public function _run($request, $xcontext)
    {
        //商品ID
        $productid = $request->productid;

        //仓库中商品列表
        $strprodata = XDao::query('StrProductQuery')->getStroreByProId($productid);

        //信息数组
        $storeproarr = array();
        foreach ($strprodata as $key => $value) {
            //仓库
            $storedata = StoreinfoSvc::ins()->getStoreInfo($value['storeid']);
            $striid = $storedata['id'];
            $storeproarr[$striid]['storename'] = $storedata['name'];
            $storeproarr[$striid]['storeid'] = $storedata['id'];

            //商品
            $prodata = XDao::query('ListFormatQuery')->getformatbyproid($value['productid']);
            $totalmoney = $prodata[0]['pricepurchase']*$value['totalreal'];
            $storeproarr[$striid]['totalmoney'] = $totalmoney; //总价
            $storeproarr[$striid]['total'] = $value['totalreal']; //数量
            $storeproarr[$striid]['productid'] = $value['productid'];

            //实际数量  锁定数量    在途数量    生产中数量   可用数量
            $storeproarr[$striid]['totalreal']          = $value['totalreal'];
            $storeproarr[$striid]['totallock']          = $value['totallock'];
            $storeproarr[$striid]['totalway']           = $value['totalway'];
            $storeproarr[$striid]['totalproduction']    = $value['totalproduction'];
            $storeproarr[$striid]['totalavailable']     = $value['totalavailable'];

        }

        if (!$storeproarr) {
            echo ResultSet::jfail(500, "Server Error：showproinstroe Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($storeproarr);
        return XNext::nothing();
    }
}