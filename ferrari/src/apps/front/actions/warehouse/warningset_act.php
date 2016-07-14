<?php
/**
 * 预警设置
 */
class Action_warehouse_warningset extends XAction
{
    public function _run($request, $xcontext)
    {
		$page      = $request->page;
		$pagesize  = $request->pagesize;
		$iswarning = $request->iswarning;
		$storedata = $request->storeid;

		empty($iswarning) || $iswarning == 'undefined' ? $iswarning='' : '';
		empty($page) || $page           == 'undefined' ? $page=1 : '';
		empty($pagesize) || $pagesize   == 'undefined' ? $pagesize=Core_Lib_Page::PAGESIZE : '';

    	/*仓库列表信息*/
    	$storelist = XDao::query('StoreShowQuery')->listStoreInfo();

    	//是否有仓库
    	empty($storedata) ? $storeid = $storelist[0]['id'] : $storeid = $storedata;

    	/*获取信息的总条数*/
    	$warningtotal = XDao::query('StrProductQuery')->strWarningList($storeid,$iswarning,$page,$pagesize,'total');
    	//分页
    	$arr['total_rows'] = $warningtotal[0]['total'];
		$arr['list_rows'] = $pagesize;
		$pagesvc = new Core_Lib_Page($arr);
		$pagedata = $pagesvc->show(3);

    	$warningdata = XDao::query('StrProductQuery')->strWarningList($storeid,$iswarning,$page,$pagesize,'list');

    	//规格类
    	$formatcalss = new GetFormatStingByProductId();
    	foreach ($warningdata as $key => &$value) {
    		$formatstr = $formatcalss->getformatstr($value['productid']);
			$value['format'] = $formatstr;
			$value['path']   = ProImage::IMAGEPATH;
    	}

    	$xcontext->storelist = $storelist; //仓库列表
    	$xcontext->warninglist = $warningdata;	//预警信息列表
    	$xcontext->pages = $pagedata;	//分页信息
        return XNext::useTpl("warehouse/warningset.html");
    }
}

/**
 * 进行仓库预警中上下限的修改
 */
class Action_warehouse_editwarninguplow extends XAction
{
    public function _run($request, $xcontext)
    {
		$warningdata = $request->warning;
		$limitdata   = 0;
		$numflag     = 0;//计数
		foreach ($warningdata as $key => $value) {
			$storeid      = $value['storeid'];
			$strproductid = $value['strproductid'];
			//批量与单个设置
			if ($value['singlelowlimit']) {
				$low = $value['singlelowlimit'];
			} elseif ($value['lowlimit']) {
				$low = $value['lowlimit'];
			}
			if ($value['singleuplimit']) {
				$up = $value['singleuplimit'];
			} elseif ($value['uplimit']) {
				$up = $value['uplimit'];
			}

			$islowup      = $value['islowup'];
			!empty($islowup) ? $islowup = $islowup : $islowup='';
			$numflag++;

		$limitdata += XDao::query('StrProductWriter')->editWarningLimit($storeid,$strproductid,$low,$up,$islowup);
		}
    	if ($limitdata < $numflag) {
            echo ResultSet::jfail(500, "Server Error：showproinstroe Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}