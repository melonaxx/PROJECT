<?php

class Action_labor_doaddlabor extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $laborid = $request->laborid;
        if($xcontext->usertype === "1"){
            $empeid  = $request->employee;
        }else{
            $empeid = $xcontext->userid;  
        } 
        $platformid = $xcontext->userid;
        $client = GClientAltar::getPlatformClient();   
        $result = $client->addLabor($platformid,$empeid,$laborid);
        
        if($result->errno != 0){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        header("Location:/labor/labormanage.php");
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission("0.1.0");
    }

}

class Action_labor_doallowebike extends XGetPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $seqno = $request->serialnum;
        if(!is_array($seqno))
        {
            $seqno = explode(",",$seqno);
        }
        $laborid = $request->laborid;
        
        $client = GClientAltar::getWCloudGateClient();   
        $result = $client->distributeEbikeToLabor($userid,$laborid,$seqno);

        if($result->error === 0){
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission("0.4.0");
    }

}

class Action_labor_dellabor extends XGetPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $laborid = $request->laborid;
        $userid = $xcontext->userid;
        $client = GClientAltar::getPlatformClient();  
        $result = $client->removeLabor($userid,$laborid);
        if($result->errno != 0)
        {
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        $data =  $result->errno;
        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission("0.2.0");
    }

} 

class Action_labor_docancleebike extends XGetPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $seqno    = $request->serialnum;
        $userid   = $xcontext->userid;
        $usertype = $xcontext->usertype;
        $client = GClientAltar::getPlatformClient();
        if($usertype === "0"){
            $result = $client->cancleDistributeEbike($seqno,$userid);
        }else{
            $result = $client->cancleDistributeEbike($seqno,$userid,'plt');
        } 
        if($result->error === 0){
            echo ResultSet::jsuccess($result->errno);
            return XNext::nothing();
        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission("0.8.0");
    }

}

class Action_labor_updatelabor extends XGetPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $laborid = $_GET['laborid'];
        $userid = $_GET['staff'];
        $platid = $xcontext->userid;  
        $client = GClientAltar::getPlatformClient();
        $result = $client->updateEmployeeForLabor($platid,$userid,$laborid);
        
        if($result->errno === 0)
        {
            $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/labor/labormanage.php";  
            return XNext::gotoUrl($mainUrl);                        
        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission("0.-1.0");
    }

}
