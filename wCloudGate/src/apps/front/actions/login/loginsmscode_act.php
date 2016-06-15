<?php

/**
 * @brief   手机短信动态码
 */
class Action_dologinphonesmscode extends XAction
{
    public function _run($request , $xcontext) 
    {
        $phone = $request->phone;
        $captcha =  $request->captcha;
        if(!$phone || !$captcha) {
            echo ResultSet::jfail(400 , "The SMS to obtain information missing ");
            return XNext::nothing();
        }
        if(!preg_match("/^1\d{10}$/" , $phone)) {
            echo ResultSet::jfail(4001 , "The phone number is eleven ");
            return XNext::nothing();
        }
        if(!preg_match("/^[0-9a-zA-Z]{4}$/", $captcha)) {
            echo ResultSet::jfail(4002 , "The captcha number is four ");
            return XNext::nothing();
        }

        $client = GClientAltar::getWOreoClient();
        $result = $client->checkCode(strtolower($captcha));

        if(!$result) {
            echo ResultSet::jfail(500 , "Internal server error ");
            return XNext::nothing();
        }
        if( !$result->data) {
            echo ResultSet::jfail(4003 , "The captcha is error ");
            return XNext::nothing();
        }

        $typenote = SMSCode::LOGIN;
        $pclient = GClientAltar::getWOreoClient();
        $presult = $pclient->sendCode($phone , $typenote);
     
        if($presult) {
            echo ResultSet::jsuccess("");
            return XNext::nothing();
        }else {
            echo ResultSet::jfail(5001, "fail to send smscode");
            return XNext::nothing();
        }

    }
}
