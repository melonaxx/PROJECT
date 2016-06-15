<?php

/**
 * @brief   主页
 */
class Action_main extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $client = GClientAltar::getWCloudGateClient();
        $usertype = $xcontext->usertype;
        $userid   = $xcontext->userid;
        $result = $client->showCallInfo();
        if($result->errno !=0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        $xcontext->notice    = $result->data;
        switch ($usertype){
        case 0: //员工
            $client  = GClientAltar::getPlatformClient();
            $result  = $client->showLaborList($userid,$sort);
            $laborid = $request->laborid;
            $status  = $request->status;
            if($result->errno != 0 && $result->errno != 404){
                echo ResultSet::jfail($result->errno,$result->errmsg);
                return XNext::nothing();
            }
            $labor = $result->data;
            //除去非该员工的劳务方
            $arr = array();
            if(!empty($labor)){
                foreach($labor as $v){
                    if($v['userid'] === $userid){
                        $arr[] = $v;
                    }
                }
            }
            if(empty($laborid)){
                $resultstate = $client->statEmployeeEbikeInfo($userid);
                if($resultstate->errno != 0 ){
                    echo ResultSet::jfail($resultstate->errno,$resultstate->errmsg);
                    return XNext::nothing();
                }
                $xcontext->labor = $arr;
            }else{
                $client = GClientAltar::getWCloudGateClient();
                $resultstate = $client->statCompanyEbikeinfo($userid,$laborid);
                if($resultstate->errno != 0){
                    echo ResultSet::jfail($resultstate->errno,$resultstate->errmsg);
                    return XNext::nothing();
                }
                $xcontext->get = $laborid;
                if(!empty($status)){
                   $arra = array();
                   foreach($labor as $v){
                       if($v['laborid']===$laborid){
                           $arra[] = $v;
                       }
                   }
                   $xcontext->labor = $arra;
                }else{
                    $xcontext->labor = $arr;
                }
            }

            $data   = $resultstate->data;

            $xcontext->ebikesum  = $data['ebikesum'];
            $xcontext->exception = $data['exception'];
            $xcontext->restnum   = $data['restnum'];
            $xcontext->runnum    = $data['runnum'];
            $xcontext->power     = implode(',',$resultstate->data['power']);
            $xcontext->total     = implode(',',$resultstate->data['total']);
            $xcontext->excep     = implode(',',$resultstate->data['excep']);
            $xcontext->userid    = $userid;
            $xcontext->laborid   = $laborid;
            $xcontext->usertype  = $xcontext->usertype;
            return XNext::useTpl('index/index.html');
        break;
        case 1: //平台
            $client  = GClientAltar::getPlatformClient();
            $result  = $client->showLaborList($userid,$sort);
            $laborid = $request->laborid;
            $status  = $request->status;
            if($result->errno != 0 && $result->errno != 404){
                echo ResultSet::jfail($result->errno,$result->errmsg);
                return XNext::nothing();
            }
            if(empty($laborid)){
                $client = GClientAltar::getWCloudGateClient();
                $resultstate = $client->StatEbike($userid);
                if($resultstate->errno != 0){
                    echo ResultSet::jfail($resultstate->errno,$resultstate->errmsg);
                    return XNext::nothing();
                }
                $xcontext->labor = $result->data;
            }else{
                $client = GClientAltar::getWCloudGateClient();
                $resultstate = $client->statCompanyEbikeinfo($userid,$laborid);
                if($resultstate->errno != 0){
                    echo ResultSet::jfail($resultstate->errno,$resultstate->errmsg);
                    return XNext::nothing();
                }
                $xcontext->get = $laborid;
                $labor = $result->data;
                $arra  = array();
                if(!empty($status)){
                    for($i=0;$i<count($labor);$i++){
                       if($labor[$i]['laborid'] === $laborid){
                           $arra[] = $labor[$i];
                       }
                    }
                    $xcontext->labor = $arra;
                }else{
                    $xcontext->labor = $labor;
                }
            }
            $data   = $resultstate->data;

            $xcontext->ebikesum  = $data['ebikesum'];
            $xcontext->exception = $data['exception'];
            $xcontext->restnum   = $data['restnum'];
            $xcontext->runnum    = $data['runnum'];
            $xcontext->power     = implode(',',$resultstate->data['power']);
            $xcontext->total     = implode(',',$resultstate->data['total']);
            $xcontext->excep     = implode(',',$resultstate->data['excep']);
            $xcontext->userid    = $userid;
            $xcontext->laborid   = $laborid;
            $xcontext->usertype  = $xcontext->usertype;
            return XNext::useTpl('index/index.html');
        break;
        case 2://劳务方
            $client     = GClientAltar::getLaborClient();
            $result     = $client->showPlatformInfo($userid);
            $status     = $request->status;
            $platformid = $request->platformid;
            if($result->errno != 0 && $result->errno != 404){
               echo ResultSet::jfail($result->errno,$result->errmsg);
               return XNext::nothing();
            }
            if(!empty($status)){
                $dat = $result->data;
                $arar = array();
                foreach($dat as $v){
                    if($v['platformid']===$_GET['platformid']){
                        $arar[] = $v;
                    }
                    $xcontext->platform = $arar;
                }
            }else{
                $xcontext->platform = $result->data;
            }

            if(empty($platformid)){
                $client = GClientAltar::getWCloudGateClient();
                $resultstate = $client->StatEbike($userid);
                if($resultstate->errno != 0){
                    echo ResultSet::jfail($resultstate->errno,$resultstate->errmsg);
                    return XNext::nothing();
                }
            }else{
                $client = GClientAltar::getWCloudGateClient();
                $resultstate = $client->statCompanyEbikeinfo($userid,$platformid);
                if($resultstate->errno != 0){
                    echo ResultSet::jfail($resultstate->errno,$resultstate->errmsg);
                    return XNext::nothing();
                }
                $xcontext->platformid = $platformid;
            }

            $data = $resultstate->data;
            $xcontext->ebikesum  = $data['ebikesum'];
            $xcontext->exception = $data['exception'];
            $xcontext->restnum   = $data['restnum'];
            $xcontext->runnum    = $data['runnum'];
            $xcontext->power     = implode(',',$resultstate->data['power']);
            $xcontext->total     = implode(',',$resultstate->data['total']);
            $xcontext->excep     = implode(',',$resultstate->data['excep']);
            $xcontext->userid    = $userid;
            $xcontext->laborid   = $_GET['platformid'];
            $xcontext->usertype  = $xcontext->usertype;
            return XNext::useTpl("main/mainplatform.html");
        break;
        case 4: //骑士
            $client = GClientAltar::getPlatformClient();
            $result = $client->showAroundKnightinfo($userid);
            if($result->errno != 0 && $result->errno != 404){
                echo ResultSet::jfail($result->errno,$result->errmsg);
                return XNext::nothing();
            }
            $data = $result->data;
            if(!empty($data)){
                for($i=0;$i<count($data);$i++){
                    if($data[$i]['userid']===$userid)
                    {
                        $arr = $data[$i];
                        unset($data[$i]);
                    }
                }
                $xcontext->data = array_values($data);
            }    
            $clientt = GClientAltar::getLaborClient();
            $resultt = $clientt->statKnightEbikeInfo($userid);
            if($resultt->errno != 0){
                echo ResultSet::jfail($resultt->errno,$resultt->errmsg);
                return XNext::nothing();
            }

            $power      = $resultt->data['power'];
            $total      = $resultt->data['total'];
            $averspeed  = $resultt->data['averspeed'];
            $xcontext->day_power     = ($power[14]);
            $xcontext->day_total     = ($total[14]);
            $xcontext->day_averspeed = ($averspeed[14]);
            $xcontext->power     = implode(',',$power);
            $xcontext->total     = implode(',',$total);
            $xcontext->averspeed = implode(',',$averspeed);
            $xcontext->userid        = $userid;
            $xcontext->knightseqno   = $arr['seqno'];
            $xcontext->usertype      = $xcontext->usertype;
            return XNext::useTpl("main/mainknight.html");
        break;
        }
    }
}

