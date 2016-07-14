<?php
/*========================库区start=================================*/
/**
 * @brief 添加库区
 * @retrun
 **/
class Action_warehouse_addstrlocation extends XAction
{
    public function _run($request, $xcontext)
    {
    	$strdata 			= $request->strdata;
    	$storeid 			= $strdata['storeid'];
    	$placeno 			= $strdata['placenumber'];
    	$locationtype 		= $strdata['locationtype'];
    	$comment 			= $strdata['comment'];
        $storeres = StrLocationSvc::ins()->addStrArea($storeid,$placeno,$locationtype,$comment);
        if (!$storeres) {
            echo ResultSet::jfail(500, "Server Error：AddStorearea Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($storeres);
        return XNext::nothing();
    }
}

/**
 * @brief 获取该仓库单个库区信息
 * @param 	仓库ID
 * @retrun
 **/
class Action_warehouse_getstorearea extends XAction
{
    public function _run($request, $xcontext)
    {
    	$areaid 	= $request->areaid;

        $areares = StrLocationSvc::ins()->getStoreArea($areaid);

        if (!$areares) {
            echo ResultSet::jfail(500, "Server Error：GetStoreArea Fail");
            return XNext::nothing();
        }
        $areadata = $areares->toarray();
        echo ResultSet::jsuccess($areadata);
        return XNext::nothing();
    }
}

/**
 * @brief 获取该仓库所有库区信息
 * @param 	仓库ID
 * @retrun 库区的所有信息
 **/
class Action_warehouse_liststorearea extends XAction
{
    public function _run($request, $xcontext)
    {
    	$storeid 	= $request->storeid;

        $storeres = XDao::query('StoreListQuery')->listStoreData($storeid);

        if (!$storeres) {
            echo ResultSet::jfail(500, "Server Error：ListStoreArea Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($storeres);
        return XNext::nothing();
    }
}

/**
 * @brief 修改该仓库的库区
 *
 * @param   areaid 库区id
 * @param   库区的其它信息。
 *
 * @retrun 库区修改成功后信息
 **/
class Action_warehouse_updatearea extends XAction
{
    public function _run($request, $xcontext)
    {
        $area           = $request->editdata;
        $areaid         = $area['areaid'];
        $areano         = $area['areano'];
        $areacomment    = $area['areacomment'];

        $areares = StrLocationSvc::ins()->updateStoreArea($areaid,$areano,$areacomment);
        if (!$areares) {
            echo ResultSet::jfail(500, "Server Error：updateareaa Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($areares);
        return XNext::nothing();
    }
}
/*========================库区end================================*/

/*========================货架start================================*/

/**
 * @brief 添加货架
 *
 * @param 货架的信息列表
 *
 * @retrun
 **/
class Action_warehouse_addstrshelve extends XAction
{
    public function _run($request, $xcontext)
    {
        $shelvedata         = $request->shelvedata;
        $storeid            = $shelvedata['storeid'];
        $parentid            = $shelvedata['areaid'];
        $placeno            = $shelvedata['placeno'];
        $comment            = $shelvedata['comment'];
        $shelveres = StrLocationSvc::ins()->addStrShelve($storeid,$parentid,$placeno,$comment);
        if (!$shelveres) {
            echo ResultSet::jfail(500, "Server Error：addstrshelve Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($shelveres);
        return XNext::nothing();
    }
}
/**
 * @brief 获取该库区下的所有货架信息
 * @param   库区ID
 * @retrun 货架的所有信息
 **/
class Action_warehouse_liststoreshelve extends XAction
{
    public function _run($request, $xcontext)
    {
        $areaid    = $request->areaid;

        $storeres = XDao::query('StoreListQuery')->listStoreData($areaid);

        if (!$storeres) {
            echo ResultSet::jfail(500, "Server Error：liststoreshelve Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($storeres);
        return XNext::nothing();
    }
}

/**
 * @brief 获取该单个货架信息
 *
 * @param   shelveID 货架ID
 *
 * @retrun
 **/
class Action_warehouse_getstoreshelve extends XAction
{
    public function _run($request, $xcontext)
    {
        $shelveid     = $request->shelveid;

        $shelveres = StrLocationSvc::ins()->getStoreShelve($shelveid);

        if (!$shelveres) {
            echo ResultSet::jfail(500, "Server Error：getstoreshelve Fail");
            return XNext::nothing();
        }
        $areadata = $shelveres->toarray();
        echo ResultSet::jsuccess($areadata);
        return XNext::nothing();
    }
}

/**
 * @brief 修改该单个货架
 *
 * @param   货架的ID
 * @param   货架的其它所有信息。
 *
 * @return bool
 **/
class Action_warehouse_updatestoreshelve extends XAction
{
    public function _run($request, $xcontext)
    {
        $shelve           = $request->editdata;
        $shelveid         = $shelve['shelveid'];
        $shelveno         = $shelve['shelveno'];
        $shelvecomment    = $shelve['shelvecomment'];

        $shelveres = StrLocationSvc::ins()->updateStoreShelve($shelveid,$shelveno,$shelvecomment);
        if (!$shelveres) {
            echo ResultSet::jfail(500, "Server Error：updatestoreshelve Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($shelveres);
        return XNext::nothing();
    }
}
/*========================货架end================================*/
/*========================货位start================================*/

/**
 * @brief 添加货位
 *
 * @param 货位的信息列表
 *
 * @retrun
 **/
class Action_warehouse_addlocationinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $locationdata         = $request->locationdata;
        $storeid            = $locationdata['storeid'];
        $parentid            = $locationdata['shelveid'];
        $placeno            = $locationdata['placeno'];
        $comment            = $locationdata['comment'];
        $locationres = StrLocationSvc::ins()->addStrLocation($storeid,$parentid,$placeno,$comment);
        if (!$locationres) {
            echo ResultSet::jfail(500, "Server Error：addlocationinfo Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($locationres);
        return XNext::nothing();
    }
}
/**
 * @brief 获取单个货位的所有信息
 * @param   货位ID
 * @retrun 货位的所有信息
 **/
class Action_warehouse_listlocationinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $shelveid    = $request->shelveid;

        $storeres = XDao::query('StoreListQuery')->listStoreData($shelveid);

        if (!$storeres) {
            echo ResultSet::jfail(500, "Server Error：listlocationinfo Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($storeres);
        return XNext::nothing();
    }
}

/**
 * @brief 获取该单个货位信息
 *
 * @param   locationID 货位ID
 *
 * @retrun
 **/
class Action_warehouse_getlocationinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $locationid     = $request->locationid;

        $locationres = StrLocationSvc::ins()->getStoreLocation($locationid);

        if (!$locationres) {
            echo ResultSet::jfail(500, "Server Error：getlocationinfo Fail");
            return XNext::nothing();
        }
        $locationdata = $locationres->toarray();
        echo ResultSet::jsuccess($locationdata);
        return XNext::nothing();
    }
}

/**
 * @brief 修改该单个货架
 *
 * @param   货架的ID
 * @param   货架的其它所有信息。
 *
 * @return bool
 **/
class Action_warehouse_updatestorelocation extends XAction
{
    public function _run($request, $xcontext)
    {
        $location           = $request->editdata;
        $locationid         = $location['locationid'];
        $locationno         = $location['locationno'];
        $locationcomment    = $location['locationcomment'];
        $locationres = StrLocationSvc::ins()->updateStoreLocations($locationid,$locationno,$locationcomment);
        if (!$locationres) {
            echo ResultSet::jfail(500, "Server Error：updatestorelocation Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($locationres);
        return XNext::nothing();
    }
}
/*========================货位end================================*/
/**
 * @brief 删除单个库区、货架、货位
 *
 * @param 	salid 区、架、位id
 *
 * @retrun bool
 **/
class Action_warehouse_delsal extends XAction
{
    public function _run($request, $xcontext)
    {
    	$salid 	= $request->salid;
        $count = XDao::query('GetStoreAreaLocationTotalQuery')->AreaLoationTotal($salid);
        if ($count[0]['total'] > 0) {
            echo ResultSet::jfail(401, "has area dont delete!");
            return XNext::nothing();
        }

        $locationres = StrLocationSvc::ins()->delSal($salid);

        if (!$locationres) {
            echo ResultSet::jfail(500, "Server Error：delsal Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($locationres);
        return XNext::nothing();
    }
}