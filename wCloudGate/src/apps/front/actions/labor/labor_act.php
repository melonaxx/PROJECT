<?php

class Action_labor_labormanage extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $usertype = $xcontext->usertype;
        if($usertype == 2 || $usertype == 4) {
            $mainurl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainurl);
        }
        $userid   = $xcontext->userid;
        $employee = $request->employee;
        $name     = $request->laborname;
        $num      = $request->num ? $request->num : 1;
        $page     = $_SESSION['page'];
        $employid = $request->employee;
        $xcontext->namer = $name;         
        if($usertype === "1"){
            $condi    = array('page'=>$page,'num'=>$num,'employeeid'=>$employid,'name'=>$name);
            $client = GClientAltar::getPlatformClient();
            $result = $client->showLaborInfo($userid,$condi,$sort);
            if($result->errno != 0 && $result->errno != 404){
                echo ResultSet::jfail($result->errno,$result->errmsg);
                return XNext::nothing();
            }
            //获取平台下的员工
            $resultt = $client->getEmployeeList($userid); 
            if($resultt->errno != 0 && $resultt->errno != 404){
                echo ResultSet::jfail($resultt->errno,$resultt->errmsg);
                return XNext::nothing();
            }
            $xcontext->emp = $resultt->data;
            $data    = $result->data;
            $total   = array_pop($data);
            $pageAll = array_pop($data);
            $xcontext->arr     = $data;
            $xcontext->pageAll = $pageAll;
            $xcontext->total   = $total;
            $xcontext->employeeid = $employee;
            return XNext::useTpl("/labor/labor.html");   
        }else{
            $condi  = array('page'=>$page,'num'=>$num,'name'=>$name);
            $client = GClientAltar::getwCloudGateClient($userid);
            $result = $client->showLaborInfoFromEmp($userid,$condi);
            if($result->errno != 0 && $result->errno != 404)
            {
                echo ResultSet::jfail($result->errno,$result->errmsg);
                return XNext::nothing(); 
            }
            $data    = $result->data;
            $total   = array_pop($data);
            $pageAll = array_pop($data);
            $xcontext->pageAll = $pageAll;
            $xcontext->total = $total; 
            $xcontext->arr  = $data;

            return XNext::useTpl("/labor/labor-employee.html"); 
        }

    }

}

class Action_labor_addlabor extends XPermissionAction 
{
    public function _run($request,$xcontext)
    {
        $name = $request->value;
        if(!empty($name)){
            $userid = $xcontext->userid;
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->searchLaborByName($name,$userid);
            if($result->errno !=0  && $result->errno != 404){
                echo ResultSet::jfail($result->errno,$result->errmsg);
                return XNext::nothing();
            }    

            $xcontext->arr = $result->data;
            $xcontext->name = $name;
        }

        $clientt = GClientAltar::getPlatformClient();
        $resultt = $clientt->getEmployeeList($xcontext->userid);
        if($resultt->errno != 0 && $resultt->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }  

        $employee = $resultt->data;
        $xcontext->emp = $employee;
        return XNext::useTpl("/labor/labor_addsearch.html");
   
    }

    public function getPermission() {
        return new Permission("0.1.0");
    }
}

/*
 *平台->劳务方->车辆管理->查看
 */
class Action_labor_seeebike extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid  = $xcontext->userid;
        $laborid = $request->laborid;
        
        $client = GClientAltar::getPlatformClient();
        $result = $client->showEbikeInfoFromLabor($userid,$laborid);
        
        if($result && $result->errno === 0){
            $data = $result->data;
            $account = array_pop($data);
            for($i=0;$i<count($data);$i++){
                if($data[$i]['companyid'] !== $account){
                    $data[$i]['companyid'] = "";
                }
            }

            echo ResultSet::jsuccess($data);
            return XNext::nothing();
        }

        echo ResultSet::jfail($result->errno,$result->eerrmsg);
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission("0.2.0");
    }

}

/*
 *平台->劳务方->分配车辆(显示车辆)
 */
class Action_distribute extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $usertype = $xcontext->usertype;
        $userid   = $xcontext->userid;
        if($usertype === "1"){
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->showDistributeableEbike($userid);
        
            if($result->errno != 0 && $result->errno != 404){
                echo ResultSet::jfail($result->errno,$result->errmsg);
                return XNext::nothing();
            }

            echo ResultSet::jsuccess($result->data); 
            return XNext::nothing(); 
        }else{
            $client = GClientAltar::getWCloudGateClient();
            $result = $client->showDistributeableEbike($userid);
            
            if($result->errno != 0 && $result->errno != 404){
                echo ResultSet::jfail($result->errno,$result->errmsg);
                return XNext::nothing();
            }
            
            $data = $result->data;
            $arr  = array();
            foreach($data as $v){
                if($v['userid'] === $userid){
                    $arr[] = $v;
                }
            }
            echo ResultSet::jsuccess($arr); 
            return XNext::nothing(); 
        }       
    }
}

class Action_setpage extends XPostAction
{
    public function _run($request,$xcontext)
    {
        $page = XParamFilter::htmlNumber($request->page);
        if($page === ""){
            echo ResultSet::jfail(403,' invalid paramete');
            return XNext::nothing();
        }

        $_SESSION['page'] = $page;
        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

//解除员工车辆关系
class Action_ubrelation extends XPostAction
{
    public function _run($request,$xcontext)
    {
        $seqno  = $request->seqno;
        $userid = $request->userid;
        $client = GClientAltar::getPlatformClient();
        $result = $client->cancleDistributeEbike($seqno,$userid,'emp');

        if($result->errno != 0){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($result->errno);
        return XNext::nothing();
    }
}
