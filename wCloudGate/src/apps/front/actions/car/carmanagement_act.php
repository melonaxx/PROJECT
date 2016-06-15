<?php

/**
 * @brief   车辆管理
 */
class Action_platform_carmanagement extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {        
        if($usertype == UserType::KNIGHT) {
            $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/carknight.php";
            return XNext::gotoUrl($mainUrl);
        }

        $userid = $xcontext->userid;
        $usertype = $xcontext->usertype;
        $num = $request->num;
        $page = $request->page;
        $pageall = $request->pageall;
        $data = array();

        if($usertype == UserType::LABOR) {
            
            //平台
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
                    $xcontext->labour1 = $labour;
                }else {
                    $xcontext->labour1 = "";
                }
            }else {
                $xcontext->labour1 = "";
            }

            $belong = $request->belong;
            $abnormal = $request->abnormal;
            $labour = $request->labour;
            $allocation = $request->allocation;
            $carsearch = $request->carsearch;
            $status = $request->status;
   
            if($status || $abnormal || $labour || $allocation || $carsearch || $belong || $num || $page ) {

                if($num < 1)    $num = 1;
                if($num > $pageall) $num = $pageall;
                if($abnormal == 2)  $abnormal = 0;
                $searchdata = array(
                    'allot' => intval($allocation),
                    'laborid' => intval($labour),
                    'exception' => intval($abnormal),
                    'belong' => intval($belong),
                    'seqno' => intval($carsearch),
                    'num' => intval($num),
                    'status' => intval($status),
                    'page' => intval($page)
                );
            }
        }


        if($usertype == UserType::PLATFORM || $usertype == UserType::EMPLOYEE ) {

            //劳务方
            $client = GClientAltar::getWCloudGateClient();
            $resultl = $client->showLaborInfo($userid );
            if($resultl && $resultl->errno == 0) {
                $ldata = $resultl->data;
                if(is_array($ldata)) {
                    array_pop($ldata);
                    array_pop($ldata);
                    for($i = 0; $i < count($ldata); $i++) {
                        if($usertype == 0 && $ldata[$i]['userid'] != $userid)
                            continue;
                        $labour[$ldata[$i]['laborid']] = $ldata[$i]['name'];
                    }
                    $xcontext->labour = $ldata;
                    $xcontext->labour1 = $labour;
                } else {
                    $xcontext->labour = "";
                    $xcontext->labour1 = "";
                }
            } else {
                $xcontext->labour = "";
                $xcontext->labour1 = "";
            }

            $belong = $request->belong;
            $abnormal = $request->abnormal;
            $labour = $request->labour;
            $allocation = $request->allocation;
            $carsearch = $request->carsearch;
            $status = $request->status;

            if($status || $abnormal || $labour || $allocation || $carsearch || $belong || $num || $page ) {

                if($num < 1)    $num = 1;                
                if($num > $pageall) $num = $pageall;              
                if($usertype == UserType::EMPLOYEE) $allocation = 0;
                if($abnormal == 2)  $abnormal = 0;
                $searchdata = array(
                    'distribute' => intval($allocation),
                    'laborid' => intval($labour),
                    'exception' => intval($abnormal),
                    'belong' => intval($belong),
                    'seqno' => intval($carsearch),
                    'num' => intval($num),
                    'status' => intval($status),
                    'page' => intval($page)
                );
            }
        }
         
        $client = GClientAltar::getLaborClient();
        $result = $client->combineSearch($userid , $searchdata);
        
        if($resultl && $resultl->errno == 0) {
            $data = $result->data;
            if(is_array($data)) {
                $pageall = array_pop($data);
                $pagenum = array_pop($data);
            }
        }else {
            $xcontext->data = '';
            $pageall = 0;
            $pagenum = 0;
        }
 
        if(!$num) $num = 1;
        if(!$page)  $page = 10;
        $xcontext->data = $data;       
        $xcontext->pageall = $pagenum; //共几页     
        $xcontext->count  =$pageall;      //共几条     
        $xcontext->num = $num;        
        $xcontext->page = $page;
        $xcontext->usertype = $usertype;
        return XNext::useTpl('/car/carmanagement.html');

    }

}

/**
 * @brief   车辆管理 显示平台
 */
class Action_platform_carmanagementplatforminfo extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype == UserType::KNIGHT) {
            echo ResultSet::jfail(4031  , 'The request failed');
            return XNext::nothing();
        }

        $userid = $xcontext->userid;
        $client = GClientAltar::getLaborClient();
        $result = $client->showPlatformInfo($userid );
        if($result && $result->errno == 0) {
            $data = $result->data;
            if(is_array($data)) {
                array_pop($data);
                array_pop($data);
                $seqno = $request->seqno;
                $ebikeid = $request->ebikeid;
                for($i = 0; $i < count($data); $i++) {
                    $state[$i]['state'] = $data[$i]['ownerseqno'][$seqno];
                    $state[$i]['platformid'] = $data[$i]['platformid'];
                    $state[$i]['name'] = $data[$i]['name'];
                    $state[$i]['ebikeid'] = $ebikeid;
                }
            } else {
                $state = "";
            }

            echo ResultSet::jsuccess($state);
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

/**
 * @brief   车辆管理 解除激活单个车辆
 */
class Action_platform_carmanagementdeactive extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype == UserType::KNIGHT) {
            echo ResultSet::jfail(4031  , 'The request failed');
            return XNext::nothing();
        }

        $seqno = $request->seqno;
        if($seqno) {
            $arrseqno[0] = $seqno;
            $userid = $xcontext->userid;
            $act = "unwrap";
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->ebikeAssoc( $userid , $arrseqno , $act);
            if($result && $result->errno == 0) {            
                echo ResultSet::jsuccess();
                return XNext::nothing();
            }            
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }

    public function getPermission() {
        return new Permission("0.0.2");
    }
}

/**
 * @brief   车辆管理 平台查看
 */
class Action_platform_carmanagementplatform extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype == UserType::KNIGHT) {
            echo ResultSet::jfail(4031  , 'The request failed');
            return XNext::nothing();
        }

        $userid = $xcontext->userid;
        $platformid = $request->platformid;
        $ebikeid = $request->ebikeid;
        $client = GClientAltar::getLaborClient();
        $result = $client->allowPlatformLook( $userid , intval($platformid) , intval($ebikeid) );
        if($result && $result->errno == 0) {            
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

/**
 * @brief   车辆管理 取消平台查看
 */
class Action_platform_carmanagementunplatform extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype == UserType::KNIGHT) {
            echo ResultSet::jfail(4031  , 'The request failed');
            return XNext::nothing();
        }

        $userid = $xcontext->userid;
        $ebikeid = $request->ebikeid;
        $client = GClientAltar::getLaborClient();
        $result = $client->forbidPlatformLook( $userid , intval($ebikeid) );
        if($result && $result->errno == 0) {            
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

/**
 * @brief   车辆管理 修改定位器号
 */
class Action_careditsensor extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {        
        $usertype =  $xcontext->usertype;
        if($usertype == UserType::KNIGHT || $usertype == UserType::EMPLOYEE) {
            echo ResultSet::jfail(403  , 'The request failed');
            return XNext::nothing();
        }

        $imei = $request->sensor;
        $ebikeid = $request->ebikeid;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->updateRelateSensor( $imei , intval($ebikeid) );
        if($result && $result->errno == 0) {            
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}