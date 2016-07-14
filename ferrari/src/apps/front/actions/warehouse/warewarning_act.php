<?php
/**
 * @brief 库存预警
 */
class Action_warehouse_warewarning extends XAction
{
    public function _run($request, $xcontext)
    {
    	$storeid = $request->storeid; //仓库ID
    	/*仓库列表信息*/
    	$storelist = XDao::query('StoreShowQuery')->listStoreInfo();

    	if (!empty($storeid)) {
	    	/*获取数据*/
	    	$warningdata = XDao::query('StrProductQuery')->strWarningList($storeid,'','','','');

	    	//规格类
	    	$formatcalss = new GetFormatStingByProductId();
	    	foreach ($warningdata as $key => &$value) {
	    		$formatstr = $formatcalss->getformatstr($value['productid']);
				$value['format'] = $formatstr;
				$value['path']   = ProImage::IMAGEPATH;
	    	}
			$xcontext->warninglist = $warningdata;	//预警信息列表
    	}

		$xcontext->storelist   = $storelist; //仓库列表
        return XNext::useTpl("warehouse/warewarning.html");
    }
}