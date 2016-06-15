<?php

/**
 * @brief   车辆添加
 */
class Action_platform_caractive extends XPermissionAction
{
    public function _run($request,$xcontext)
    {
        return XNext::useTpl('/car/caractive.html');
    }

    public function getPermission() {
        return new Permission("0.0.1");
    }
}

/**
 * @brief   车辆添加处理
 */
class Action_platform_docaractive extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $num = $request->num;
        if(!$num) {
            echo ResultSet::jfail(400 , "Please complete the serial number ");
            die;return XNext::nothing();
        }

        $nums = explode("\n" , $num);
        $arrnum = array();
        for($u = 0; $u < count($nums); $u++) {
            $numl = rtrim($nums[$u]);
            $numsu = preg_match("/^\d{15,16}$/", $numl);
            if($numsu) {
                $arrnum[] = $numl;
            }
        }
        if(count($arrnum) == 0) {
            echo ResultSet::jfail(4001 , "Please complete the serial number ");
            return XNext::nothing();
        }else{
            $arrnum1 = array_unique($arrnum);
        }
        
        $userid = $xcontext->userid;
        $act = "activate";
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->ebikeAssoc($userid , $arrnum1 , $act);

        if(!$result) {
            echo ResultSet::jfail(500 , "Internal server error ");
            return XNext::nothing();
        }
        
        $errno = $result->errno;
        if($errno == 0) {
            $no = $result->data;
            $success = $no['success'];
            $fail = $no['fail'];

            $data = array("success" => $success , "fail" => $fail);
            echo ResultSet::jsuccess($data);
        }else {
            echo ResultSet::jfail(4002 , "Activation failed ");
        }
        return XNext::nothing();
    }

    public function getPermission() {
        return new Permission("0.0.1");
    }
}

/**
 * @brief   车辆解除激活
 */
class Action_platform_docardeactive extends XPermissionAuthAction
{
    public function _run($request,$xcontext)
    {
        $num = $request->num;
        if(!$num) {
            echo ResultSet::jfail(400 , "Please complete the serial number ");
            return XNext::nothing();
        }
        $arrnum = array();
        $nums = explode("\n" , $num);
        for($u = 0; $u < count($nums); $u++) {
            $numl = rtrim($nums[$u]);
            $numsu = preg_match("/^\d{15,16}$/", $numl);
            if($numsu) {
                $arrnum[] = $numl;
            }
        }
        if(count($arrnum) == 0) {
            echo ResultSet::jfail(4001 , "Please complete the serial number ");
            return XNext::nothing();
        }else{
            $arrnum1 = array_unique($arrnum);
        }

        $userid = $xcontext->userid;
        $act = "unwrap";
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->ebikeAssoc($userid , $arrnum1 , $act);

        if(!$result) {
            echo ResultSet::jfail(500 , "Internal server error ");
            return XNext::nothing();
        }

        $errno = $result->errno;
        if($errno == 0) {
            $no = $result->data;
            $success = $no['success'];
            $fail = $no['fail'];

            $data = array("success" => $success , "fail" => $fail);
            echo ResultSet::jsuccess($data);
            return XNext::nothing();
        }else {
            echo ResultSet::jfail(4002 , "Unbound failure ");
            return XNext::nothing();
        }

    }

    public function getPermission() {
        return new Permission("0.0.2");
    }
}