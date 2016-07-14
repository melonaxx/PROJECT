<?php

/**
 * @brief 显示商品属性名称列表
 *
 * @param
 *
 * @return 属性名称列表
 */
class Action_goods_goodsproperty extends XAction
{
    public function _run($request, $xcontext)
    {
    	$listattr = XDao::query('ListAttrQuery')->listAttrInfo();
    	$xcontext->listattr = $listattr;
        return XNext::useTpl("goods/goodsproperty.html");
    }
}

/**
 * @brief 添加商品属性名称
 *
 * @param 商品属性名称 pattrname
 *
 * @return bool
 */
class Action_goods_addgoodsproperty extends XAction
{
    public function _run($request, $xcontext)
    {
        $goodattrname    = $request->fattrname;
  		$pattrflag = ProAttrNameSvc::ins()->addgoodsattr($goodattrname);

        if (!$pattrflag) {
            echo ResultSet::jfail(434, "addgoodsproperty fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($pattrflag);
        return XNext::nothing();
    }
}

/**
 * @brief 修改商品属性名称
 *
 * @param 商品属性名称 pattrname
 * @param 商品属性名称ID pattrnameid
 *
 * @return bool
 */
class Action_goods_editgoodsproperty extends XAction
{
    public function _run($request, $xcontext)
    {
        $goodattrid    = $request->attrnameid;
        $goodattrname    = $request->eattrname;
  		$pattrflag = ProAttrNameSvc::ins()->editgoodsattr($goodattrname,$goodattrid);

        if (!$pattrflag) {
            echo ResultSet::jfail(434, "editgoodsproperty fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($pattrflag);
        return XNext::nothing();
    }
}

/**
 * @brief 删除商品属性名称
 *
 * @param 商品属性名称ID pattrnameid
 *
 * @return bool
 */
class Action_goods_delgoodsproperty extends XAction
{
    public function _run($request, $xcontext)
    {
        $goodattrid    = $request->pattrnameid;
        $attrvaluenum = XDao::query('AttrTotalByAttrNameIdQuery')->attrvaluetotal($goodattrid);
        //是否有属性值
        if ($attrvaluenum[0]['total'] > 0) {
            echo ResultSet::jfail(401, "has attrvalue dont delete!");
            return XNext::nothing();
        }
  		$pattrflag = ProAttrNameSvc::ins()->delgoodsattr($goodattrid);

        if (!$pattrflag) {
            echo ResultSet::jfail(434, "delgoodsproperty fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($pattrflag);
        return XNext::nothing();
    }
}

/**
 * @brief 列出商品属性值
 *
 * @param 商品属性名称ID pattrnameid
 *
 * @return 商品属性值列表
 */
class Action_goods_listpattrvalues extends XAction
{
    public function _run($request, $xcontext)
    {
    	$panameid = $request->panameid;
        $listpvalue = XDao::query('ListAttrValuesQuery')->listAttrValueInfo($panameid);

        if (!$listpvalue) {
            echo ResultSet::jfail(434, "listpattrvalue fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($listpvalue);
        return XNext::nothing();
    }
}

/**
 * @brief 添加商品属性值
 *
 * @param 商品属性名称ID pattrnameid
 * @param 商品属性值 pattrvalue
 *
 * @return 商品属性值列表
 */
class Action_goods_addpattrvalues extends XAction
{
    public function _run($request, $xcontext)
    {
    	$attribid = $request->pattrnameid;
    	$optional = $request->pattrvalue;
        $pavalueflag = ProAttrValueSvc::ins()->addgoodsattrvalue($attribid,$optional);

        if (!$pavalueflag) {
            echo ResultSet::jfail(434, "addpattrvalues fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($pavalueflag);
        return XNext::nothing();
    }
}

/**
 * @brief 修改商品属性值
 *
 * @param 商品属性值ID pattrvalueid
 * @param 商品属性值 pattrvalue
 *
 * @return bool
 */
class Action_goods_editpattrvalues extends XAction
{
    public function _run($request, $xcontext)
    {
    	$pattrname = $request->pattrname;
    	$pattrvid = $request->pattrvid;
        $pavalueflag = ProAttrValueSvc::ins()->editgoodsattrvalue($pattrname,$pattrvid);

        if (!$pavalueflag) {
            echo ResultSet::jfail(434, "editpattrvalues fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($pavalueflag);
        return XNext::nothing();
    }
}
/**
 * @brief 删除商品属性值
 *
 * @param 商品属性值ID pattrvalueid
 *
 * @return bool
 */
class Action_goods_delpattrvalues extends XAction
{
    public function _run($request, $xcontext)
    {
    	$pattrvalueid = $request->pattrvalueid;
        $pavalueflag = ProAttrValueSvc::ins()->delgoodsattrvalue($pattrvalueid);

        if (!$pavalueflag) {
            echo ResultSet::jfail(434, "delpattrvalues fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($pavalueflag);
        return XNext::nothing();
    }
}