<?php

/**
 * @brief   员工 
 */
class Action_employees extends XPermissionAction
{
    public function _run($request,$xcontext)
    {
        $searchdata = array();
        $num = $request->num;
        $page = $request->page;
        $pageall = $request->pageall;
        $name = $request->name;

       if($name || $num || $page ) {
            if($num < 1)   $num = 1;
            if($num > $pageall)  $num = $pageall;
            $searchdata = array(
                'name' => htmlspecialchars($name),
                'num' => intval($num),
                'page' => intval($page)
            );               

        }

        $userid = $xcontext->userid;
        $client = GClientAltar::getPlatformClient();
        $result = $client->getemployee($userid  , $searchdata);

        if($result && $result->errno == 0) {
            $data = $result->data;
            if($data && is_array($data)) {
                $pageall = array_pop($data);
                $pagenum = array_pop($data);
            }
        }
        
        $xcontext->pageall = $pagenum ? $pagenum : 0; //共几页        
        $xcontext->count  =$pageall ? $pageall : 0; //共几条
        if(!$num)  $num = 1;          
        if(!$page)  $page = 10;
        $xcontext->num = $num;
        $xcontext->page = $page;
        $xcontext->data = $data;
        $xcontext->usertype = $xcontext->usertype;

        $client = GClientAltar::getWCloudGateClient();
        $state = "employee";
        $resulltt = $client->showDistributeableEbike($userid , $state);
        $xcontext->ebike = $resulltt->data ? $resulltt->data : "";
        
        return XNext::useTpl('/employees/employees.html');
    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEADD);
    }
}

/**
 * @brief   员工拥有车辆
 */
class Action_employeeebike extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $employeeid = $request->employeeid;
        $client = GClientAltar::getWcloudGateClient();
        $result = $client->showEbikeInfoFromEmp(intval($employeeid) );

        if($result && $result->errno == 0) {
            $data = $result->data ? $result->data : "";
            echo ResultSet::jsuccess($data);
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

/**
 * @brief   员工分配车辆
 */
class Action_employeedistributeebike extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $employeeid = $request->employeid;
        $arr = $request->larr;
        $client = GClientAltar::getWcloudGateClient();
        $result = $client->allotEbikeToUser(intval($employeeid) , $arr);

        if($result && $result->errno == 0) {            
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

/**
 * @brief   员工取消分配车辆
 */
class Action_employeeundistribute extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $employeeid = $request->employeeid;
        $seqno = $request->larr;
        $client = GClientAltar::getPlatformClient();
        $result = $client->cancleDistributeEbike(intval($seqno) , intval($employeeid) ,  "emp");

        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

/**
 * @brief   员工权限显示
 */
class Action_employeeshowpermission extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $permission = $request->permission;

        $objpermission = new Permission($permission);
        $authity = PermissionEnum::havepermission();

        for($i = 0; $i < count($authity); $i++){
            if($objpermission->allow($authity[$i][0] , $authity[$i][1])) {
                $checked[$i]['permission'] = $authity[$i][1];
                $checked[$i]['state'] = 1;
            }else{
                $checked[$i]['permission']= $authity[$i][1];
                $checked[$i]['state'] = 0;
            }
            
        }

        echo ResultSet::jsuccess($checked);
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

/**
 * @brief   员工权限修改
 */
class Action_employeepermissionadd extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $arrchecked = $request->arrchecked;
        $employeeid = $request->employeeid;

        $objpermission = new Permission();
        foreach($arrchecked as $k => $v) {
            if($k) {
                for($i = 0; $i < count($v); $i++) {
                    $tostring = $objpermission->add($k , $v[$i]);
                    $stringpermission = $tostring->toString();
                }
            }
        }

        $client = GClientAltar::getWcloudGateClient();
        $result = $client->updatePermission(intval($employeeid) , $stringpermission);
        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

/**
 * @brief   未分配劳务方
 */
class Action_employeesundislabor extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $client = GClientAltar::getPlatformClient();
        $result = $client->showDistributableLabor($userid );

        if($result && $result->errno == 0) {
            $data = $result->data ? $result->data : "";
            echo ResultSet::jsuccess($data);
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

/**
 * @brief   员工分配劳务方
 */
class Action_employeeslabor extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $employeid = $request->employeid;
        $larr = $request->larr;
        $client = GClientAltar::getPlatformClient();
        $result = $client->distributeLaborToEmployee($userid , $larr , intval($employeid) );

        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

/**
 * @brief   员工解绑 单个劳务方
 */
class Action_employeesunlabor extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $labor = $request->larr;
        $client = GClientAltar::getPlatformClient();
        $result = $client->cancleDistributeLabor($userid , intval($labor) );

        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

/**
 * @brief   员工删除
 */
class Action_employeesdel extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
    	$employeid = $request->employeid;
    	$userid = $xcontext->userid;
    	$client = GClientAltar::getPlatformClient();
        $result = $client->removeEmployee($userid , intval($employeid) );

        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();  	

    }
    
    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEALL);
    }

}

