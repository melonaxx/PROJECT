<?php

/*
|-----------------------------------------------------
| 普通登录验证 Validator By Passwd Action
|-----------------------------------------------------
|
| 这个Action主要负责验证那些普通的登陆。
*/
class Action_validator_by_passwd extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $mobileno = $request->mobileno;
        $passwd   = $request->passwd;
        $clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        $userinfo = UserInfoSvc::ins()->getUserInfoByMobileNo($mobileno); // 验证手机号，以确认是否注册
        if (!$userinfo) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        }

        $result = SecuritySvc::ins()->verifyPasswd($userinfo['userid'], $passwd); // 验证登录密码
        if (!$result) {
            echo ResultSet::jfail(403, "Passwd Is Invalid");
            return XNext::nothing();
        }
        
        $encryptInfo = SecuritySvc::ins()->encryptUserInfo($userinfo['userid'], $clientip); // 加密用户信息，存cookie用
        if (!$encryptInfo) {
            echo ResultSet::jfail(500, "Server Error Of EncryptUserInfo");
            return XNext::nothing();
        }

        $user = UserSvc::ins()->getUserById($userinfo['userid']); // 获取用户的基本信息，做导航栏显示
        
        $sessionid = SecuritySvc::ins()->createSession($userinfo['userid']);

        $loginlog  = LoginLogSvc::ins()->addLoginLog($userinfo['userid'], $clientip); // 记录登陆日志

        $user = $user->toArray();
        $data['userinfo']    = $user;
        $data['encryptinfo'] = $encryptInfo;
        $data['sessionid']   = $sessionid;

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 短信登陆验证 Validator By Note Action
|-----------------------------------------------------
|
| 这个Action主要负责验证那些手机号登陆短信验证的用户。
|
*/
class Action_validator_by_note extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $mobileno = $request->mobileno;
        $note     = $request->note;
        $cookie   = $request->cookie;
        $clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        $client  = GClientAltar::checkNote();
        $result  = $client->checkPhone($cookie, $note); // 验证短信验证码是否正确
        if ($result->errno != 0) {
            echo ResultSet::jfail(403, "Note Is Error");
            return XNext::nothing();
        }

        $userinfo = UserInfoSvc::ins()->getUserInfoByMobileNo($mobileno); // 验证手机号是否注册过，未注册返回错误码
        if (!$userinfo) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        }

        $user = UserSvc::ins()->getUserById($userinfo['userid']); // 获取用户基本信息，前台页面导航做显示用
        if ($user['status'] != User::STATUS_NORMAL) {
            echo ResultSet::jfail(4031, "User Not Register");
            return XNext::nothing();
        }

        $encryptInfo = SecuritySvc::ins()->encryptUserInfo($userinfo['userid'], $clientip); // 加密用户信息，存cookie用
        if (!$encryptInfo) {
            echo ResultSet::jfail(500, "Server Error Of EncryptUserInfo");
            return XNext::nothing();
        }
        
        $sessionid = SecuritySvc::ins()->createSession($userinfo['userid']); // 生成sessionid, 解密用

        $loginlog  = LoginLogSvc::ins()->addLoginLog($userinfo['userid'], $clientip); // 记录登陆日志

        $user = $user->toArray();
        $data['userinfo']    = $user;
        $data['encryptinfo'] = $encryptInfo;
        $data['sessionid']   = $sessionid;

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 后台验证 Login Admin Action
|-----------------------------------------------------
|
| 这个Action主要负责验证后台登陆，后期可做比较全面
| 的验证体系。
|
*/
class Action_login_admin extends XPostAction
{
     public function _run($request, $xcontext)
    {
        $name     = $request->name;
        $passwd   = $request->passwd;
        $clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        $adminuser = AdminUserSvc::ins()->verifyPasswd($name, $passwd); // 后台登陆验证密码
        if (!$adminuser) {
            echo ResultSet::jfail(403, "Passwd Is Invalid");
            return XNext::nothing();
        }

        $status    = LoginLog::LOGIN_ADMIN;
        $loginlog  = LoginLogSvc::ins()->addLoginLog($adminuser['id'], $clientip, $status); // 记录登陆日志

        $adminuser = $adminuser->toArray();

        echo ResultSet::jsuccess($adminuser);
        return XNext::nothing();
    }
}
