<?php

/*
|---------------------------------------------------
| 统计员工的车辆状况 Stat Employee EbikeInfo Action
|---------------------------------------------------
|
| 这个Action主要负责统计员工下的车辆状态，这些统计
| 数据包括。车辆总数，正在运行，正在休息，异常
| 车辆数据，以及一些历史数据来方便分析。后期
| 可扩展此Action。
|
*/
class Action_stat_employee_ebikeinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $ublink = UBlinkSvc::ins()->showAllEbikeByUserId($userid); // 获取平台分给员工的车辆
        $ublink = $ublink ? Entity::convertToArray($ublink) : array();

        $cllink = CLlinkSvc::ins()->getLaborByUserId($userid); // 获取员工绑定的劳务方

        $cnt = array();
        if ($cllink) {
            foreach($cllink as $k => $v) {
                $gather[] = EbikeGather::getEbikeGather($v['platformid'], $v['laborid']); // 获取符合的车辆集
            }

            for($i = 0; $i <= count($gather)-1; $i++) {
                $cnt = array_merge($cnt, $gather[$i]);
            }
        }

        if ($ublink || $cnt) {
            $cnt = array_merge($ublink, $cnt);
        }

        $stat     = new StatFactory();
        $result   = $stat->getEbikeStat($cnt); // 获取账号下的车辆各种状态的统计数据

        echo ResultSet::jsuccess($result);
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------------
| 显示员工下的劳务方信息 Show Labor Info From Emp Action
|-------------------------------------------------------
|
| 这个Action主要负责统计员工下的劳务方信息，主要在员
| 工账号的劳务方下用。
*/
class Action_show_labor_info_from_emp extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $data   = $request->data;
        $name   = $data['name'];

        $page = $data['page']? $data['page'] : null;   // 处理分页数据
        $num  = $data['num'] ? $data['num'] : null;
        $name = $name ? " and company.name like '%$name%'" : "";

        $total = XDao::query("CLlinkQuery")->getTotalRowOfEmp($userid, $name); // 获取总条数
        $limit = HandlePage::getPage($page, $num, $total); // 设置分页
        
        $labor = XDao::query("CLlinkQuery")->getRelateEmp($userid, $limit['limit'], $name);  // 获取平台账号关联的所有劳务方

        foreach($labor as $key => &$v_lab) {
            $cnt = EbikeGather::getEbikeGather($v_lab['platformid'], $v_lab['laborid']); // 获取符合的车辆集

            if ($v_lab['userid']) {
                $user = UserSvc::ins()->getUserById($v_lab['userid']); // 劳务方有管理员工，展示员工信息

                $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($v_lab['userid']);

                $v_lab['username'] = $user['name'] ? $user['name'] : "";
                $v_lab['mobileno'] = $userinfo ? $userinfo['mobileno'] : 0;
            }

            $company  = CompanySvc::ins()->getCompanyById($v_lab['laborid']);

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