class Action_searchlabor extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $usertype = $xcontext->usertype;
        $name = $request->name;
        $userid = $xcontext->userid;
        if($usertype === "1"){
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->searchLaborByName($name,$userid);
            if($result && $result->errno === 0){
                $labor = $result->data;
                for($i=0;$i<count($labor);$i++){
                    if($labor[$i]['belong']==0){
                        $labor[$i] = array();
                    }
                }

                echo ResultSet::jsuccess($labor);
                return XNext::nothing();
            }

            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }else{
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->searchLaborByName($name,$userid);
            if($result->errno === 0){
                $labor = $result->data;
                for($i=0;$i<count($labor);$i++){
                    if($labor[$i]['belong']===0 || $labor[$i]['userid'] != $userid){
                        $labor[$i] = array();
                    }
                }

                echo ResultSet::jsuccess($labor);
                return XNext::nothing();
            }

            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
    }
}

/*
 *按车辆数量排序
 * */
class Action_ebikenum extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $usertype = $xcontext->usertype;
        $sort   = $request->sort;
        $client = GClientAltar::getPlatformClient();
        $result = $client->showLaborList($userid,$sort);
        if($result->errno === 0 || $result->errno === 404){
            if($usertype==="0"){
                $data = $result->data;
                $arr = array();
                if(!empty($data)){
                    foreach($data as $v){
                        if($v['userid']===$userid){
                            $arr[] = $v;
                        }
                   }
                }
                echo ResultSet::jsuccess($arr);
                return XNext::nothing();
            }else{
                echo ResultSet::jsuccess($result->data);
                return XNext::nothing();
            }

        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }
}

