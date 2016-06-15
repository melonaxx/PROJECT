<?php

class Action_sensorindex extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->getSensorConf();
        if($result->errno != 0 && $result->errno != 404)
        {
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();    
        }
        $data = $result->data;
        $xcontext->data = $data;
        if(!empty($data)){
            $arr = array();
            foreach($data as $v){
                if($v['sensorid'] === "0"){
                    $arr[] = $v;
                }
            }
            $xcontext->arr = $arr;    
        }

        return XNext::useTpl("/mage_sensor/sensor_index.html");
    }
}


/**
 * @brief   传感器历史记录
 */
class Action_sensorhistory extends XAdminAction
{
	public function _run($request , $xcontext)
	{
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->getConfLog();
        if($result->errno != 0 && $result->errno != 404)
        {
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        $xcontext->data = $result->data;
        return XNext::useTpl("/mage_sensor/sensor_history.html");
	}
}

/**
 * @brief  删除历史记录
 */
class Action_historydel extends XPostAdminAction
{
	public function _run($request , $xcontext)
    {
        $id = $request->id;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->removeConfLog($id);
        if($result->errno != 0)
        {
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        echo ResultSet::jsuccess();
        return XNext::nothing();
	}
}

/**
 * @brief   传感器配置修改处理
 */
class Action_sensorupdate extends XPostAdminAction
{
	public function _run($request , $xcontext)
    {
        $data = $request->data;
        if(empty($data)){
            $ver  = $request->ver;
            $cf   = $request->cf;    
            $f    = $request->f;    
            $wi   = $request->wi;    
            $wf   = $request->wf;    
            $ebikeid = $request->ebikeid;
        }else{
            $data = explode(",",$data);
            $ver = $data[0];$cf = $data[1];$f = $data[2];
            $wi  = $data[3];$wf = $data[4];$ebikeid = $data[5];
        }    
        if($ebikeid =="全部"){
            $ver = $ver;
        }else{
            $ver  = ($ver+1);    
        }
        if(empty($ebikeid) || $ebikeid === "全部"){
            $data = array("ver"=>$ver,"cf"=>$cf,"f"=>$f,"wi"=>$wi,"wf"=>$wf);
        }else{
            $data = array("ver"=>$ver,"cf"=>$cf,"f"=>$f,"wi"=>$wi,"wf"=>$wf,"seqno"=>$ebikeid);
        }
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->updateSensorConf($data);
        if($result->errno != 0)
        {
            echo ResultSet::jfail($result->errno,$result->errmsg); 
            return XNext::nothing();   
        }
        
        echo ResultSet::jsuccess();
        return XNext::nothing();       
    }
}

/**
 *@brief 传感器列表
 */
class Action_sensorlist extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showSensorInfo();
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        
        $list = $result->data;
        if(!empty($list)){
            $data = array();
            foreach($list as $v){
                if($v['seqno'] != 0){
                    $data[] = $v; 
                }
            }
        }
        $xcontext->data = $data;
        return XNext::useTpl('/mage_sensor/sensorlist.html');
    }
}

class Action_unbindsensor extends XPostAdminAction
{
    public function _run($request,$xcontext){
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->showSensorInfo();
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        $data = array();
        $list = $result->data;
        
        foreach($list as $v){
            if($v['seqno'] === 0 || $v['seqno'] === NULL){
                $data[] = $v; 
            }
        }
        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }    
}

/**
 *@brief 添加传感器
 */
class Action_addsensor extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        return XNext::useTpl('/mage_sensor/addsensor.html');
    }
}

class Action_doaddsensor extends XPostAdminAction
{
    public function _run($request,$xcontext)
    {
        $imei = rtrim($request->imei);       
        $arr = explode("\n" , $imei);          
        $data = array();                         
        for($i=0;$i<count($arr);$i++){           
            if(strlen($arr[$i]) === 16 || strlen($arr[$i]) === 15){           
                $data[] = $arr[$i];              
            }                                    
        } 
        $data = array_unique($data);   
        if(empty($data)){
            echo ResultSet::jfail(4001,'Please enter the correct imei number');
            return XNext::nothing(); 
        }  
        
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->storeSensorIMEI($data);
        if($result && $result->errno === 0){
            echo ResultSet::jsuccess($result->data);
            return XNext::nothing();
        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }
}

class Action_dobindsensor extends XPostAdminAction
{
    public function _run($request,$xcontext)
    {
        $seqno = $request->seqno;
        $sensorid  = $request->imei;
       
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->storeBSlink($seqno,$sensorid);
        if($result && $result->errno === 0){
            echo ResultSet::jsuccess($result->data);
            return XNext::nothing();
        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }
}
