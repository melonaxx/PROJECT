<?php

/*
 * @brief   注册手机动态码获取
 */
class Action_registerphone extends XAction
{
    public function _run($request , $xcontext) 
    {
        return XNext::useTpl('loginphone.html');
    }
}

/**
 * @brief   手机短信动态码
 */
class Action_doregisterphone extends XPostAuthAction
{
    public function _run($request , $xcontext) 
    {

        $phone = $request->mobileno;
        $smscode = $request->note;
        if(!$phone || !$smscode ) {
            echo ResultSet::jfail(400 , "The register information missing ");
            return XNext::nothing();
        }
        if(!preg_match("/^1\d{10}$/" , $phone)) {
            echo ResultSet::jfail(4001 , "The phone number is eleven ");
            return XNext::nothing();
        }
        
        $cookieid = $_COOKIE['NOTE_COOKIE'];
        // if(!$cookieid) {
        //     echo ResultSet::jfail(4041, "cookie is not found ");
        //     return XNext::nothing();
        // }
        $result = $client->registeruser($phone , $smscode , $cookieid);
        if(!$result) {
            echo ResultSet::jfail(500 , "Internal server error ");
            return XNext::nothing();
        }
// echo "<pre>";var_dump($result);echo $cookieid;die;        
        $errno = $result->errno;     
        if($errno == 0) {
            $data = $result->data;
            $user = $data['userinfo'];
            $status = $user['status'];
            $userid = $user['id'];
            $sessionid = $data['sessionid'];
            $encryptinfo = $data['encryptinfo'];
            UserSvc::ins()->setCookie($userid , $sessionid , $encryptinfo);

            echo ResultSet::jsuccess("");
            return XNext::nothing();
        }

        if($errno == 404) {
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
