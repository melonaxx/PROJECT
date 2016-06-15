<?php

/**
 * @brief   登录
 * 登录成功后跳转到登录之前页面
 */
class Action_login extends XAction
{
    public function _run($request,$xcontext)
    {
        list($retcode, $user) = UserSvc::ins()->getUserInfoFromCookie();
        if ($retcode === 0) {
            // 身份验证通过
            $xcontext->auth_errtype = 0;
            $xcontext->userid = $user['id'];
            $xcontext->username = $user['name'];
            $xcontext->phone = $user['mobileno'];
            $xcontext->userinfo = $user;
            $xcontext->status = $user['status'];
            $xcontext->usertype = $user['usertype'];
        } else {
            $xcontext->auth_errtype = $retcode;
            $xcontext->userid = 0;
            $xcontext->username = "";
            $xcontext->phone = "";
            $xcontext->userinfo = null;
            $xcontext->status = "";
            $xcontext->usertype = "";
        }
        if ($xcontext->userid) {
            $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainUrl);
        }

        return XNext::useTpl('login/login.html');
    }
}

/**
 * @brief   登录判断
 */
class Action_dologin extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $phone = $request->phone;
        $password = $request->password;
        if(!$phone || !$password) {
            echo ResultSet::jfail(400 , "The login information missing ");
            return XNext::nothing();
        }
        if(!preg_match("/^1\d{10}$/" , $phone)) {
            echo ResultSet::jfail(4001 , "The phone number is eleven ");
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

        $client = GClientAltar::getWCloudGateClient();
        $result = $client->validatorByPasswd($phone , $password );
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
            }else {
                echo ResultSet::jsuccess("");
                return XNext::nothing();
            }
        }

        if($errno == 404) {
            echo ResultSet::jfail(404 , "User not found ");
            return XNext::nothing();
        }
        if($errno == 403) {
            echo ResultSet::jfail(403, "Server Error:passwd varify has failed ");
            return XNext::nothing();
        }
        if($errno == 500) {
            echo ResultSet::jfail(5001, "encrypt user info has failed ");
            return XNext::nothing();
        }

    }
}

/**
 * @brief   退出
 */
// TODO Action_logout
class Action_logout extends XAction
{
    public function _run($request , $xcontext)
    {
        setcookie('U' , '' , time()-3600 );
        setcookie('S' , '' , time()-3600 );

        $loginUrl = "http://" . $_SERVER['DOMAIN'] . "/index.php";
        return XNext::gotoUrl($loginUrl);

    }
}
