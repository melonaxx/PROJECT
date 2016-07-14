<?php
/**
 * @brief 显示印刷分类页面
 *
 * @param
 *
 * @return 印刷分类列表
 **/
class Action_app_printunit extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("printunitQuery")->findall();

        $xcontext->list = $list;
        return XNext::useTpl("/app/printunit.html");
    }
}


/**
 * 添加印刷单
 */
class Action_app_doaddprintunit extends XAction
{
    public function _run($request, $xcontext)
    {
		$name    = $request->attr['name'];
		$comment = $request->attr['comment'];

		$result  =PrintunitSvc::ins()->add($name,$comment);

        return XNext::gotourl("/app/printunit.php");
    }
}

/**
 * 修改印刷单
 */
class Action_app_editprintunit extends XAction
{
    public function _run($request, $xcontext)
    {
		$id      = $request->puid;
		$name    = $request->puname;
		$comment = $request->pucomment;

		$result  =PrintunitSvc::ins()->editprintunit($id,$name,$comment);

		if (!$result) {
			echo ResultSet::jfail(500, "Server Error：editprintunit Fail");
			return XNext::nothing();
		}
		echo ResultSet::jsuccess($result);
		return XNext::nothing();
	}
}

/**
 * 删除印刷单
 */
class Action_app_delprintunit extends XAction
{
    public function _run($request, $xcontext)
    {
		$id      = $request->puid;

		$pures  =XDao::dwriter('PrintUnitWriter')->delPrintUnit($id);

		if (!$pures) {
			echo ResultSet::jfail(500, "Server Error：editprintunit Fail");
			return XNext::nothing();
		}
		echo ResultSet::jsuccess($pures);
		return XNext::nothing();
	}
}