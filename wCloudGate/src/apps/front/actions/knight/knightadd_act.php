<?php 

/**
 * @brief   骑士添加页面
 */
class Action_knightadd extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype != UserType::LABOR) {
            $mainurl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainurl);
        }
        return XNext::useTpl('/knight/knight_add.html');
    }
}

/**
 * @brief   骑士查询 按手机号
 */
class Action_knightaddsearch extends XKnightPostAuthAction
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
        if($result && $result->errno == 0) {
            $data = $result->data;
            echo ResultSet::jsuccess($data);
        }else if($result->errno == 404) {
            echo ResultSet::jfail(404 , "user not found ");
        //403  500
        }else{
            echo ResultSet::jfail(403 , "The phone number not found ");
        }

        return XNext::nothing();
    }
}

/**
 * @brief   骑士添加
 */
class Action_knightaddinsert extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $mobileno = $request->mobileno;
        $userid = $xcontext->userid;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->addEmployee($userid , intval($mobileno) );
        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(500 , 'Add knight failed');
        return XNext::nothing();

    }
}

