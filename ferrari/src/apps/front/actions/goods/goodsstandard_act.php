<?php
/**
 * @brief 商品规格显示
 *
 * @param
 *
 * @return
 */
class Action_goods_goodsstandard extends XAction
{
    public function _run($request, $xcontext)
    {
    	$formatelist = XDao::query('ListFomateQuery')->listFormateInfo();
    	$xcontext->formatelist = $formatelist;
         return XNext::useTpl("goods/goodsstandard.html");
    }
}

/**
 * @brief 添加商品規格
 *
 * @param 商品规格名称formatenaem
 *
 * @return bool
 */
class Action_goods_addgoodsformate extends XAction
{
    public function _run($request, $xcontext)
    {
    	$formatename = $request->formatename;
    	$addflag = ProFormateNameSvc::ins()->addformate($formatename);

        if (!$addflag) {
            echo ResultSet::jfail(434, "addgoodsformate fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($addflag);
        return XNext::nothing();
    }
}

/**
 * @brief 删除商品規格
 *
 * @param 商品规格名称ID
 *
 * @return bool
 */
class Action_goods_delgoodsformate extends XAction
{
    public function _run($request, $xcontext)
    {
    	$fnameid = $request->fnameid;
        //查询有没有规格值
        $formateflag = XDao::query('totalByFnameidQuery')->totalFormateValueInfo($fnameid);
        if ($formateflag[0]['total'] > 0) {
            echo ResultSet::jfail(4001, "has formatevale !");
            return XNext::nothing();
        }
    	$delfnameflag = ProFormateNameSvc::ins()->delfnamebyid($fnameid);

        if (!$delfnameflag) {
            echo ResultSet::jfail(434, "delgoodsformate fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($delfnameflag);
        return XNext::nothing();
    }
}

/**
 * @brief 修改商品規格
 *
 * @param 商品规格名称ID 商品规格名称
 *
 * @return bool
 */
class Action_goods_editgoodsformate extends XAction
{
    public function _run($request, $xcontext)
    {
    	$eformatename 	= $request->eformatename;
    	$eformateid 	= $request->eformateid;
    	$editformatflag = ProFormateNameSvc::ins()->editfnamebyid($eformatename,$eformateid);

        if (!$editformatflag) {
            echo ResultSet::jfail(434, "editgoodsformate fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($editformatflag);
        return XNext::nothing();
    }
}

/**
 * @brief 添加商品規格值
 *
 * @param 商品规格值ID 商品规格值
 *
 * @return bool
 */
class Action_goods_addgoodsformatevalue extends XAction
{
    public function _run($request, $xcontext)
    {
    	$addfvalue 	= $request->addfvalue;
    	$afnameid 	= $request->afnameid;
    	$addfvalue = ProFormateValueSvc::ins()->addfvalueinfo($addfvalue,$afnameid);

        if (!$addfvalue) {
            echo ResultSet::jfail(434, "addgoodsformatevalue fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($addfvalue);
        return XNext::nothing();
    }
}

/**
 * @brief 查询并列出商品規格值
 *
 * @param 商品规格名称ID
 *
 * @return 规格值列表
 */
class Action_goods_listgoodsformatevalue extends XAction
{
    public function _run($request, $xcontext)
    {
        $fnameid = $request->fnameid;
        $listfvalue = XDao::query('ListFvalueByFnameidQuery')->listFormateValueInfo($fnameid);
        if (!$listfvalue) {
            echo ResultSet::jfail(434, "listgoodsformatevalue fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($listfvalue);
        return XNext::nothing();
    }
}


/**
 * @brief 修改商品规格值
 *
 * @param fvaluename 规格值
 * @param fvalueid 规格值ID
 *
 * @return bool
 **/
class Action_goods_editgoodsformatevalue extends XAction
{
    public function _run($request, $xcontext)
    {
        $fvalue        = $request->fvalue;
        $fvalueid      = $request->fvalueid;
        $fvalflag = ProFormateValueSvc::ins()->editfvalueinfo($fvalue,$fvalueid);

        if (!$fvalflag) {
            echo ResultSet::jfail(434, "editgoodsformatevalue fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($fvalflag);
        return XNext::nothing();
    }
}

/**
 * @brief 删除商品规格值
 *
 * @param fvalueid 规格值ID
 *
 * @return bool
 **/
class Action_goods_delgoodsformatevalue extends XAction
{
    public function _run($request, $xcontext)
    {
        $fvalueid        = $request->fvalueid;
  		$fvalflag = ProFormateValueSvc::ins()->delfvalueinfo($fvalueid);

        if (!$fvalflag) {
            echo ResultSet::jfail(434, "delgoodsformatevalue fail!");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($fvalflag);
        return XNext::nothing();
    }
}

