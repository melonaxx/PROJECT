<?php
/**
 * 印刷方式页面
 */
class Action_app_printmethod extends XAction
{
    public function _run($request, $xcontext)
    {
    	$unitdata = XDao::query('printunitQuery')->findall();
    	$methoddata = XDao::query('PrintMethodQuery')->listprintmethod();

    	//类型
    	$unitarr = array('Y'=>'是','N'=>'否');
    	foreach ($methoddata as $key => &$value) {
    		$value['type'] = $unitarr[$value['type']];

    		//公司名称
    		$punitdata = XDao::query('printunitQuery')->getunitbyid($value['printunitid']);
    		$value['printunitid'] = $punitdata['name'];
    	}

    	$xcontext->unitdata = $unitdata;//印刷单位列表
    	$xcontext->methoddata = $methoddata;//印刷方式列表
        return XNext::useTpl("/app/printmethod.html");
    }
}

/**
 * 添加印刷方式
 */
class Action_app_addprintmethod extends XAction
{
    public function _run($request, $xcontext)
    {
    	$methodobj = $request->attr;
		$name        = $methodobj['printmethodname'];
		$printunitid = $methodobj['punit'];
		$type        = $methodobj['creatbord'];
		$price       = $methodobj['pmprice'];
		$comment     = $methodobj['pmcomment'];

		$pmres = PrintMethodSvc::ins()->addprintmethod($name ,$printunitid ,$type ,$price ,$comment);

        return XNext::gotourl("/app/printmethod.php");
    }
}

/**
 * 获取单个印刷方式
 */
class Action_app_getprintmethod extends XAction
{
    public function _run($request, $xcontext)
    {
    	$pmid = $request->pmid;

    	$methodobj = array();
		$pmres = PrintMethodSvc::ins()->getprintmethodbyid($pmid);

    	$unitdata = XDao::query('printunitQuery')->findall();
    	$methodobj['pmres'] = $pmres;
    	$methodobj['unitdata'] = $unitdata;

        if (!$pmres) {
			echo ResultSet::jfail(500, "Server Error：getprintmethod Fail");
			return XNext::nothing();
		}
		echo ResultSet::jsuccess($methodobj);
		return XNext::nothing();
    }
}

/**
 * 编辑单个印刷方式
 */
class Action_app_editprintmethod extends XAction
{
    public function _run($request, $xcontext)
    {
    	$pmdata = $request->editpmdata;
    	$id          = $pmdata['id'];
    	$name        = $pmdata['name'];
    	$printunitid = $pmdata['printunitid'];
    	$type        = $pmdata['type'];
    	$price       = $pmdata['price'];
    	$comment     = $pmdata['comment'];

		$pmres = PrintMethodSvc::ins()->editprintmethod($id ,$name ,$printunitid ,$type ,$price ,$comment);

        if (!$pmres) {
			echo ResultSet::jfail(500, "Server Error：editprintmethod Fail");
			return XNext::nothing();
		}
		echo ResultSet::jsuccess($pmres);
		return XNext::nothing();
    }
}

/**
 * 删除单个印刷方式
 */
class Action_app_delprintmethod extends XAction
{
    public function _run($request, $xcontext)
    {
    	$pmid = $request->pmid;

		$pmres = XDao::dwriter('PrintMethodWriter')->delPrintMethod($pmid);

        if (!$pmres) {
			echo ResultSet::jfail(500, "Server Error：delprintmethod Fail");
			return XNext::nothing();
		}
		echo ResultSet::jsuccess($pmres);
		return XNext::nothing();
    }
}