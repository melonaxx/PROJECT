<?php

class Action_car extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showCompanyByStatus("0");    
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        
        $data = $result->data;
        $platform = array();
        if(!empty($data)){
            foreach($data as $v) {
                if($v['companytype'] === "1"){
                    $platform[] = $v;
                }
            }
        }
        $xcontext->platform = $platform;
        return XNext::useTpl('/mage_car/car_index.html');
    }
}

class Action_carlabor extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showCompanyByStatus("0"); 
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        } 
        $data = $result->data;
        $platform = array();
        if(!empty($data)){
            foreach($data as $v)
            {
                if($v['companytype'] === "2"){
                    $platform[] = $v;
                }    
            }
        }    
        $xcontext->platform = $platform;
        return XNext::useTpl('/mage_car/car_labor.html');
    }
}

class Action_allotcar extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $xcontext->username = $request->username;
        $xcontext->userid   = $request->userid;

        return XNext::useTpl('/mage_car/allotcar.html');
    }    
}

class Action_doallot extends XPostAdminAction
{
    public function _run($request,$xcontext) 
    {
        $userid = $request->userid;
        $serial = rtrim($request->serial);       
        $arr = explode("\n" , $serial);
        $data = array();
        for($i=0;$i<count($arr);$i++){
            if(strlen($arr[$i])===16 || strlen($arr[$i]) === 15){
                $data[] = $arr[$i];
            }
        }
        $data = array_unique($data);
        $act = "activate";
        $client = GClientAltar::getWCloudGateClient();     
        $result = $client->ebikeAssoc($userid , $data , $act); 
        if($result && $result->errno ===0){
            echo ResultSet::jsuccess($result->data);
            return XNext::nothing();
        }
        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();

    }
}

class Action_relievecar extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $username = $request->username;
        $userid   = $request->userid;
        $xcontext->username = $username;
        $xcontext->userid   = $userid;
        return XNext::useTpl('/mage_car/relievecar.html');
    }
}


class Action_dorelievecar extends XPostAdminAction
{
    public function _run($request,$xcontext) 
    {
        $userid = $request->userid;
        $serial = rtrim($request->serial);       
        $arr = explode("\n" , $serial);
        $data = array();
        for($i=0;$i<count($arr);$i++){
            if(strlen($arr[$i])===16){
                $data[] = $arr[$i];
            }
        }

        if(!empty($data)){
           $act = "unwrap";
           $client = GClientAltar::getWCloudGateClient();     
           $result = $client->ebikeAssoc($userid , $data , $act); 
           if($result && $result->errno ===0){
               echo ResultSet::jsuccess($result->data);
               return XNext::nothing();
           }
           echo ResultSet::jfail($result->errno,$result->errmsg);
           return XNext::nothing();
        }else{
            echo ResultSet::jfail(4001 , "Please complete the serial number ");
            return XNext::nothing();
        }

    }
}

/**
 *@brief  新增车辆
 */
class Action_addcar extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        return XNext::useTpl('/mage_car/addcar.html');
    }
}

class Action_doaddcar extends XPostAdminAction
{
    public function _run($request,$xcontext)
    {
        $seqno = rtrim($request->seqno);       
        $arr = explode("\n" , $seqno);
        $mobel = '36V,12A';        
        $data = array();                         
        for($i=0;$i<count($arr);$i++){           
            if(strlen($arr[$i])===16 || strlen($arr[$i])===15){           
                $data[] = $arr[$i];              
            }                                    
        }
        $data = array_unique($data); 
        if(empty($data)){
           echo ResultSet::jfail(4001,'Please enter the correct serial number');
           return XNext::nothing(); 
        }

        $client = GClientAltar::getWCloudGateClient();     
        $result = $client->storeEbikeSeqno($data,$mobel); 
        if($result && $result->errno === 0){
            echo ResultSet::jsuccess($result->data);
            return XNext::nothing();
        }
        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }
}
