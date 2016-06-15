<?php

/*
|---------------------------------------------------
| 添加劳务方   Add Labor Action
|---------------------------------------------------
|
| 这个Action主要负责为平台添加劳务方。你可以在
| 添加劳务方的同时为劳务方设置管理的员工，
| 也可以先不设置，后期在分配管理员工。
|
*/
class Action_add_labor extends XAction
{
    public function _run($request, $xcontext)
    {
        $platformid = $request->platformid;
        $userid     = $request->userid;
        $laborid    = $request->laborid;

        $platform   = UClinkSvc::ins()->getCompanyIdByUserId($platformid); // 获取平台信息
        if (!$platform) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $cllink  = CLlinkSvc::ins()->addLabor($platform['companyid'], $laborid, $userid); // 添加劳务方

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 调拨电车给员工  Allot Ebike To User Action
|--------------------------------------------------
|
| 这个Action主要负责平台给员工分配一些车辆供
| 员工管理，员工若有相应的权限，可行驶对
| 车辆的分配，取消分配等，当然平台依
| 然享有对这些车的操作的一些权限。
|
*/
class Action_allot_ebike_to_user extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $ebikeid = $request->ebikeid;

        foreach($ebikeid as $v_eb) {
            $ebike = UBlinkSvc::ins()->getUBlinkByEbikeId($v_eb, $userid); // 判断车之前是否跟员工绑定
            if (!$ebike) {
                $result = UBlinkSvc::ins()->createUBlink($userid, $v_eb);
            } else {
                $result = UBlinkSvc::ins()->updateIsDelete($userid, $v_eb);
            }

            if (!$result) {
                echo ResultSet::jfail(500, "Server Error");
                return XNext::nothing();
            }
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|------------------------------------------------------ 
| 取消电车分配 Cancle Distribute Ebike Action
|------------------------------------------------------
|
| 这个Action主要负责取消分配给员工的车辆或者是取消
| 分配给劳务方的车辆，取消分配给员工和取消分配
| 给劳务方的车辆是两个执行过程，根据用户类型
| 来执行对应的取消行为。扩展可改成Action。
|
*/
class Action_cancle_distribute_ebike extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $seqno  = $request->seqno;
        $act    = $request->act;

        $user   = UserSvc::ins()->getUserById($userid); // 获取用户基本信息，判断类型用
        if (!$user) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        }

        $ebike = EbikeSvc::ins()->getEbikeBySeqno($seqno); // 根据电车序列号获取电车信息
        if (!$ebike) {
            echo ResultSet::jfail(404, "Ebike Not Found");
            return XNext::nothing();
        } 

        if ($user['usertype'] == User::USERTYPE_EMPLOYEE && $act) {
            $result = UBlinkSvc::ins()->destoryRelate($userid, $ebike['id']); // 取消分配给员工的车辆
            if (!$result) {
                echo ResultSet::jfail(4031, "Cancle DistributeEbike Of Emp Fail");
                return XNext::nothing();
            }
        } else {       
            $company = UClinkSvc::ins()->getCompanyIdByUserId($userid);

            $result  = CBlinkSvc::ins()->cancleDistribute($ebike['id'], $company['companyid']); // 取消分配给劳务方的车辆
            if (!$result) {
                echo ResultSet::jfail(4031, "Cancle DistributeEbike Of Labor Fail");
                return XNext::nothing();
            }
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|------------------------------------------------------ 
| 取消劳务方分配 Cancle Distribute Labor Action
|------------------------------------------------------
|
| 这个Action主要负责取消分配给员工的劳务方，在平台
| 下的员工模块分配车辆里用，扩展可重写Action。
|
*/
class Action_cancle_distribute_labor extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $laborid = $request->laborid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $employeeid = 0;
        $cllink     = CLlinkSvc::ins()->updateEmployee($uclink['companyid'], $laborid, $employeeid);  // 取消员工和劳务方的关联
        if (!$cllink) {
            echo ResultSet::jfail(4031, "Cancle DistributeLabor Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 分配劳务方 Distribute Labor To Employee Action
|--------------------------------------------------
|
| 这个Action主要负责平台为员工分配劳务方，一个
| 员工可管理多个劳务方，但一个劳务方对应
| 一个平台只能被一个员工管理。
|
*/
class Action_distribute_labor_to_employee extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $empid  = $request->empid;
        $labor  = $request->labor;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        foreach($labor as $k => $v_lab) {
            $cllink = CLlinkSvc::ins()->updateEmployee($uclink['companyid'], $v_lab, $empid);
            if (!$cllink) {
                echo ResultSet::jfail(4031, "Distribute Labor To Emp Fail");
                return XNext::nothing();
            }
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 为劳务方分配电车 Distribute Ebike To Labor Action
|--------------------------------------------------
|
| 这个Action主要负责给劳务方分配电车，电车--平台
| --劳务方三者建立关系，即平台和劳务方都可查
| 看电车的状况，这里的电车序列号是数组，
| 代表可同时为劳务方分配多辆车。
|
*/
class Action_distribute_ebike_to_labor extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $seqno   = $request->seqno;
        $userid  = $request->userid;
        $laborid = $request->laborid;

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 根据账号获取公司的信息
        if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $fail = array();
        foreach ($seqno as $v_seq) {
            $ebike = EbikeSvc::ins()->getEbikeBySeqno($v_seq);  
            if ($ebike) {
                $new_cblink = CBlinkSvc::ins()->addCBlink($company['companyid'], $ebike['id'], $laborid); // 将车跟劳务方绑定关系
            } else {
                $fail[] = $v_seq['seqno'];
            } 
        }

        echo ResultSet::jsuccess($fail);
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 获取公司的员工 Get Employee Action
|--------------------------------------------------
|
| 这个Action主要负责获取账号对应的公司下的员工，
| 然后将其显示在劳务模块的员工下拉里做搜
| 索用，可以匹配查看对应员工的劳务方。
|
*/
class Action_get_employee extends XAction
{
     public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $data    = $request->data;
        $name    = $data['name'];
        $page    = $data['page'];
        $num     = $data['num'];

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $usertype = User::USERTYPE_EMPLOYEE;
        $name = $name ? " and user.name like '%$name%'" : "";
        $page = $page ? $page : null;
        $num  = $num ? $num : null;
        
        $total    = XDao::query("UClinkQuery")->getTotalRow($company['companyid'], $usertype, $name); // 获取总数据条数
        $limit    = HandlePage::getPage($page, $num, $total); // 设置分页

        $employee = XDao::query("UClinkQuery")->getEmployeeByCompanyId($company['companyid'], $usertype, $name, $limit['limit']);
        if (!$employee) {
            echo ResultSet::jfail(404, "Employee Not Found");
            return XNext::nothing();
        }

        foreach($employee as &$v_emp) {
            $cllink = CLlinkSvc::ins()->getLaborByUserId($v_emp['id']);
            $relate = array();
            if ($cllink) {
                foreach($cllink as $v_cl) {
                    $relate[] = EbikeGather::getEbikeGather($v_cl['platformid'], $v_cl['laborid']); // 获取符合的车辆集
                }
            }

            $ublink = UBlinkSvc::ins()->showAllEbikeByUserId($v_emp['id']); // 获取平台分给员工的车辆

            $sender = $ublink ? Entity::convertToArray($ublink) : array();

            for($i = 0; $i <= count($relate)-1; $i++) {
                $sender = array_merge($sender, $relate[$i]); // 统计总的车辆情况，并要处理消重
                $key = "ebikeid";         
                $tmp_arr = array();
                foreach ($sender as $k => $v) {
                    if (in_array($v[$key], $tmp_arr)) {
                        unset($sender[$k]);
                    } else {
                        $tmp_arr[] = $v[$key];
                    }
                }
            }

            $v_emp['cnt']    = count($sender);
            $v_emp['labnum'] = $cllink ? count($cllink) : 0; // 统计劳务方数量
        }

        $employee['pageAll'] = $limit['pageAll'];
        $employee['total']   = $total['sum'];

        echo ResultSet::jsuccess($employee);
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 获取公司的员工列表 Get Employee List Action
|--------------------------------------------------
|
| 这个Action主要负责显示平台下的员工列表，在下
| 拉中显示。
|
*/
class Action_get_employee_list extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号对应的公司信息
        if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $uclink = UClinkSvc::ins()->getsUserIdByCompanyId($company['companyid']); // 获取平台下的员工
        foreach ($uclink as $k => $v) {
            if ($v['userid'] == $userid) {
                unset($uclink[$k]);
            } else {
                $user     = UserSvc::ins()->getUserById($v['userid']);
                $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($v['userid']);

                $list[$k]['id']       = $v['userid'];
                $list[$k]['name']     = $user['name'];
                $list[$k]['mobileno'] = $userinfo['mobileno'];
            }
        }

        $list = $list ? array_values($list) : array();

        echo ResultSet::jsuccess($list);
        return XNext::nothing();
    }
}

/*
|------------------------------------------------------ 
| 删除关联的劳务方 Remove Labor Action
|------------------------------------------------------
|
| 这个Action主要负责删除关联的劳务方，同时要注意释放
| 之前分配给劳务方车辆，和劳务方设置平台可查看的
| 车辆，防止冲突，后期有更好地方法可重写。
|
*/
class Action_remove_labor extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $laborid = $request->laborid;

        $platform = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取请求账号对应的公司id
        if (!$platform) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $cllink  = CLlinkSvc::ins()->removeLabor($platform['companyid'], $laborid); // 移除关联的劳务方
        if (!$cllink) {
            echo ResultSet::jfail(4031, "Remove Labor Fail");
            return XNext::nothing();
        }

        $cblink = CBlinkSvc::ins()->removeEbikeRelate($platform['companyid'], $laborid); // 解除平台分给劳务方的电车
        if (!$cblink) {
            echo ResultSet::jfail(4031, "Remove Ebike Relate Of Plat Fail");
            return XNext::nothing();
        }

        $remove = CBlinkSvc::ins()->removeEbikeRelate($laborid, $platform['companyid']); // 解除劳务方设置平台可查看的电车
        if (!$remove) {
            echo ResultSet::jfail(4031, "Remove Ebike Relate Of Labor Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}
/*
|-------------------------------------------------- 
| 移除员工或骑士  Remove Employee Action
|--------------------------------------------------
|
| 这个Action主要负责对于平台或劳务方中的人员变
| 动，一些不在的员工/骑士，或一些不参与操作
| 的员工/骑士，将其移除。释放一些权限。
|
*/
class Action_remove_employee extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid     = $request->userid;
        $employeeid = $request->employeeid;
        $usertype   = $request->usertype;
        $ebikeid    = $request->ebikeid; 

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号对应的公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $new_uclink = UClinkSvc::ins()->removeRelate($employeeid); // 移除员工，解除公司员工的关系
        if (!$new_uclink) {
            echo ResultSet::jfail(500, "Remove Relate Fail");
            return XNext::nothing();
        }

        if (!$usertype) {
            $cllink = CLlinkSvc::ins()->updateCLlink($uclink['companyid'], $employeeid); // 将移除的员工管理的劳务方释放出来，重新分配管理的员工

            $ublink = UBlinkSvc::ins()->destoryRelate($employeeid); // 移除平台分给劳务方的车: 分配车就删除，没分配也不报错
        } else {
            // 这里标志移除的是骑士
            $userinfo = UserInfoSvc::ins()->unwrapEbike($employeeid);
            if ($ebikeid) {
                $ebike = EbikeSvc::ins()->updateDistribute($ebikeid, Ebike::ALLOT_UNBIND);    
                if (!$ebike) {
                    echo ResultSet::jfail(403, "Update Distribute Fail");
                    return XNext::nothing();
                }
            }
        }

        /* 删除的员工/骑士要将其状态回到初始状态 */
        $user     = UserSvc::ins()->getUserById($employeeid);

        $usertype = 0;
        $status   = $user['status'] == 0 ? 2 : $user['status'];

        $result = UserSvc::ins()->updateUserStauts($employeeid, $status, $usertype);
        if (!$result) {
            echo ResultSet::jfail(500, "Server Error: update status fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 显示可分配的电车 Show Distributable Ebike Action
|--------------------------------------------------
|
| 这个Action主要负责显示企业下还未分配的自有车
| 辆，默认是企业的，如果是查看员工的，需传
| 一个role来区分，因为员工要过滤自己分
| 配过的车，后期如有更好的方案，可
| 重写此Action。
|
*/
class Action_show_distributable_ebike extends XAction
{
     public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $role   = $request->role;

        $user   = UserSvc::ins()->getUserById($userid); // 获取账号基本信息
        if (!$user) {
            echo ResultSet::jfail(404, "user not found");
            return XNext::nothing();
        }   

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号对应的公司信息

        if ($role) {
            $filter  = XDao::query("UBlinkQuery")->getRelateEbikeId($company['companyid']); // role参数来标志是查看员工的，此时要顾虑已经关联过的电车
            $filter  = array_column($filter, "ebikeid");
            $ebikeid = implode($filter, ",");
            $limit   = $ebikeid ? " and ebikeid not in($ebikeid)" : "";
        }

        $ebike = XDao::query("EbikeQuery")->getEbike($company['companyid'], $limit); // 获取未分配的电车
        if (!$ebike) {
            echo ResultSet::jfail(404, "Ebike not found");
            return XNext::nothing();
        }

        foreach($ebike as &$v_eb) { // 标识一些车，为了区分给劳务方
            $ublink = UBlinkSvc::ins()->getUBlinkByEbikeId($v_eb['id']);
            $v_eb['relate'] = $ublink ? 1 : 0;
            if ($ublink) {
                $user = UserSvc::ins()->getUserById($ublink['userid']);
            }

            $v_eb['userid'] = $ublink ? $ublink['userid'] : 0;
            $v_eb['name']   = $user['name'] ? $user['name']  : "";
        }

        echo ResultSet::jsuccess($ebike);
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 显示劳务方信息 Show Labor Info Action
|--------------------------------------------------
|
| 这个Action主要负责显示平台关联的劳务方信息，
| 一般在首页侧边栏用列表，也可根据需求设置
| 排序，这里目前只设置了按车辆数来进行
| 排序显示，后期有别的需求，可扩展
| 此Action,设置不同的排序。
|
*/
class Action_show_labor_info extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $data   = $request->data;
        $page   = $data['page'];
        $num    = $data['num'];
        $employeeid = $data['employeeid'];
        $name       = $data['name'];

        $page = $page ? $page : null;
        $num  = $num ? $num : null;
        $employeeid = $employeeid ? " and cllink.userid=$employeeid" : "";
        $name = $name ? " and company.name like '%$name%'" : "";

        $total = XDao::query("CLlinkQuery")->getTotalRow($userid, $employeeid, $name); // 获取总条数
        $limit = HandlePage::getPage($page, $num, $total); // 设置分页
        
        $labor = XDao::query("CLlinkQuery")->getRelateLabor($userid, $limit['limit'], $employeeid, $name);  // 获取平台账号关联的所有劳务方

        foreach($labor as $key => &$v_lab) {
            $cnt = EbikeGather::getEbikeGather($v_lab['platformid'], $v_lab['laborid']); // 获取符合的车辆集

            if ($v_lab['userid']) {
                $user = UserSvc::ins()->getUserById($v_lab['userid']); // 劳务方有管理员工，展示员工信息

                $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($v_lab['userid']);

                $v_lab['username'] = $user['name'] ? $user['name'] : "";
                $v_lab['mobileno'] = $userinfo ? $userinfo['mobileno'] : 0;
            }

            $company = CompanySvc::ins()->getCompanyById($v_lab['laborid']); // 获取劳务方公司信息
            
            $v_lab['ebikenum'] = count($cnt);
            $v_lab['linkman']  = $company['linkman'];
            $v_lab['linkmono'] = $company['mobileno'];
        }

        $labor['pageAll'] = $limit['pageAll'];
        $labor['total']   = $total['sum'];

        echo ResultSet::jsuccess($labor);
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 显示劳务方列表 Show Labor List Action
|--------------------------------------------------
|
| 这个Action主要负责显示平台先的所有劳务方，在
| 首页侧边栏，包括排序，后期扩展可改写这个
| 方法。
|
*/
class Action_show_labor_list extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $sort   = $request->sort;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号关联的公司id
        if (!$uclink) {
            echo ResultSet::jfail(404, "company not found");
            return XNext::nothing();
        }

        $cllink = CLlinkSvc::ins()->getsLabIdByCompanyId($uclink['companyid']); // 获取平台关联的劳务方
        if (!$cllink) {
            echo ResultSet::jfail(404, "labor not found");
            return XNext::nothing();
        }

        foreach($cllink as $k => &$v_cl) {
            $company = CompanySvc::ins()->getCompanyById($v_cl['laborid']); // 获取劳务方信息，显示名称需要

            $cnt = EbikeGather::getEbikeGather($v_cl['platformid'], $v_cl['laborid']); // 获取符合的车辆集

            $v_cl['name']     = $company['name'];
            $v_cl['ebikenum'] = count($cnt);
            $dist[$k]         = $v_cl['ebikenum'];
        }

        if ($sort) {
            array_multisort($dist, SORT_ASC, $cllink);
        }

        echo ResultSet::jsuccess($cllink);
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 根据名字搜索劳务方 Search Labor By Name Action
|--------------------------------------------------
|
| 这个Action主要负责根据名字来搜索匹配的劳务
| 方, 用在平台下的劳务方搜索模块。
|
*/
class Action_search_labor_by_name extends XAction
{
     public function _run($request, $xcontext)
    {
        $name    = $request->name;
        $userid  = $request->userid;

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$company) {
            echo ResultSet::jfail(404, "Company not found");
            return XNext::nothing();
        }

        $labor  = XDao::query("CompanyQuery")->getCompany($name, $companytype=Company::COMPANYTYPE_LABOR); 
        if (!$labor) {
            echo ResultSet::jfail(404, "Labor not found");
            return XNext::nothing();
        }

        foreach($labor as $key => &$value) {
            $cllink = CLlinkSvc::ins()->getPlatform($company['companyid'], $value['id']);

            $value['belong']  = $cllink ? 1 : 0;
            $value['userid']  = $cllink['userid'] ? $cllink['userid'] : 0;
            $value['laborid'] = $cllink ? $cllink['laborid'] : 0;
        }

        echo ResultSet::jsuccess($labor);
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------- 
| 显示可分配的劳务方 Show Distributable Labor Action
|--------------------------------------------------
|
| 这个Action主要负责显示那些未绑定员工的劳务方，
| 用作给员工分配劳务方，当然也可以在添加劳务
| 的时候就给劳务方指定员工，此时这里就不
| 显示已绑定过的劳务方。
|
*/
class Action_show_distributable_labor extends XAction
{
     public function _run($request, $xcontext)
    {
        $userid  = $request->userid;

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取请求账号的基本信息
        if (!$company) {
            echo ResultSet::jfail(404, "Company not found");
            return XNext::nothing();
        }

        $cllink = CLlinkSvc::ins()->getsLabIdByCompanyId($company['companyid']); // 获取平台关联的劳务号
        if (!$cllink) {
            echo ResultSet::jfail(404, "Labor not found");
            return XNext::nothing();
        }
        
        foreach($cllink as $key => $value) {  
            if (!$value['userid']) {
                $companys = CompanySvc::ins()->getCompanyById($value['laborid']); // 获取未绑定员工的劳务方

                $data[] = $companys;
            }
        }

        $data = Entity::convertToArray($data);

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

/*
|------------------------------------------------------ 
| 搜索手机号添加员工 Search Employee By MobileNo Action
|------------------------------------------------------
|
| 这个Action主要负责搜索手机号来添加员工，这里输入
| 的手机号可以是注册过的，也可以是未注册过的，
| 注册过的手机号添加后可登陆，未注册的添加，
| 相当于预注册，员工还需走注册流程。
|
*/
class Action_search_employee_by_mobileno extends XAction
{
    public function _run($request, $xcontext)
    {
        $mobileno = $request->mobileno;

        $userinfo = UserInfoSvc::ins()->getUserInfoByMobileNo($mobileno); // 根据手机号查找用户信息，有补全基本信息，没有返回错误提示
        if (!$userinfo) {
            echo ResultSet::jfail(404, "user not found");
            return XNext::nothing();
        }

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userinfo['userid']); // 判断用户是否已经是员工/骑士/该账号是平台或者劳务方
        if ($uclink) {
            echo ResultSet::jfail(403, "mobileno is a  plat or has been added");
            return XNext::nothing();
        }

        $user = UserSvc::ins()->getUserById($userinfo['userid']); // 获取用户的基本信息

        $userinfo['name'] = $user['name'] ? $user['name'] : "";

        $userinfo = $userinfo->toArray();

        echo ResultSet::jsuccess($userinfo);
        return XNext::nothing();
    }   
}

/*
|------------------------------------------------------ 
| 根据名字搜索员工信息 Search Employee By Name Action
|------------------------------------------------------
|
| 这个Action主要负责根据员工的名字来查询员工的
| 信息，为后续一些权限内的操作提供的展示。
| 后期扩展添加可在Action增加字段，来
| 显示想要的信息。
|
*/
class Action_search_employee_by_name extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid   = $request->userid;
        $data     = $request->data;
        $usertype = $request->usertype;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$uclink) {
            echo ResultSet::jfail(404, "company not auth");
            return XNext::nothing(); 
        }

        if ($data['name']) {
            $name = $data['name'];
            $condition[] = " user.name like '%$name%' ";
        }

        if ($data['kgid']) {
            $kgid = $data['kgid'];
            $condition[] = " userinfo.gropid = $kgid";
        }

        $condit = "";
        if (count($condition) > 0) {
            $condit = " and" . implode("and", $condition);
        } 

        $userinfo = XDao::query("UserInfoQuery")->getUserInfo($uclink['companyid'], $usertype, $condit);
        if (!$userinfo) {
            echo ResultSet::jfail(404, "user not found");
            return XNext::nothing();
        }

        foreach($userinfo as &$v) {
            $cblink = CLlinkSvc::ins()->getLaborByUserId($v['userid']);

            if ($v['gropid']) {
                $kgrop = KGropSvc::ins()->showKGropByGropId($v['gropid']);
            }

            if ($v['ebikeid']) {
                $ebike = EbikeSvc::ins()->getEbikeById($v['ebikeid']);
            }

            $v['seqno']    = $ebike ? $ebike['seqno'] : 0;
            $v['kgname']   = $kgrop ? $kgrop['name'] : "";
            $v['labornum'] = $cblink ? count($cblink) : 0;
        }

        echo ResultSet::jsuccess($userinfo);
        return XNext::nothing();
    }
}

/*
|------------------------------------------------------ 
| 显示员工下的车辆信息 Show EbikeInfo From Emp Action
|------------------------------------------------------
|
| 这个Action主要负责显示员工管理的车辆信息，这里包
| 括平台单给员工分的车辆和给员工分配的劳务方
| 对应的车辆，当然这里要注意消重，员工给
| 劳务方分的车辆，和平台给员工的车辆
| 只能统计一次，不然会出现误差。
| 后期如有更好的方法可重写Action.
| 
*/
class Action_show_ebikeinfo_from_emp extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $ublink = UBlinkSvc::ins()->showAllEbikeByUserId($userid); // 获取公司分给员工的车辆
        $sender = $ublink ? Entity::convertToArray($ublink) : array();

        $cllink = CLlinkSvc::ins()->getLaborByUserId($userid); // 获取员工绑定的劳务方
        if ($cllink) {
            foreach($cllink as $v_cl) {
                $relate[] = EbikeGather::getEbikeGather($v_cl['platformid'], $v_cl['laborid']); // 获取符合的车辆集
            }
        }

        for($i = 0; $i <= count($relate)-1; $i++) {
            $sender = array_merge($relate[$i], $sender); // 统计总的车辆情况，并要处理消重
            $key = "ebikeid";         
            $tmp_arr = array();
            foreach ($sender as $k => $v) {
                if (in_array($v[$key], $tmp_arr)) {
                    unset($sender[$k]);
                } else {
                    $tmp_arr[] = $v[$key];
                }
            }
        }

        foreach($sender as &$v_eb) {
            $ebike = EbikeSvc::ins()->getEbikeById($v_eb['ebikeid']);
            $v_eb['seqno'] = $ebike ? $ebike['seqno'] : 0;
        }

        echo ResultSet::jsuccess($sender);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 获取平台关联劳务方车辆  Show EbikeInfo From Labor Action
|-----------------------------------------------------------
|
| 这个Action主要负责显示平台下关联的劳务方车辆信息，包括
| 平台分配给劳务方的电车和，劳务方自己绑定设置平台
| 可查看的电车，后期扩展可改成此Action。
|
*/
class Action_show_ebikeinfo_from_labor extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $laborid = $request->laborid;

        $uclink  = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号对应的平台信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "company not found");
            return XNext::nothing();
        }

        $gather = EbikeGather::getEbikeGather($uclink['companyid'], $laborid); // 获取符合的车辆集

        foreach($gather as &$v_eb) {
            $ebike = EbikeSvc::ins()->getEbikeById($v_eb['ebikeid']);
            $v_eb['seqno'] = $ebike ? $ebike['seqno'] : 0;
        }

        $gather['account'] = $uclink['companyid'];

        echo ResultSet::jsuccess($gather);
        return XNext::nothing();
    }
}

/*
|------------------------------------------------------ 
| 给劳务方替换管理员工 Update Employee For Labor Action
|------------------------------------------------------
|
| 这个Action主要负责给劳务方替换管理员工，后期扩展
| 可改写此Action。
|
*/
class Action_update_employee_for_labor extends XAction
{
     public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $laborid = $request->laborid;
        $platid  = $request->platid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($platid);
        if (!$uclink) {
            echo ResultSet::jfail(404, "company not auth");
            return XNext::nothing();
        }

        $cllink = CLlinkSvc::ins()->updateEmployee($uclink['companyid'], $laborid, $userid);
        if (!$cllink) {
            echo ResultSet::jfail(500, "Server Error");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}



