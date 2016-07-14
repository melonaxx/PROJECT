<?php

/**
 *  @brief 仓库设置页面信息
 *  @retrun
 * */
class Action_warehouse_warehouse extends XAction
{
    public function _run($request, $xcontext)
    {
        $storeinfo = XDao::query('StoreShowQuery')->listStoreInfo();
        $defstore = XDao::query('StoreShowQuery')->defaultstore();
        $xcontext->storeinfo = $storeinfo;
        $xcontext->defstore = $defstore;
        return XNext::useTpl("warehouse/warehouse.html");
    }
}

/**
 * @brief 添加仓库
 *
 * @param
 *
 * @return
 * */
class Action_warehouse_addwarehouse extends XAction
{
    public function _run($request,$xcontext)
    {
        $storedata = $request->attr;

        $defaultstroe   = $storedata['defaultstroe'];
        $storetype      = $storedata['storetype'];
        $storenumber    = $storedata['storenumber'];
        $storename      = $storedata['storename'];
        $contactname    = $storedata['contactname'];
        $moblie         = $storedata['moblie'];
        $telphone       = $storedata['telphone'];
        $loc_province   = $storedata['loc_province'];
        $loc_city       = $storedata['loc_city'];
        $loc_town       = $storedata['loc_town'];
        $storeaddress   = $storedata['storeaddress'];
        $describes      = $storedata['describes'];

        //判断是不是默认仓库并修改默认仓库
        $adddefstore   = $storedata['adddefstore'];
        $defaulstore = 'Normal';
        if ($adddefstore == 1) {
            //取消原先的默认仓库
            XDao::query('StoreShowQuery')->editdefaultstore();
            $defaulstore = 'Default';
        }
        $storeres = StoreinfoSvc::ins()->addStoreInfo($storetype ,$storenumber ,$storename ,$contactname ,$moblie ,$telphone ,$loc_province,$loc_city,$loc_town,$storeaddress,$describes,$defaulstore);

        if (!$storeres) {
            echo ResultSet::jfail(500, "Server Error：AddStoreinfo Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}

/**
 * @brief 查询单个仓库信息
 *
 * @param storeid  仓库ID
 *
 * @return  单个仓库信息
 * */
class Action_warehouse_getstoreinfo extends XAction
{
    public function _run($request,$xcontext)
    {
        $storeid = $request->storeid;
        $storeres = StoreinfoSvc::ins()->getStoreInfo($storeid);

        if (!$storeres) {
            echo ResultSet::jfail(500, "Server Error：GetStoreinfo Fail");
            return XNext::nothing();
        }

        $storedata = $storeres->toarray();
        echo ResultSet::jsuccess(array('data'=>$storedata));
        return XNext::nothing();
    }
}

/**
 * @brief 修改单个仓库信息
 *
 * @param 仓库各个参数
 *
 * @return
 **/
class Action_warehouse_editstoreinfo extends XAction
{
    public function _run($request,$xcontext)
    {

        $storeif        = $request->strinfo;
        $editdefstore   = $storeif['editdefstore'];
        $strid          = $storeif['id'];
        $storetype      = $storeif['strtype'];
        $storenumber    = $storeif['storenumber'];
        $storename      = $storeif['storename'];
        $contactname    = $storeif['contactname'];
        $moblie         = $storeif['mobile'];
        $telphone       = $storeif['telphone'];
        $loc_province   = $storeif['prov'];
        $loc_city       = $storeif['city1'];
        $loc_town       = $storeif['countys'];
        $storeaddress   = $storeif['address'];
        $describes      = $storeif['describes'];

        //是否是默认仓库
        if ($editdefstore == 1) {
            //取消原先的默认仓库
            $defstrid = XDao::query('StoreShowQuery')->getdefstoreid();
            StoreinfoSvc::ins()->editdefstoretonor($defstrid['id']);
            // 设置本仓库为默认仓库
            StoreinfoSvc::ins()->editdefstoretodef($strid);
        }

        $storeres = StoreinfoSvc::ins()->updateStoreInfo($strid,$storetype ,$storenumber ,$storename ,$contactname ,$moblie ,$telphone ,$loc_province,$loc_city,$loc_town,$storeaddress,$describes);

        if (!$storeres) {
            echo ResultSet::jfail(500, "Server Error：UpdateStoreinfo Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}

/**
 * @brief 删除单个仓库信息
 *
 * @param storeid  仓库ID
 *
 * @return  bool
 * */
class Action_warehouse_delstoreinfo extends XAction
{
    public function _run($request,$xcontext)
    {
        $storeid = $request->storeid;
        $areanum = XDao::query('StoreAreaTotalQuery')->listStoreAreaTotal($storeid);
        if ($areanum[0]['areatotal'] > 0) {
            echo ResultSet::jfail(401, "had stroearea dont delet stroe!");
            return XNext::nothing();
        }
        $storeres = StoreinfoSvc::ins()->delStoreInfo($storeid);

        if (!$storeres) {
            echo ResultSet::jfail(500, "Server Error：DelStoreinfo Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}














class Action_warehouse_waregoodsdetail extends XAction
{
    public function _run($request, $xcontext)


    {
        return XNext::useTpl("warehouse/waregoodsdetail.html");
    }
}

class Action_warehouse_wareinorout_set extends XAction
{
    public function _run($request, $xcontext)


    {
        return XNext::useTpl("warehouse/wareinorout_set.html");
    }
}
