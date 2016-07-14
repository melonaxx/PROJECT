<?php
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");
pylon_include_sdk("/home/q/php/bridge_sdk/", "bridge_sdk.php");
/**
 * 添加印刷单
 */
class Action_app_addprint extends XAction
{
    public function _run($request, $xcontext)
    {
    	$unitdata = XDao::query('printunitQuery')->findall();
    	$methoddata = XDao::query('PrintMethodQuery')->listpmethod($puid);


    	$xcontext->unitdata = $unitdata;//印刷单位列表
    	$xcontext->methoddata = $methoddata;//印刷方式列表
        return XNext::useTpl("/app/addprint.html");
    }
}

/**
 * 通过印刷单位ID列出印刷方式列表
 */
class Action_app_listpmbypuid extends XAction
{
    public function _run($request, $xcontext)
    {
		$puid       = $request->puid;
		$methoddata = XDao::query('PrintMethodQuery')->listpmethod($puid);

    	if (!$methoddata) {
			echo ResultSet::jfail(500, "Server Error：listpmbypuid Fail");
			return XNext::nothing();
		}
		echo ResultSet::jsuccess($methoddata);
		return XNext::nothing();
    }
}

/**
 * 计算印刷成本
 */
class Action_app_countcost extends XAction
{
    public function _run($request, $xcontext)
    {
		$countdata     = $request->countdata;
		$vnumber       = $countdata['platemaking'];
		$printmethodid = $countdata['pmethodid'];
		$printunitid   = $countdata['punitid'];
		$printtimes    = $countdata['printtimes'];
		$pronumber     = $countdata['pronumber'];

		$printtimes = empty($printtimes) ? $printtimes=1 : $printtimes;
		$pronumber  = empty($pronumber) ? $pronumber=1 : $pronumber;

		$priceres = XDao::query('PrintMethodQuery')->getprintprice($printunitid,$printmethodid);
		$price = $priceres['price'];

		//判断使用哪种计算公式
		//有制版数时：单价*制版数+产品数量*每件印刷次数
		//无制版数时：单价*产品数量*每件印刷次数
		$printcost = 0.00;

		if (!$vnumber) {
			if ($printmethodid != '-1' && $printunitid != '-1') {
				$printcost = Floatval($price)*Intval($pronumber)*Intval($printtimes);
			}
		} else {
			if ($printmethodid != '-1' && $printunitid != '-1') {
				$printcost = (Floatval($price)*Intval($vnumber))+(Intval($pronumber)*Intval($printtimes));
			}
		}

        if (!$printcost) {
			echo ResultSet::jfail(500, "Server Error：countcost Fail");
			return XNext::nothing();
		}
		echo ResultSet::jsuccess($printcost);
		return XNext::nothing();
    }
}


/**
 * 添加印刷单信息
 */
class Action_app_addprintbill extends XAction
{
    public function _run($request, $xcontext)
    {
		$printdata     = $request->printdata;

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
		$printimage    = $printdata['printimage'];
		//操作人
		$cookuid = $_COOKIE['U'];
		$uidarr  = explode('=',$cookuid);
		$staffid = $uidarr['2'];

		$pflag = PrintBillSvc::ins()->addprintbill($printmethodid ,$printunitid ,$content ,$vnumber ,$pnumber ,$frequency ,$position ,$orderid ,$stylename ,$loadaddress ,$tpsetstatus ,$verifystatus ,$printcost ,$comment,$staffid);

		//添加图片
		if ($printimage) {
			$picflag = PrintPicSvc::ins()->addprintpic($pflag['id'] ,$printimage);
			if (!$picflag) {
				echo ResultSet::jfail(502, "print image upload fail!");
				return XNext::nothing();
			}
		}

    	if (!$pflag) {
			echo ResultSet::jfail(500, "Server Error：addprintbill Fail");
			return XNext::nothing();
		}
		echo ResultSet::jsuccess($pflag);
		return XNext::nothing();
    }
}

/**
 * 印刷单图片添加
 */
class Action_app_addprintimage extends XAction
{
    public function _run($request, $xcontext)
    {
		$picname = $_FILES['goodspic']['name'];
        $picsize = $_FILES['goodspic']['size'];

        if ($picname != "") {
        	//图片大小
            if ($picsize > 800000) {
                echo ResultSet::jfail(421, "picture size > 800kb!");
                return XNext::nothing();
            }
            //图片类型
            $type = strstr($picname, '.');
            if ($type != ".gif" && $type != ".jpg" && $type != ".png" && $type != ".jpeg") {
                echo ResultSet::jfail(432, "image type not fond!");
                return XNext::nothing();
            }

            //图片路径
            $tmp_file = $_FILES['goodspic']['tmp_name'];
            if (!is_uploaded_file($tmp_file)) {
                echo ResultSet::jfail(433, "not fond upload file!");
                return XNext::nothing();
            }

            //上传图片
            $qn = BridgeSvc::ins()->callbridge($tmp_file);

            if ($qn[0] != 0) {
                echo ResultSet::jfail(434, "upload file fail!");
                return XNext::nothing();
            }

			$picpath   = ProImage::IMAGEPATH;
			$imagename = $qn[1]['key'];
            $imgarr = array('imagename'=>$imagename,'picpath'=>$picpath);

	    	if (!$imgarr) {
				echo ResultSet::jfail(500, "Server Error：addprintimage Fail");
				return XNext::nothing();
			}
			echo ResultSet::jsuccess($imgarr);
			return XNext::nothing();
		}
    }
}
