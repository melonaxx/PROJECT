<?php

class Action_users_check extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showCompany("1");     
        if($result->errno != 0 && $result->errno != 404)
        {
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing(); 
        }
        
        $xcontext->platform = $result->data;
        return XNext::useTpl("/mage_user/users_check.html");
    }
}

class Action_users_docheck extends XPostAdminAction
{
    public function _run($request,$xcontext)
    {
        $companyid = $request->companyid;
        $status = $request->status;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->authCompany($companyid,$status);     
        if($result->errno != 0)
        {
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

class Action_labor_check extends XPostAdminAction
{
    public function _run($request,$xcontext)
    {
        $status = $request->status;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showCompany($status);
        
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($result->data);
        return XNext::nothing();
    }
}

class Action_approved extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showCompanyByStatus("0");     
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        $xcontext->data = $result->data;
        return XNext::useTpl('/mage_user/approved.html');
    }
}

class Action_approved_refused extends XPostAdminAction
{
    public function _run($request,$xcontext)
    {
        $status = $request->status;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showCompanyByStatus($status);     

        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        } 

        echo ResultSet::jsuccess($result->data);
        return XNext::nothing(); 
//        if($result && $result->errno === 0){
//            echo ResultSet::jsuccess($result->data);
//            return XNext::nothing();
//        }else{
//            echo ResultSet::jfail($result->errno,$result->errmsg);
//            return XNext::nothing();
//        }
    }
}   

class Action_userlist extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showUserList("1");
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        } 

        $xcontext->platform = $result->data;   
        return XNext::useTpl('/mage_user/userlist.html');
    }
}

class Action_userlisttype extends XPostAdminAction
{
    public function _run($request,$xcontext)
    {
        $status = $request->status;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showUserList($status);
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($result->data);
        return XNext::nothing();     
    }
}

class Action_blacklist extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        return XNext::useTpl('/mage_user/blacklist.html');
    }
}   
