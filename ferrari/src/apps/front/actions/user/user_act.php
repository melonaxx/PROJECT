<?php

class Action_user_listuser extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("user/listuser.html");
    }
}

class Action_user_adduser extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $op = intval($_POST['op']);

        if (!$op) {
            return XNext::useTpl("user/adduser.html");
        } else {
            return $this->doAdd($request, $xcontext);
        }
    }

    private function doAdd($request, $xcontext)
    {
        $username = $_POST['name'];
        $pwd = $_POST['pwd'];

        if (!$username || !$pwd) {
            $xcontext->errinfo = ResultSet::fail(-1, "参数不能为空");
            return XNext::useTpl("user/adduser.html");
        }

        $password = XParamFilter::checkPassword($pwd);
        if (!$password) {
            $xcontext->errinfo = ResultSet::fail(-1, "密码长度至少为6位。不能是汉字。");
            return XNext::useTpl("user/adduser.html");
        }

        // 首先检查用户名是否已被使用
        $user = UserSvc::ins()->getUserByName($username);
        if ($user) {
            $xcontext->errinfo = ResultSet::fail(1, "用户名已被使用");
            return XNext::useTpl("user/adduser.html");
        }

        // 新增用户
        $newuser = UserSvc::ins()->addUser($username, $password);
        if (!$newuser) {
            $xcontext->errinfo = ResultSet::fail(-1, "服务器错误，添加用户失败");
            return XNext::useTpl("user/adduser.html");
        }

        // 新增用户成功，跳转到用户的详情页
        $serverUri = "http://" . $_SERVER['HTTP_HOST'];
        return XNext::gotoUrl($serverUri . "/user/viewuser.php?uid=" . $newuser['id']);
    }
}

