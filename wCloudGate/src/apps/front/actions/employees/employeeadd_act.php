<?php

/**
 * @brief   员工添加界面
 */
class Action_employeesadd extends XPermissionAction
{
    public function _run($request,$xcontext)
    {
        return XNext::useTpl('/employees/employees_add.html');
    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEADD);
    }
}

/**
 * @brief   员工查询 按手机号
 */
class Action_employeesaddsearch extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
    	$mobileno = $request->mobileno;
        if(!$mobileno ) {
            echo ResultSet::jfail(400 , "The mobileno information missing ");
            return XNext::nothing();
        }    	
        if(!preg_match("/^1\d{10}$/", $mobileno)) {
    		echo ResultSet::jfail(4001 , "The phone number is eleven ");
            return XNext::nothing();
    	}

    	$client = GClientAltar::getPlatformClient();
        $result = $client->searchEmployeeByMobileno(intval($mobileno) );
        if( $result && $result->errno == 0 ) {
            $data = $result->data;
            echo ResultSet::jsuccess($data);
            return XNext::nothing();
        }else if($result->errno == 404) {
            echo ResultSet::jfail(404 , "user not found ");
            return XNext::nothing();            
        }else{
        	echo ResultSet::jfail(403 , "The phone number not found ");
            return XNext::nothing();
        }

        echo ResultSet::jfail(4031 , 'The request failed');
        return XNext::nothing();

    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEADD);
    }
}

/**
 * @brief   员工添加
 */
class Action_employeesinsert extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
    	$mobileno = $request->mobileno;
    	$userid = $xcontext->userid;
    	$client = GClientAltar::getWCloudGateClient();
        $result = $client->addEmployee($userid , intval($mobileno) );
        if( $result && $result->errno == 0 ) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }elseif($errno == 500) {
        	echo ResultSet::jfail(500 , 'Add employees failed');
            return XNext::nothing();
        }

        echo ResultSet::jfail(4031 , 'The request failed');
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission(HavePermission::EMPLOYEEADD);
    }
}