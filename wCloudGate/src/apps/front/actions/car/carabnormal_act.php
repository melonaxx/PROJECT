<?php

/**
 * @brief   异常车辆
 */
class Action_platform_carabnormal extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
    	if($xcontext->usertype == UserType::KNIGHT) {
            $mainurl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainurl);
        }
        $userid = $xcontext->userid;
		$client = GClientAltar::getWCloudGateClient();
        $result = $client->getExceptionEbike($userid );

        if($result && $result->errno == 0) {
            $data = $result->data;
            $xcontext->aram = $data['aram'];
            $xcontext->elect = $data['elect'];
            $xcontext->lost = $data['lost'];
            $xcontext->stolen = implode( "," , $data['stolen'] );
            $xcontext->lowpower = implode( "," , $data['lowpower'] );
            $xcontext->losts = implode( "," , $data['losts'] );

        }else {
            $xcontext->aram = "";
            $xcontext->elect = "";
            $xcontext->lost = "";
            $xcontext->stolen = "";
            $xcontext->lowpower = "";
            $xcontext->losts = "";
        }

        $usertype = $xcontext->usertype;
        if($usertype == UserType::PLATFORM || $usertype == UserType::EMPLOYEE ) {
            $resultl = $client->showLaborInfo($userid );
            if($resultl && $resultl->errno == 0) {
                $ldata = $resultl->data;
                if(is_array($ldata)) {
                    array_pop($ldata);
                    array_pop($ldata);
                    for($i = 0; $i < count($ldata); $i++) {
                        if($usertype == 0 && $ldata[$i]['userid'] !== $userid)
                            continue;
                        $labour[$ldata[$i]['laborid']] = $ldata[$i]['name'];
                    }
                }
                $xcontext->labour = $labour ? $labour : "";
            }
        }
        if($usertype == UserType::LABOR) {
            $client = GClientAltar::getLaborClient();
            $resultl = $client->showPlatformInfo($userid);
            if($resultl && $resultl->errno == 0) {
                $ldata = $resultl->data;
                if(is_array($ldata)) {
                    array_pop($ldata);
                    array_pop($ldata);
                    for($i = 0; $i < count($ldata); $i++) {
                        $labour[$ldata[$i]['platformid']] = $ldata[$i]['name'];
                    }
                }
                $xcontext->labour = $labour ? $labour : "";
            }
        }

        return XNext::useTpl('/car/carabnormal.html');
    }

}

/**
 * @brief   劳务方 下的 异常车辆
 */
class Action_platform_carabnormallabor extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
		if($xcontext->usertype == UserType::KNIGHT) {
            echo ResultSet::jfail(403  , 'The request failed');
            return XNext::nothing();
        }

        $labourid = $request->labourid;
        $client = GClientAltar::getWCloudGateClient();
        $userid = $xcontext->userid;
		if($labourid == 'all') {			
	        $result = $client->getExceptionEbike($userid );
            if($result && $result->errno == 0) { 
	            $data = $result->data;
                $data[a] = implode( "," , $ldata['stolen'] );
                $data[b] = implode( "," , $ldata['lowpower'] );
                $data[c] = implode( "," , $ldata['losts'] );
	        	echo ResultSet::jsuccess($data);
	        	return XNext::nothing();
	        }
		}else {
	        $result = $client->getExpEbikeFromLabor($userid , $labourid);
            if($result && $result->errno == 0) { 
	        	$ldata = $result->data;
                $data[a] = implode( "," , $ldata['stolen'] );
                $data[b] = implode( "," , $ldata['lowpower'] );
                $data[c] = implode( "," , $ldata['losts'] );
	        	echo ResultSet::jsuccess($data);
                return XNext::nothing();
	        }
		}

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

