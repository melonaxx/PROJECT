<?php

/*
|-----------------------------------------------------
| 修改权限 Update Permission Action
|-----------------------------------------------------
|
| 这个Action主要负责为员工修改或者添加权限，后期扩展
| ，可重写次Action，可扩展更多的权限。
|
*/
class Action_update_permission extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $auth   = $request->auth;

        $result = UserSvc::ins()->updatePermissonById($userid, $auth);
        if (!$result) {
            echo ResultSet::jfail(500, "Server Error Of UpdatePermission");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    } 
}
