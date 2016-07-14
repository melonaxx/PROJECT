<?php
/**
 *  @param 商品设置
 */
class Action_warehouse_waregoodset extends XAction
{
    public function _run($request, $xcontext)
    {
    	$storeid 		            = $request->storeid;
        $storelist                  = StoreinfoSvc::ins()->getStoreInfo($storeid);
    	$locationid 	            = $request->location;
    	$myloc                      = $this->getMylocList($locationid);
        $myloc['store']['id']       = $storelist['id'];
        $myloc['store']['name']     = $storelist['name'];

        //我的位置
        $xcontext->myloc = $myloc;

        //仓库、区、架、位的ID号串
        $locarr[] = $myloc['store']['id'];
        $locarr[] = $myloc[1]['id'];
        $locarr[] = $myloc[2]['id'];
        $locarr[] = $myloc[0]['id'];
        $locstr = implode(',',$locarr);
        $xcontext->locstr = $locstr;

        //列出商品信息
        $proidlist = XDao::query('ListGoodsBystrlocIdQuery')->listgoodsdata($storeid,$locationid);
        //商品列表
        $proinfolist = array();
        foreach ($proidlist as $key => $value) {
            $goodslist = ProductSvc::ins()->getgoodsbyid($value['productid']);

            $proinfolist[$key]['productid'] = $goodslist['productid'];

            //规格数组
            $formatlist = array();
            //商品的规格ID及规格值ID
            $gformatid = XDao::query('ListFormatQuery')->getformatbyproid($goodslist['productid']);

            $formatlist['formatname'][]         = $gformatid[0]['formatid1'];
            $formatlist['formatname'][]         = $gformatid[0]['formatid2'];
            $formatlist['formatname'][]         = $gformatid[0]['formatid3'];
            $formatlist['formatname'][]         = $gformatid[0]['formatid4'];
            $formatlist['formatname'][]         = $gformatid[0]['formatid5'];
            $formatlist['formatvalue'][]        = $gformatid[0]['valueid1'];
            $formatlist['formatvalue'][]        = $gformatid[0]['valueid2'];
            $formatlist['formatvalue'][]        = $gformatid[0]['valueid3'];
            $formatlist['formatvalue'][]        = $gformatid[0]['valueid4'];
            $formatlist['formatvalue'][]        = $gformatid[0]['valueid5'];

            // 单位ID
            $unitid = $gformatid[0]['unitid'];
            $unitname = XDao::query('ListProunitQuery')->getdwname($unitid);
            $proinfolist[$key]['unit']          = $unitname['name'];

            $proinfolist[$key]['image']         = $goodslist['image'];
            $proinfolist[$key]['storeid']       = $storeid;
            $proinfolist[$key]['locationid']    = $locationid;
            $proinfolist[$key]['name']          = $goodslist['name'];

            /*获取商品的规格串*/
            $formatsrc = new FormatArrayToStrQuery();
            $formatdata = $formatsrc->arrayToStr($formatlist['formatname'],$formatlist['formatvalue']);
            $proinfolist[$key]['format'] = $formatdata;
        }

        $xcontext->goodlist = $proinfolist;
        return XNext::useTpl("warehouse/waregoodset.html");
    }

    private function getMylocList($locationid)
    {
    	// 我的位置数组
    	$myloc = array();
    	$parentlist1 = $this->getLocationParent($locationid);
        $myloc[] = $parentlist1;
        $parentlist2 = $this->getLocationParent($parentlist1['parentid']);
        $myloc[] = $parentlist2;
        $parentlist3 = $this->getLocationParent($parentlist2['parentid']);
        $myloc[] = $parentlist3;
        $parentlist4 = $this->getLocationParent($parentlist3['parentid']);
        return $myloc;
    }
    //查询父类信息
    private function getLocationParent($locationid)
    {
    	$parentid = XDao::query('getkParentByaflQuery')->listparentafl($locationid);
    	$listdata['id'] = $parentid['id'];
    	$listdata['placeno'] = $parentid['placeno'];
    	$listdata['comment'] = $parentid['comment'];
    	$listdata['parentid'] = $parentid['parentid'];
    	return $listdata;
    }
}

/**
 * @brief 通过商品名称获取商品信息
 * @param   仓库ID
 * @retrun
 **/
class Action_warehouse_searchgoodbyname extends XAction
{
    public function _run($request, $xcontext)
    {
        $goodname     = $request->goodname;
        $goodlist = XDao::query('ListProductQuery')->likeProduct($goodname);
        foreach ($goodlist as $key => &$value) {
            // 获取单位ID
            $unitdata = XDao::query('ListUnitByIdQuery')->getunitbyproid($value['productid']);
            // 单位ID
            $unitid = $unitdata[0]['unitid'];
            $unitname = XDao::query('ListProunitQuery')->getdwname($unitid);
            $value['unitname']          = $unitname['name'];
        }

        if (!$goodlist) {
            echo ResultSet::jfail(500, "Server Error：searchgoodbyname Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($goodlist);
        return XNext::nothing();
    }
}

/**
 * @brief 添加商品与仓库的对应关系
 * @param   仓库ID 架、区、位、商品ID
 * @retrun
 **/
class Action_warehouse_addstrrelated extends XAction
{
    public function _run($request, $xcontext)
    {
        $goodidarr      = $request->goodidarr;
        $storedataarr   = $goodidarr['path'];
        $storeinfolist  = explode(',', $storedataarr);

        //去除path串
        array_pop($goodidarr);

        $storeid        = $storeinfolist[0];
        $areaid         = $storeinfolist[1];
        $shelvesid      = $storeinfolist[2];
        $locationid     = $storeinfolist[3];

        foreach ($goodidarr as $key => $value) {
            $isexist = XDao::query('IsGoodInStoreQuery')->goosinstore($storeid,$value,$areaid,$shelvesid,$locationid);
            if ($isexist['total'] > 0) {
                continue;
            }
            $goodres += StrRelatedSvc::ins()->addStoreToProduct($storeid,$value,$areaid,$shelvesid,$locationid);
        }

        if ($goodres <= 0) {
            echo ResultSet::jfail(500, "Server Error：searchgoodbyname Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($goodres);
        return XNext::nothing();
    }
}

/**
 * @brief 删除单个商品与仓库的对应关系
 * @param   仓库ID、 位ID、商品ID
 * @retrun bool
 **/
class Action_warehouse_deloneproformstr extends XAction
{
    public function _run($request, $xcontext)
    {
        $productid      = $request->productid;
        $storeid        = $request->storeid;
        $locationid     = $request->locationid;

        $relatedres = XDao::dwriter('ProRelatedWriter')->delOneProductFromStr($productid,$storeid,$locationid);

        if ($relatedres <= 0) {
            echo ResultSet::jfail(500, "Server Error：deloneproformstr Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($relatedres);
        return XNext::nothing();
    }
}