<?php

/*
|-----------------------------------------------------
| 用户注册 Register User Action
|-----------------------------------------------------
|
| 这个Action主要负责注册用户, 注册分两种情况，一种
| 正常注册，另一种是公司预注册了，不过都是走同
| 样的流程，注册完就默认设置为登陆状态了。
|
*/
class Action_register_user extends XPostAction
{
	public function _run($request, $xcontext)
	{
        $note     = $request->note;
        $cookie   = $request->cookie;
		$mobileno = $request->mobileno;
		$passwd   = $request->passwd;
        $clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        $client   = GClientAltar::checkNote();
        $result   = $client->checkPhone($cookie, $note); // 验证短信验证码
        if ($result->errno != 0) {
            echo ResultSet::jfail(403, "Note Is Error");
            return XNext::nothing();
        }

        $userinfo = UserInfoSvc::ins()->getUserInfoByMobileNo($mobileno);
        if (!$userinfo) {
            /* 普通注册 */
            $new_user = UserSvc::ins()->addUser($mobileno, $passwd);
            if (!$new_user) {
                echo ResultSet::jfail(5001, "Normal Register Fail");
                return XNext::nothing();
            }
        } else {
            /* 公司为员工预注册了，这里添加密码 */
            $user     = UserSvc::ins()->getUserById($userinfo['userid']);
            if ($user['status'] != User::STATUS_NOT_REGISTER) {
                echo ResultSet::jfail(4031, "User Is Exists");
                return XNext::nothing();
            }

            $security = SecuritySvc::ins()->createSecurity($userinfo['userid'], $passwd);
            if (!$security) {
                echo ResultSet::jfail(5001, "Register Is Fail");
                return XNext::nothing();
            }

            $new_user = UserSvc::ins()->updateUserStauts($userinfo['userid'], $status=User::STATUS_FIRST_LOGIN); // 注册成功，对应状态改变
            if (!$new_user) {
                echo ResultSet::jfail(500, "Server Error Of UpdateStatus");
                return XNext::nothing();
            }
        }

        $encryptInfo = SecuritySvc::ins()->encryptUserInfo($new_user['id'], $clientip); // 注册完，即默认登录了，这里加密信息存cookie用
        if (!$encryptInfo) {
            echo ResultSet::jfail(5001, "Server Error Of EncryptInfo");
            return XNext::nothing();
        }

        $sessionid = SecuritySvc::ins()->createSession($new_user['id']); // 生成sessionid, 做解密用

        $loginlog  = LoginLogSvc::ins()->addLoginLog($new_user['id'], $clientip); // 记录登陆日志

        $data['userid']      = $new_user['id'];
        $data['mobileno']    = $mobileno;
        $data['sessionid']   = $sessionid;
        $data['encryptInfo'] = $encryptInfo;

		echo ResultSet::jsuccess($data);
		return XNext::nothing();
	}
}

/*
|-----------------------------------------------------
| 完善用户信息 Complete UserInfo Action
|-----------------------------------------------------
|
| 这个Action主要负责完善用户信息，包括一些基本信息
| 姓名，和一些详细信息，邮箱，QQ等。
|
*/
class Action_complete_userinfo extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $name   = $request->name;
        $email  = $request->email;
        $qq     = $request->qq;
        $wechat = $request->wechat;

        $new_user = UserSvc::ins()->updateUserById($userid, $name); // 完善用户的基本信息
        if (!$new_user) {
            echo ResultSet::jfail(500, "Server Error Of CompleteInfo");
            return XNext::nothing();
        }

        $new_userinfo = UserInfoSvc::ins()->updateUserInfoByUserId($userid, $email, $qq, $wechat); // 完善用户详情信息
        if (!$new_userinfo) {
            echo ResultSet::jfail(500, "Server Error Of CompleteDetail");
            return XNext::nothing();
        }

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid);

        $status = $uclink ? User::STATUS_NORMAL : User::STATUS_COMPLETE_INFO; // 如果用户已被邀请，就直接进入，否则等待邀请或认证

        $result = UserSvc::ins()->updateUserStauts($userid, $status); // 普通完善流程，需等待被邀请或者去认证公司

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 存储公司认证信息 Store CompanyInfo Action
|-----------------------------------------------------
|
| 这个Action主要负责将公司提交的认证信息入库，方便
| 后台显示做审核用。
|
*/  
class Action_store_companyinfo extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $city        = $request->city;
        $name        = $request->name;
        $linkman     = $request->linkman;
        $mobileno    = $request->mobileno;
        $email       = $request->email;
        $registerid  = $request->registerid;
        $licence     = $request->licence;
        $companytype = $request->companytype;
        $userid      = $request->userid;

        $new_company = CompanySvc::ins()->addCompany($city, $name, $linkman, $mobileno, $email, $registerid, $licence, $companytype, $userid);
        if (!$new_company) {
            echo ResultSet::jfail(500, "Server Error Of AddCompany");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}