/*
 *劳务方按分组排序
 */
class Action_groupsort extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $userid  = $xcontext->userid;
        $groupid = $request->groupid;
        $client  = GClientAltar::getLaborClient();
        $result  = $client->showKGrop($userid );

        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        $xcontext->group = $result->data;

        //数据统计
        if(empty($groupid)){
            $resultt = $client->statKGropInfo($userid);
            if($resultt->errno != 0 && $resultt->errno != 404){
                echo ResultSet::jfail($resultt->errno,$resultt->errmsg);
                return XNext::nothing();
            }
        }else{
            $groupid = XParamFilter::htmlNumber($groupid);
            $xcontext->groupid = $groupid;
            if(!empty($groupid)){
                $resultt = $client->statEbikeInfoByKGrop($groupid);
                if($resultt->errno != 0 && $resultt->errno != 404){
                    echo ResultSet::jfail($resultt->errno,$resultt->errmsg);
                    return XNext::nothing();
                }
           }else{
               echo ResultSet::jfail(403,"Invalid parameter");
               return XNext::nothing();
           }
        }
        $xcontext->data  = $resultt->data;
        $xcontext->power = implode(',',$resultt->data['power']);
        $xcontext->total = implode(',',$resultt->data['total']);
        $xcontext->excep = implode(',',$resultt->data['excep']);

        $client = GClientAltar::getWCloudGateClient();
        $usertype = $xcontext->usertype;
        $userid   = $xcontext->userid;
        $result = $client->showCallInfo();
        if($result->errno !=0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        $xcontext->notice    = $result->data;
        //$xcontext->notice    = $xcontext->groupid;
        return XNext::useTpl("main/mainplatform-group.html");
    }
}

//分组搜索
class Action_searchbygroup extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $name    = $request->name;
        $userid  = $xcontext->userid;
        $client  = GClientAltar::getLaborClient();
        $result  = $client->searchKGropByName($name,$userid);

        if($result->errno != 0){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($result->data);
        return XNext::nothing();
    }
}
