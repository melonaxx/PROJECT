<?php
/**
 * 订单售后服务->售后分类
 */
class Action_order_saleserviceclassify extends XAction
{
    public function _run($request, $xcontext)
    {
    	$catedata = XDao::query('AfterSaleCateQuery')->listSaleCateData();

    	$xcontext->catedata = $catedata; //分类列表信息
        return XNext::useTpl("order/saleservice_classify.html");
    }
}


/**
 * 添加售后分类信息
 */
class Action_order_addsaleservice extends XAction
{
    public function _run($request, $xcontext)
    {
		$catename    = $request->catename;
		$catecomment = $request->catecomment;

		$cateres = AfterSaleCateSvc::ins()->addAfterCate($catename,$catecomment);

    	if (!$cateres) {
            echo ResultSet::jfail(500, "Server Error：addsaleservice Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($cateres);
        return XNext::nothing();
    }
}

/**
 * 修改售后分类信息
 */
class Action_order_editsaleservice extends XAction
{
    public function _run($request, $xcontext)
    {
		$id          = $request->cateid;
		$catename    = $request->catename;
		$comment = $request->comment;

		$cateres = AfterSaleCateSvc::ins()->editAfterCate($id,$catename,$comment);

    	if (!$cateres) {
            echo ResultSet::jfail(500, "Server Error：editsaleservice Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($cateres);
        return XNext::nothing();
    }
}

/**
 * 删除售后分类信息
 */
class Action_order_delsaleservice extends XAction
{
    public function _run($request, $xcontext)
    {
		$id          = $request->cateid;

		$cateres = AfterSaleCateSvc::ins()->delAfterCate($id);

    	if (!$cateres) {
            echo ResultSet::jfail(500, "Server Error：delsaleservice Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($cateres);
        return XNext::nothing();
    }
}