<?php

class Action_accountset extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->getAccountInfo($userid);
        if($result->errno != 0 && $result->errno != 404){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        $xcontext->info = $result->data;
    	return XNext::useTpl('accountset/accountset.html');
    }
}

class Action_accountset_certification extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype == 4) {
            $mainurl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainurl);
        }
        $userid = $xcontext->userid;
        $xcontext->usertype = $xcontext->usertype;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->getCompanyInfo($userid);
        if($result->errno != 0 && $result->errno != 404)
        {
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return XNext::nothing();
        }
        $data = $result->data;
        $xcontext->info = $data;
        $imgname = $data['licence'];
        $imgurl = "http://" . UploadLicense::UPLOADPIC . "/" . $imgname;
        $xcontext->imgurl = $imgurl;
    	return XNext::useTpl('/accountset/accountset_certification.html');
    }
}

class Action_accountset_forgotpassword extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $userid = $request->userid;
        $mobileno = $request->mobileno;
        $xcontext->mobileno = $mobileno;
        $xcontext->userid = $userid;
        return XNext::useTpl('accountset/accountset_forgotpassword.html');
    }
}

class Action_accountset_mynews extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
    	return XNext::useTpl('accountset/accountset_mynews.html');
    }
}

class Action_accountset_docheckcaptcha extends XPostAction
{
    public function _run($request,$xcontext)
    {
        $captcha = $request->captcha;
        $client = GClientAltar::getWOreoClient();
        $result = $client->checkCode($captcha);
        $stat = $result->errno;

        echo ResultSet::jsuccess($stat);
        return XNext::nothing();
    }
}

class Action_accountset_updatename extends XGetAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $name = $request->name;

        $data = array("name"=>$name);
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->updateUserInfo($userid,$data);
        if($result->errno != 0){
            echo ResultSet::jfail($result->errno,$result->errmsg);
            return xNext::nothing();
        }

        echo ResultSet::jsuccess($result->errno);
        return XNext::nothing();
    }
}


class Action_accountset_updateemail extends XGetAuthAction
{

    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $email = $request->email;

        $data = array("email"=>$email);
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->updateUserInfo($userid,$data);
        if($result->errno != 0){
            echo ResultSet::jfail($result->errno);
            return xNext::nothing();
        }

        echo ResultSet::jsuccess($result->errno);
        return XNext::nothing();
    }
}

class Action_accountset_sendcode extends XPostAction
{
    public function _run($request,$xcontext)
    {
        $mobileno = $request->mobileno;

        $client = GClientAltar::getWOreoClient();
        $result = $client->sendCode($mobileno,SMSCode::UPDATEPHONE);

        if ($result) {
            echo ResultSet::jsuccess($result);
        } else {
            echo ResultSet::jfail(5001, "fail to get smscode");
        }

        return XNext::nothing();
    }
}

class Action_accountset_checknote extends XPostAction
{
    public function _run($request,$xcontext)
    {
        $code = $request->code;
        $cookid = $_COOKIE['NOTE_COOKIE'];

        $client = GClientAltar::getWOreoClient();
        $result = $client->checkPhone($cookid,$code);
        $data = $result->errno;

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

class Action_accountset_doupdatepasswd extends XPostAction
{
    public function _run($request,$xcontext)
    {
        $userid = $request->userid;
        $passwd = $request->pwd1;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->updatePasswd($userid,$passwd);

        if($result->errno === 0){
            $ucookie = 'U';
            $scookie = 'S';
            setcookie($ucookie , '' );
            setcookie($scookie , '' );

            echo ResultSet::jsuccess($result->errno);
            return XNext::nothing();
        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }
}

class Action_accountset_updatepassword extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        $url = explode('/',$_SERVER['HTTP_REFERER']);
        $val = $url[(count($url)-1)];
        if($val != "forgotpassword.php"){
            header('Location:/accountset/forgotpassword.php');
            return XNext::nothing();
        }

        $xcontext->userid = $xcontext->userid;
        return XNext::useTpl("/accountset/accountset_updatepasswd.html");
    }
}
