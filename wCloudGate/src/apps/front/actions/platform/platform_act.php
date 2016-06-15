<?php

class Action_platform extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $type = array(0 , 1 , 4);
        if(in_array($xcontext->usertype, $type) ) {
            $mainurl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainurl);
        }        
        $company  = $request->company;
        $usertype = $xcontext->usertype;
        $userid   = $xcontext->userid;
        $client   = GClientAltar::getLaborClient();
        $page     = $_SESSION['page'] ? $_SESSION['page'] : 10;
        $num      = $request->num ? $request->num : "1";
        $company  = $request->company ? $request->company : ""; 
        $cond     = array('page'=>$page,'num'=>$num,'name'=>$company);
        $info     = 'page';
        $result   = $client->showPlatformInfo($userid,$info,$cond);
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        } 
        $data    = $result->data;
        $total   = array_pop($data);
        $pageAll = array_pop($data);
        $xcontext->total   = $total;
        $xcontext->company = $company;
        $xcontext->pageAll = $pageAll;
        $xcontext->data    = $data;       

//        $client = GClientAltar::getWCloudGateClient(); 
//        if(empty($company)){
//            $client = GClientAltar::getLaborClient(); 
//            $result = $client->showPlatformInfo($userid);
//            $xcontext->data = $result->data;
//        }else{
//            $xcontext->company = $company;
//            $result = $client->searchPlatformByName($userid,$company);    
//            $xcontext->data = $result->data;            
//        }    
        return XNext::useTpl('platform/platform.html');
    }
}

class Action_searchplatform extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $type = array(0 , 1 , 4);
        if(in_array($xcontext->usertype , $type)) {
            echo ResultSet::jfail(401 , 'You can not have access to the request');
            return XNext::nothing();
        }
        $userid = $request->userid; 
        $platform = $request->platform;
        $client = GClientAltar::getWCloudGateClient(); 
        $result = $client->searchPlatformByName($userid,$platform);
        if($result->errno == 0){
            echo ResultSet::jsuccess($result->data);
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}
