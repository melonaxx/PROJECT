<?php
/**
 * @param 发货设置
 */
class Action_warehouse_shippingset extends XAction
{
    public function _run($request, $xcontext)
    {
    	//仓库ID
    	$storeid = $request->storeid;
    	// 显示仓库发货地址信息
    	$shipaddlist = XDao::query('ShowShipAddressQuery')->listShipAddressinfo($storeid);

    	//省市名数组
    	$procityarr = array();
        //省市number数组
        $procitynumberarr = array();
    	foreach ($shipaddlist as $key => $value) {
    		//省
    		$provincenum = $value['stateid'];
    		$provincedata = XDao::query('AreasQuery')->getNameByNumber($provincenum);
    		$proname = $provincedata['name'];
            $pronumber[] = $provincedata['number'];
    		//市
    		$citynum = $value['cityid'];
    		$citydata = XDao::query('AreasQuery')->getNameByNumber($citynum);
    		$cityname = $citydata['name'];
            $citynumber[] = $citydata['number'];
    		//总数组
    		$procityarr[$proname][] = $cityname;
    	}
    	//市数组
    	$cityarrstr = array();
    	foreach ($procityarr as $key => $value) {
    		$citystr = implode(',',$value);
    		$cityarrstr[$key] = $citystr;
    	}

        $xcontext->storeid      = $storeid;
        //省number
        $xcontext->pronumber    = $pronumber;
        // 市number
    	$xcontext->citynumber   = $citynumber;
        //总数组
    	$xcontext->procityarr 	= $procityarr;
        //市字符串数组
    	$xcontext->cityarrstr 	= $cityarrstr;
        //======================地区=========================================
        //存放地区的数组
        $areaarr = array();
        //显示设置地区中的省市
        $prolist = XDao::query('AreasQuery')->getProByLevel();
        foreach ($prolist as $key => $value) {
            $areaarr[$key]['pro'] = array('number'=>$value['number'],'name'=>$value['name']);
            $citydata = XDao::query('AreasQuery')->getCityByLevel($value['number']);
            //市
            $citylist = array();
            foreach ($citydata as $k => $v) {
                $citylist['name'][$k] = $v['name'];
                $citylist['number'][$k] = $v['number'];
            }
            $areaarr[$key]['city'] = $citylist;
        }
        $xcontext->areaarr = $areaarr;
        return XNext::useTpl("warehouse/shippingset.html");
    }
}

//添加仓库发货地址
class Action_warehouse_addshipaddress extends XAction
{
	public function _run($request,$xcontext)
	{
		$shipaddress = $request->procityarr;
		$storeid = $request->storeid;
		$addresscount = XDao::query('getAddressByStoreIdQuery')->getShipNumber($storeid);
		if ($addresscount['addrestotal'] > 0) {
			XDao::dwriter('StrAddressWriter')->delShipAddress($storeid);
		}
		//市的个数
		$cnumber = 0;
		foreach ($shipaddress as $key => $value) {

			foreach ($value as $k => $v) {
				$cnumber++;
				$shipaddnumber += StrAddressSvc::ins()->addShipAddress($storeid,$key,$v);
			}
		}
		if ($cnumber > $shipaddnumber) {
            echo ResultSet::jfail(500, "Server Error：addshipaddress Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess(1);
        return XNext::nothing();
	}
}
