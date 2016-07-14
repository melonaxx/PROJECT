<?php
/**
 * @brief 商品品牌页面。
 *
 * @param
 *
 * @return
 **/
class Action_goods_goodscbrand_brand extends XAction
{
    public function _run($request, $xcontext)
    {
    	//----显示分类列表----
        $procate = XDao::query('ListBrandQuery')->listBrandInfo();

        $catelist = Unlimit::get_sort_by_array($procate);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['names'] = str_repeat("　", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->catelist = $catelist;
        // var_dump($catelist);
         return XNext::useTpl("goods/goodscbrand_brand.html");
    }
}

/**
 * @brief 商品品牌添加。
 *
 * @param 商品品牌名称brandname
 *
 * @return bool
 **/
class Action_goods_addgoodsbrand extends XAction
{
    public function _run($request, $xcontext)
    {
    	$brandname = $request->brandname;
    	$insertbrandid = ProBrandSvc::ins()->addgoodsbrand($brandname);

        if (!$insertbrandid)
        {
            echo ResultSet::jfail(500, "Server Error：addgoodsbrand Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($insertbrandid);
        return XNext::nothing();
    }
}

/**
 * @brief 商品添加子品牌。
 *
 * @param 商品子品牌名称brandname 商品父ID
 *
 * @return bool
 **/
class Action_goods_addgoodschildbrand extends XAction
{
    public function _run($request, $xcontext)
    {
    	$brandname = $request->bchildname;
    	$parendid = $request->bparentid;
    	$insertbrandid = ProBrandSvc::ins()->addgoodsbrandchild($brandname,$parendid);

        if (!$insertbrandid)
        {
            echo ResultSet::jfail(500, "Server Error：addgoodschildbrand Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($insertbrandid);
        return XNext::nothing();
    }
}

/**
 * @brief 删除商品品牌。
 *
 * @param 商品品牌ID
 *
 * @return bool
 **/
class Action_goods_delbrandbyid extends XAction
{
    public function _run($request, $xcontext)
    {
    	$brandid = $request->brandid;
    	$haschildflag = XDao::query('BrandIsChildQuery')->havebrandchild($brandid);
    	if ($haschildflag['total'] > 0) {
    		echo ResultSet::jfail(4001, "have child brand!");
            return XNext::nothing();
    	}
    	$delbrand = ProBrandSvc::ins()->delgoodsbrand($brandid);

        if (!$delbrand)
        {
            echo ResultSet::jfail(500, "Server Error：delbrandbyid Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($delbrand);
        return XNext::nothing();
    }
}

/**
 * @brief 修改单个商品品牌。
 *
 * @param 商品品牌ID 商品品牌名称
 *
 * @return bool
 **/
class Action_goods_editbrandbyid extends XAction
{
    public function _run($request, $xcontext)
    {
    	$brandname = $request->brandname;
    	$brandid = $request->brandid;
    	$editbrandid = ProBrandSvc::ins()->editgoodsbrand($brandid,$brandname);

        if (!$editbrandid)
        {
            echo ResultSet::jfail(500, "Server Error：editbrandbyid Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($editbrandid);
        return XNext::nothing();
    }
}