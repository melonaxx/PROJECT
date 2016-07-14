<?php
/**
 * @brif 添加商品分类页面
 *
 * @param
 */

class Action_goods_goodscbrand extends XAction
{
    public function _run($request, $xcontext)
    {
    	//显示分类列表
    	$goodscategory = XDao::query('ListCategoryQuery')->listCategoryInfo();

        $catelist = Unlimit::get_sort_by_array($goodscategory);
         if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['names'] = str_repeat("　", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->catelist = $catelist;
        // var_dump($catelist);
        return XNext::useTpl("goods/goodscbrand.html");
    }
}

/**
 * @brif 添加单个商品分类
 *
 * @param 商品分类名称
 *
 * @return bool
 */

class Action_goods_addgoodscate extends XAction
{
    public function _run($request, $xcontext)
    {
    	$catename = $request->catename;
    	$addcateflag = ProCategorySvc::ins()->addonecategory($catename);

        if (!$addcateflag)
        {
            echo ResultSet::jfail(4001, "Server Error：addgoodscate Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($addcateflag);
        return XNext::nothing();
    }
}

/**
 * @brif 添加单个商品子分类
 *
 * @param 商品子分类名称 父类ID
 *
 * @return bool
 */

class Action_goods_addgoodschildcate extends XAction
{
    public function _run($request, $xcontext)
    {
    	$pcateid = $request->pcateid;
    	$childcatename = $request->childcatename;
    	$addcateflag = ProCategorySvc::ins()->addchildcategory($pcateid,$childcatename);

        if (!$addcateflag)
        {
            echo ResultSet::jfail(4001, "Server Error：addgoodschildcate Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($addcateflag);
        return XNext::nothing();
    }
}

/**
 * @brif 修改商品分类
 *
 * @param 商品分类名称 分类ID
 *
 * @return bool
 */

class Action_goods_eidtgoodscate extends XAction
{
    public function _run($request, $xcontext)
    {
    	$cateid 		= $request->cateid;
    	$catecname 		= $request->catecname;
    	$eidtcategory = ProCategorySvc::ins()->editcategory($cateid,$catecname);

        if (!$eidtcategory)
        {
            echo ResultSet::jfail(4001, "Server Error：eidtgoodscate Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($eidtcategory);
        return XNext::nothing();
    }
}

/**
 * @brif 删除商品分类
 *
 * @param 商品分类ID
 *
 * @return bool
 */

class Action_goods_delgoodscate extends XAction
{
    public function _run($request, $xcontext)
    {
    	$cateid 		= $request->cateid;
    	$hadchild = XDao::query('HasCateByIdQuery')->HasChildCateInfo($cateid);
    	if($hadchild['total'] >0) {
    		echo ResultSet::jfail(4002, "has childecategory do not delete!");
            return XNext::nothing();
    	}
    	$eidtcategory = ProCategorySvc::ins()->delcategory($cateid);

        if (!$eidtcategory)
        {
            echo ResultSet::jfail(4001, "Server Error：delgoodscate Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($eidtcategory);
        return XNext::nothing();
    }
}