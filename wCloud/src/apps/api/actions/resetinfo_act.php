<?php

/*
|-----------------------------------------------------
| 修改用户的信息 Update UserInfo Action
|-----------------------------------------------------
|
| 这个Action主要负责修改用户的信息，可修改的信息有
| 姓名和邮箱，以后有其它信息可扩展。
|
*/
class Action_update_userinfo extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $data   = $request->data;
        $userid = $request->userid;
        $name   = $data['name'];
        $email  = $data['email'];

        if ($name) {
            /* 这里处理修改姓名 */
            $new_user = UserSvc::ins()->updateUserById($userid, $name);
            if (!$new_user) {
                echo ResultSet::jfail(500, "Server Error Of UpdateUser");
                return XNext::nothing();
            }
        }

        if ($email) {    
            /* 这里处理修改邮箱 */
            $new_userinfo = UserInfoSvc::ins()->updateUserInfoByUserId($userid, $email);
            if (!$new_userinfo) {
                echo ResultSet::jfail(500, "Server Error Of Update UserInfo");
                return XNext::nothing();
            }
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
     }
}

/*
|-----------------------------------------------------
| 修改用户的密码 update passwd action
|-----------------------------------------------------
|
| 这个Action主要负责修改用户的密码，后期扩展可增加
| 一些其它的字段。
|
*/
class Action_update_passwd extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $passwd = $request->passwd;

        $security = SecuritySvc::ins()->updateSecurity($userid, $passwd);
        if (!$security) {
            echo ResultSet::jfail(500, "Server Error Of UpdatePasswd");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
      }
}

/*
|-----------------------------------------------------
| 修改后台用户信息 update adminuserinfo action
|-----------------------------------------------------
|
| 这个Action主要负责修改后台的一些用户信息，只要包
| 括修改用户的信息，和密码。
|
*/
class Action_update_adminuserinfo extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $name   = $request->name;
        $userid = $request->userid;
        $passwd = $request->passwd;

        if ($name) { // 执行修改用户名
            $result = AdminUserSvc::ins()->updateName($userid, $name);       
            if (!$result) {
                echo ResultSet::jfail(4031, "UpdateName Fail");
                return XNext::nothing();
            }
        }

        if ($passwd) { // 执行修改密码
            $result = AdminUserSvc::ins()->updatePasswd($userid, $passwd);       
            if (!$result) {
                echo ResultSet::jfail(4031, "UpdatePasswdFail");
                return XNext::nothing();
            }
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}
