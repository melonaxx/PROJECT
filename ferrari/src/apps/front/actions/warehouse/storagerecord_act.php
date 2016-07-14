<?php
/**
 * @brief 手动出入库的记录列表
 */
class Action_warehouse_storagerecord extends XAction
{
    public function _run($request, $xcontext)
    {
        /*仓库列表*/
        $storedata = XDao::query('StoreShowQuery')->listStoreInfo();

    	//仓库ID
    	if (!$request->storeid)
    	{
    		$storeid = $storedata[0]['id'];
    	} else {
    		$storeid = $request->storeid;
    	}

    	//页大小
    	$pagesizeres = $request->pagesize;
    	if ($pagesizeres == 'undefined' || !$pagesizeres)
    	{
    		$pagesize = Core_Lib_Page::PAGESIZE;
    	} else {
    		$pagesize = $pagesizeres;
    	}

    	//当前页数
    	if (!$request->page)
    	{
    		$page = 1;
    	} else {
    		$page = $request->page;
    	}

    	/*获取总条数*/
    	$totaldata = XDao::query('StrManualQuery')->totalManual($storeid);
    	$totalnum = $totaldata['total'];

    	/*分页*/
    	$arr['total_rows'] = $totalnum;
        $arr['list_rows'] = isset($_GET['pagesize'])?$_GET['pagesize']:Core_Lib_Page::PAGESIZE;
        $ppage = new Core_Lib_Page($arr);
        $pages = $ppage->show(3);

    	$manuallist = XDao::query('StrManualQuery')->listManualInOut($storeid,$page,$pagesize);

    	//分配数组
    	$datalist = array();

    	foreach ($manuallist as $key => $value) {
    		//仓库名称
    		$strdata = XDao::query('StoreShowQuery')->findname($value['storeid']);
    		$storename = $strdata['name'];

    		//出库类型
    		if ($value['type'] == 'Input')
    		{
    			$type = '入库';
    		} else if ($value['type'] == 'Output')
    		{
    			$type = '出库';
    		}

    		//用途类型
    		if ($value['purposetype'] == 'M')
    		{
    			$purposetype = '生产';
    		} else if ($value['purposetype'] == 'P')
    		{
    			$purposetype == '进货';
    		} else if ($value['purposetype'] == 'L')
    		{
    			$purposetype == '盘点';
    		} else if ($value['purposetype'] == 'S')
    		{
    			$purposetype == '销售';
    		} else if ($value['purposetype'] == 'W')
    		{
    			$purposetype == '损耗';
    		}

            $datalist[$key]['type']        = $type;
            $datalist[$key]['purposetype'] = $purposetype;
            $datalist[$key]['createtime']  = $value['createtime'];
            $datalist[$key]['storeid']     = $storename;
            $datalist[$key]['productid']   = $value['productid'];
            $datalist[$key]['total']       = $value['total'];
            $datalist[$key]['staffid']     = $value['staffid'];
            $datalist[$key]['comment']     = $value['comment'];
            $datalist[$key]['id']          = $value['id'];

    	}

		$xcontext->storedata = $storedata;
		$xcontext->datalist  = $datalist;
		$xcontext->pages     = $pages;		//分页
		return XNext::useTpl("warehouse/storagerecord.html");
    }
}


/**
 * @brief 显示出入库记录详情
 * @param int $productid 商品ID
 * @param int $id strmanual表ID
 * @param int $storeid 仓库ID
 *
 * @return obj 信息列表
 */
class Action_warehouse_listmanualdata extends XAction
{
    public function _run($request, $xcontext)
    {
        $productid = $request->productid;
        $id        = $request->id;
        $storeid   = $request->storeid;

        //总数组
        $manualdata = array();

        //查询strmanual表信息
        $manualist = StrManualSvc::ins()->getmanualbyid($id);

        //出入库
        if ($manualist['type'] == 'Input')
        {
            $manualdata['type'] = '入库';
        } else if ($manualist['type'] == 'Output') {
            $manualdata['type'] = '出库';
        }

        //类型
        if ($manualist['purposetype'] == 'M')
        {
            $manualdata['purposetype'] = '生产';
        } else if ($manualist['purposetype'] == 'P')
        {
            $manualdata['purposetype'] = '进货';
        } else if ($manualist['purposetype'] == 'L')
        {
            $manualdata['purposetype'] = '盘点';
        } else if ($manualist['purposetype'] == 'S')
        {
            $manualdata['purposetype'] = '销售';
        } else if ($manualist['purposetype'] == 'W')
        {
            $manualdata['purposetype'] = '损耗';
        }

        //仓库
        $storedata = XDao::query('StoreShowQuery')->findname($manualist['storeid']);
        $storename = $storedata['name'];
        $manualdata['storename'] = $storename;

        //日期
        $manualdata['date']    = $manualist['createtime'];

        //操作人
        $manualdata['staffid'] = $manualist['staffid'];

        //备注
        $manualdata['comment'] = $manualist['comment'];

        //商品数量
        $manualdata['total']   = $manualist['total'];

        //商品名称
        $prodata = XDao::query('ListProductQuery')->findProduct($manualist['productid']);
        $proname = $prodata['name'];
        $manualdata['proname'] = $proname;

        //商品规格串
        $formatdata = XDao::query('ListFormatQuery')->getformatbyproid($manualist['productid']);
        $formatname[]  = $formatdata[0]['formatid1'];
        $formatname[]  = $formatdata[0]['formatid2'];
        $formatname[]  = $formatdata[0]['formatid3'];
        $formatname[]  = $formatdata[0]['formatid4'];
        $formatname[]  = $formatdata[0]['formatid5'];

        $formatvalue[] = $formatdata[0]['valueid1'];
        $formatvalue[] = $formatdata[0]['valueid2'];
        $formatvalue[] = $formatdata[0]['valueid3'];
        $formatvalue[] = $formatdata[0]['valueid4'];
        $formatvalue[] = $formatdata[0]['valueid5'];

        $formateclss   = new FormatArrayToStrQuery();
        $formatstr     = $formateclss->arrayToStr($formatname,$formatvalue);
        $manualdata['formatstr'] = $formatstr;

        if (!$manualdata) {
            echo ResultSet::jfail(500, "Server Error：listmanualdata Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($manualdata);
        return XNext::nothing();
    }
}
