<?php

/**
 * @brief   注册
 */
class Action_register extends XAction
{
    public function _run($request,$xcontext)
    {        
        return XNext::useTpl('register/register.html');
    }
}

/**
 * @brief   注册处理
 */
class Action_doregister extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $phone      = $request->phone;
        $smscode    = $request->smscode;
        $password   = $request->password;
        $repassword = $request->repassword;
        if(!$phone || !$smscode || !$password || !$repassword) {
            echo ResultSet::jfail(400 , "The register information missing ");
            return XNext::nothing();
        }
        if(!preg_match("/^1\d{10}$/" , $phone)) {
            echo ResultSet::jfail(4001 , "The phone number is eleven ");
            return XNext::nothing();
        }        
        if(!preg_match("/^\d{4}$/" , $smscode)) {
            echo ResultSet::jfail(4002 , "The smscode is four ");
            return XNext::nothing();
        }
        $sp = strlen($password);
        if($sp < 6 || $sp > 22 ) {
            echo ResultSet::jfail(4003 , "The password is six-twentytwo ");
            return XNext::nothing();
        }
        if($password !== $repassword ) {
            echo ResultSet::jfail(4004 , "The twice password is not the same ");
            return XNext::nothing();
        }        

        // Get verification code access sessionid cook name
        $cookieid = $_COOKIE['NOTE_COOKIE'];
        if(!$cookieid) {
            echo ResultSet::jfail(4005, "cookie is not found ");
            return XNext::nothing();            
        }

        $client = GClientAltar::getWCloudGateClient();
        $result = $client->registeruser($phone , $password , $smscode, $cookieid);

        if(!$result) {
            echo ResultSet::jfail(5001 , "Internal server error ");
            return XNext::nothing();
        }

        $errno = $result->errno;
        if($errno === 0) {
            $data = $result->data;
            $userid = $data['userid'];
            $sessionid = $data['sessionid'];
            $encryptinfo = $data['encryptInfo'];
            UserSvc::ins()->setCookie($userid , $sessionid , $encryptinfo);

            echo ResultSet::jsuccess("");
        } else {
            if ($errno == 403) {
                echo ResultSet::jfail(403, "Note is error ");
            } else if ($errno == 4031) {
                echo ResultSet::jfail(4031, "Registration failed "); 
            } else if ($errno == 500) {
                echo ResultSet::jfail(5001, "encrypt user info has failed ");
            } else {
                echo ResultSet::jfail(500, "Server Error ");
            }
        }

        return XNext::nothing();
    }
}
