<?php

class Action_web extends XAdminAction
{
    public function _run($request,$xcontent)
    {
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showCallInfoList();
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        } 
        $xcontent->data = $result->data;        
        return XNext::useTpl('/mage_web/web_index.html');
    }
}

class Action_doweb extends XPostAdminAction
{
    public function _run($request,$xcontent)
    {
        $val = $request->notice;
        
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->storeCallInfo($val);
        
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        header('Location: /web.php');
        return XNext::nothing();                          
    }
}

class Action_notice extends XPostAdminAction
{
    public function _run($request,$xcontent)
    {
        $notid = XParamFilter::htmlNumber($request->notid);
        $nowid = XParamFilter::htmlNumber($request->nowid);
        
        if(empty($notid) || empty($nowid)){
            echo ResultSet::jfal("403","invalid parameter");
            return XNext::nothing();
        }

        $client = GClientAltar::getWCloudGateClient();
        if($notid===$nowid){
            $result = $client->setDisplayCallInfo($nowid);
        }else{
            $result = $client->setDisplayCallInfo($notid,$nowid);
        }
        
        if($result->errno != 0){
            echo ResultSet::jfal($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

class Action_addweb extends XAction
{
    public function _run($request ,$xcontent)
    {
        return XNext::useTpl("/mage_web/addweb.html"); 
    }    
}
