<?php
/**
 * 编辑印刷单
 */
class Action_app_printlistedit extends XAction
{
    public function _run($request, $xcontext)
    {
    	$pbillid = $request->pbillid; //印刷单ID
    	$unitdata = XDao::query('printunitQuery')->findall();

    	$pbilldata = PrintBillSvc::ins()->getprintbillbyid($pbillid);
		$username = XDao::query("UserQuery")->userone($pbilldata['staffid']);
		$pbilldata['staffid'] = $username['name'];

    	/*印刷方式信息*/
    	$methoddata = XDao::query('PrintMethodQuery')->listpmethod($pbilldata['printunitid']);

    	/*印刷单的图片显示*/
		$printpic = XDao::query('PrintPicQuery')->getprintpicbyid($pbilldata['id']);
		$picname  = $printpic['filename'];
		$picpath  = ProImage::IMAGEPATH;
		$picarr   = ['picname'=>$picname,'picpath'=>$picpath];

		$xcontext->unitdata   = $unitdata;//印刷单位列表
		$xcontext->pbilldata  = $pbilldata;//印刷单信息列表
		$xcontext->methoddata = $methoddata;//印刷单方式列表
		$xcontext->picarr     = $picarr;//印刷单图片
        return XNext::useTpl("/app/printlistedit.html");
    }
}