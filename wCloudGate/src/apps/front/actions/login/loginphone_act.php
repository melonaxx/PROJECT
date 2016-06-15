<?php

/**
 * @brief   手机动态码登录
 */
class Action_dologinphone extends XPostAuthAction
{
    public function _run($request ,  $xcontext)
    {
        $phone      = $request->phone;
        $smscode    = $request->smscode;
        if(!$phone || !$smscode ) {
            echo ResultSet::jfail(400 , "The phone login information missing ");
            return XNext::nothing();
        }
        if(!preg_match("/^1\d{10}$/" , $phone)) {
            echo ResultSet::jfail(4001 , "The phone number is eleven ");
            return XNext::nothing();
        }
        if(!preg_match("/^\d{4}$/", $smscode)) {
            echo ResultSet::jfail(4002 , "The sms number is four ");
            return XNext::nothing();
        }

        $times = $request->times;
        if(!preg_match("/^[0,1]{1}$/", $times)) {
            $times = 0;
        }else if($times == 0) {
            $times = 0;
        }else if($times == 1) {
            $times = 1;
        }

        $cookieid = $_COOKIE['NOTE_COOKIE'];
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->validatorByNote($phone , $smscode , $cookieid);
        if(!$result) {
            echo ResultSet::jfail(500 , "Internal server error ");
            return XNext::nothing();
        }

        $errno = $result->errno;
        if($errno == 0) {
            $data = $result->data;
            $user = $data['userinfo'];
            $status = $user['status'];
            $userid = $user['id'];
            $sessionid = $data['sessionid'];
            $encryptinfo = $data['encryptinfo'];
            UserSvc::ins()->setCookie($userid , $sessionid , $encryptinfo , $times);

            if($status != "0") {
                //"Web page redirects " 
                $data = $status;
                echo ResultSet::jsuccess($data);
                return XNext::nothing();                
            }

            echo ResultSet::jsuccess("");
            return XNext::nothing();
        }

        if($errno == 404 || $errno == 4031) {
            echo ResultSet::jfail(404 , "User not found ");
            return XNext::nothing();
        }
        if($errno == 403) {
            echo ResultSet::jfail(403, "Server Error:sms number varify has failed ");
            return XNext::nothing();
        }
        if($errno == 500) {
            echo ResultSet::jfail(5001, "encrypt user info has failed ");
            return XNext::nothing();
        }     


    }
}
