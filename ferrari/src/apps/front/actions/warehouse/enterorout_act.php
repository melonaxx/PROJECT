<?php
/**
 * @breif 手动出入库
 */
class Action_warehouse_enterorout extends XAction
{
    public function _run($request, $xcontext)
    {
    	/*仓库列表*/
    	$storelist = XDao::query('StoreShowQuery')->listStoreInfo();

    	$xcontext->storelist = $storelist;
        return XNext::useTpl("warehouse/enterorout.html");
    }
}

/**
 * @breif 列出带有仓库列表
 *
 * @param
 *
 * @return 仓库列表
 */
class Action_warehouse_liststoredata extends XAction
{
	public function _run($request, $xcontext)
    {
        //查询仓库名称与ID
        $storedata = XDao::query('StoreShowQuery')->listStoreInfo();

    	if (!$storedata) {
            echo ResultSet::jfail(500, "Server Error：liststoredata Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($storedata);
        return XNext::nothing();
    }
}

/**
 * @breif 在仓库下查询单个商品
 *
 * @param storeid
 *
 * @return 商品列表
 */
class Action_warehouse_listprobystoreid extends XAction
{
	public function _run($request, $xcontext)
    {
    	$storeid = $request->storeid;
    	$proname = $request->proname;

        //查询仓库名称与ID
        $productdata = XDao::query('SeachProByStrIdQuery')->listProductInfo($storeid,$proname,'','','');

        //新的数组
    	$newprolist = array();
    	foreach ($productdata as $key => $value) {
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
    		$newprolist[$key]['totalreal'] = $value['totalreal'];
    	}

    	if (!$newprolist) {
            echo ResultSet::jfail(500, "Server Error：listprobystoreid Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($newprolist);
        return XNext::nothing();
    }
}


/**
 * @breif 手动出入库添加
 *
 * @param 商品出入库信息
 *
 * @return bool
 */
class Action_warehouse_addinoutstore extends XAction
{
	public function _run($request, $xcontext)
    {
        //出入库的信息
    	$inoutstrarr = $request->inoutstrarr;

        //计数器
        $conutnum      = 0;
        $strmanualflag = 0;

        //开启事务
        $writer = XDao::dwriter('DWriter');
        $writer->beginTrans();

    	foreach ($inoutstrarr as $key => $value) {
            //仓库ID
            $storeid     = $value['strid'];
            //商品ID
            $productid   = $value['proid'];
            //类型
            $type        = $value['type'];
            //商品数量
            $productnum  = $value['pronum'];
            //备注
            $comment     = $value['comment'];
            //用途类型
            $purposetype = $value['purposetype'];

    		//出库还是入库
    		if ($value['type'] == 'Input')
    		{
                $conutnum+=XDao::query('StrProductWriter')->editNumInOutStore($productnum,$productid,$storeid,'increase');
    		} elseif ($value['type'] == 'Output') {
                $conutnum+=XDao::query('StrProductWriter')->editNumInOutStore($productnum,$productid,$storeid,'decrease');
    		}

            //进行数据记录的插入
            $strmanualflag += StrManualSvc::ins()->addStrManual($storeid,$productid,$type,$purposetype,$productnum,$comment);
    	}

    	if (!$conutnum || !$strmanualflag) {
            $writer->rollback();
            echo ResultSet::jfail(500, "Server Error：addinoutstore Fail");
            return XNext::nothing();
        }

        $writer->commit();
        echo ResultSet::jsuccess($storedata);
        return XNext::nothing();
    }
}