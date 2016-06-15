<?php

/*
|---------------------------------------------------
| 添加黑名单 Add BlackList Action
|---------------------------------------------------
|
| 这个Action主要负责添加哪些不合法，或操作违背
| 设置的规则的账号，如果是公司的话，其公司
| 下的员工，也将受限，目前未考虑全，后期
| 设计完善，可补全该Action。
|
*/
class Action_add_blacklist extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $status = $request->status; 
        $user = UserSvc::ins()->getUserById($userid);
        //TODO 这里添加黑名单流程   # 数字转化为常量
        if ($user['usertype'] == 1 || $user['usertype'] == 2) {
            /* 平台或劳务方被加入黑名单时 */
            $new_user = UserSvc::ins()->updateUserStauts($userid, $status);
            $uclink   = UClinkSvc::ins()->getCompanyIdByUserId($userid);

            $new_status = CompanySvc::ins()->updateCompanyStatus($companyid, $status);
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|---------------------------------------------------
| 公司认证 Auth Company Action
|---------------------------------------------------
|
| 这个Action主要负责来认证那些提交认证的公司，具体
| 状态后期可扩展，添加对应字段即可。
| 
*/
class Action_auth_company extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $companyid = $request->companyid;
        $status    = $request->status;

        $company   = CompanySvc::ins()->getCompanyById($companyid);
        if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();   
        }

        $uclink    = UClinkSvc::ins()->getsUserIdByCompanyId($companyid); // 根据公司id获取账号信息

        $new_user  = UserSvc::ins()->updateUserStauts($uclink[0]['userid'], $status); // 根据认证状态更改用户表的状态
        if (!$new_user) {
            echo ResultSet::jfail(500, "Server Error Of UpdateUserStatus");
            return XNext::nothing(); 
        }

        $new_status = CompanySvc::ins()->updateCompanyStatus($companyid, $status); // 根据认证状态更改公司表的状态
        if (!$new_status) {
            echo ResultSet::jfail(500, "Server Error Of UpdateCompanyStatus");
            return XNext::nothing();
        }

        $authority = $company['companytype'] == Company::COMPANYTYPE_LABOR ? "0.0.-1" : "-1.-1.-1"; // 平台和劳务方赋予不同的权限

        if ($status == Company::STATUS_AUTH_SUCCEESS) {
            $enable = UserSvc::ins()->updatePermissonById($uclink[0]['userid'], $authority);
            if (!$enable) {
                echo ResultSet::jfail(500, "Server Error Of UpdatePermisson");
                return XNext::nothing();
            }           
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|---------------------------------------------------
| 显示注册公司的信息 Show Company Action
|---------------------------------------------------
|
| 这个Action主要负责获取提交认证的公司列表。
|
*/
class Action_show_company extends XAction
{
    public function _run($request, $xcontext)
    {
       $companytype = $request->companytype;

       $company     = XDao::query("CompanyQuery")->getAllCompany($companytype); // 获取所有符合类型的公司
       if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
       }

       foreach($company as $k => &$v) {
           if ($v['status'] == Company::STATUS_COMMIT_AUTH) {  
               $seat = ConvertSeat::getSeat($v['site']);
               $v['seat'] = $seat;
           } else { 
               array_splice($company, $k, 1);
           }
       }

       echo ResultSet::jsuccess($company);
       return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 显示不同状态的公司列表 Show Company By Status Action
|-----------------------------------------------------
|
| 这个Action主要负责显示审核通过的和被拒绝的公司列
| 表，也包括现在黑名单的用户列表，未加入黑名单的
| 可根据是否违规执行加入黑名单，加入的也可以恢
| 复，审核未通过的，信息补全也可通过。
|
*/
class Action_show_company_by_status extends XAction
{
    public function _run($request, $xcontext)
    {
        $status    = $request->status;
        $platform  = User::USERTYPE_PLATFORM; // 角色类型：平台
        $labor     = User::USERTYPE_LABOR; // 角色类型：劳务方

        $condition = $status == 6 ? "" : " and (user.usertype=$platform or user.usertype=$labor)";

        $company = XDao::query("CompanyQuery")->getCompanyByStatus($status, $condition);
        if ($company) {
            foreach($company as &$v) {
                $seat = ConvertSeat::getSeat($v['site']);
                $v['seat'] = $seat;
            }
        }

        echo ResultSet::jsuccess($company);
        return XNext::nothing();
    }
}

/*
|---------------------------------------------------
| 显示用户列表 Show UserList Action
|---------------------------------------------------
|
| 这个Action主要负责显示已经注册成功的用户，根据
| 类型来区分是平台。劳务方，员工还是骑士，来
| 分开显示，主要用作可将违规操作的加入黑
| 名单。
|
*/
class Action_show_user_list extends XAction
{
    public function _run($request, $xcontext)
    {
        $usertype = $request->usertype;

        $userinfo = XDao::query(UserQuery)->getUserByUserType($usertype); // 根据请求类型来获取符合的用户
        if (!$userinfo) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        }

        foreach($userinfo as &$v_user) {
            $uclink = UClinkSvc::ins()->getCompanyIdByUserId($v_user['id']);
            if (!$uclink) {
                echo ResultSet::jfail(500, "Server Error Of GetCompanyId");
                return XNext::nothing();
            }

            $company = CompanySvc::ins()->getCompanyById($uclink['companyid']);

            $seat = ConvertSeat::getSeat($company['site']);
            $company['seat'] = $seat;

            $company = $company->toArray();
            $v_user['company'] = $company;
        }

        echo ResultSet::jsuccess($userinfo);
        return XNext::nothing();
    }
}
