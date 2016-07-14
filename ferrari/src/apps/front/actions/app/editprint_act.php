<?php
/**
 * 印刷单审核页面修改
 */
class Action_app_editprint extends XAction
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
        return XNext::useTpl("/app/editprint.html");
    }
}

/**
 * 修改印刷单
 */
class Action_app_editprintbill extends XAction
{
    public function _run($request, $xcontext)
    {
		$printdata = $request->printdata; //印刷单obj

		//开启事务
		$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();

		$id =$printdata['id'];

		$printmethodid = $printdata['printmethodid'];
		$printunitid   = $printdata['printunitid'];
		$content       = $printdata['content'];
		$vnumber       = $printdata['vnumber'];
		$pnumber       = $printdata['pnumber'];
		$frequency     = $printdata['frequency'];
		$position      = $printdata['position'];
		$orderid       = $printdata['orderid'];
		$stylename     = $printdata['stylename'];
		$loadaddress   = $printdata['loadaddress'];
		$tpsetstatus   = $printdata['tpsetstatus'];
		$verifystatus  = $printdata['verifystatus'];
		$printcost     = $printdata['printcost'];
		$comment       = $printdata['comment'];
		$cdate         = $printdata['comdate'];
		$cstatus       = $printdata['comstatus'];
		$comdate       = !empty($cdate) ? $cdate : '';
		$comstatus     = !empty($cstatus) ? $cstatus : '';

		$printimage    =$printdata['printimage'];

		//修改印刷单
		$editflag = XDao::query('PrintBillWriter')->editprintbill($id ,$printmethodid ,$printunitid ,$content ,$vnumber ,$pnumber ,$frequency ,$position ,$orderid ,$stylename ,$loadaddress ,$tpsetstatus ,$verifystatus ,$printcost ,$comment,$comdate,$comstatus);

		//修改印刷单图片
		$printpicflag = XDao::query('PrintPicQuery')->getprintpicbyid($id);

		//图片是否存在
		if ($printpicflag) {
			$editpic = XDao::dwriter('PrintPicWriter')->editprintpic($id,$printimage);
		} else {
			if ($printimage) {
				$editpic = PrintPicSvc::ins()->addprintpic($id ,$printimage);
			}
		}

    	if (!$editflag && !$editpic ) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：editprintbill Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}