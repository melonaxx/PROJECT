<?php
/**
 * @brief 商品单位的显示
 *
 * @param
 *
 * @return
 */
class Action_goods_goodsunitset extends XAction
{
    public function _run($request, $xcontext)
    {
    	$listunit = XDao::query('ListProunitQuery')->listProunitinfo();
    	$xcontext->listunit = $listunit;
    	$listunitlength = count($listunit);
    	$xcontext->unitlength = $listunitlength;
        return XNext::useTpl("goods/goodsunitset.html");
    }
}

/**
 * @brief 添加单位的名称
 *
 * @param
 *
 * @return
 */
class Action_goods_addgoodsunitset extends XAction
{
    public function _run($request, $xcontext)
    {
        $unitname = $request->unitname;
        $addflag = ProUnitSvc::ins()->addgoodsunits($unitname);

        if (!$addflag) {
            echo ResultSet::jfail(434, "addgoodsunitset fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($addflag);
        return XNext::nothing();
    }
}

/**
 * @brief 修改单位的名称
 *
 * @param unitname 单位名称
 * @param unitid 单位ID
 *
 * @return bool
 */
class Action_goods_editgoodsunitset extends XAction
{
    public function _run($request, $xcontext)
    {
        $unitid = $request->unitid;
        $unitname = $request->unitname;
        $unitfalg = ProUnitSvc::ins()->editgoodsunits($unitname,$unitid);

        if (!$unitfalg) {
            echo ResultSet::jfail(434, "editgoodsunitse fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($unitfalg);
        return XNext::nothing();
    }
}

/**
 * @brief 删除单位的名称
 *
 * @param unitid 单位ID
 *
 * @return bool
 */
class Action_goods_delgoodsunitset extends XAction
{
    public function _run($request, $xcontext)
    {
        $unitid = $request->unitid;
    	$unitfalg = ProUnitSvc::ins()->delgoodsunits($unitid);

        if (!$unitfalg) {
            echo ResultSet::jfail(434, "delgoodsunitset fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($unitfalg);
        return XNext::nothing();
    }
}