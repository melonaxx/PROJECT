<?php

/*
|---------------------------------------------------
| 显示周围的骑士状况 Show Around KnightInfo Action
|---------------------------------------------------
|
| 这个Action主要负责显示当前骑士周围同公司的骑士
| 状况，这些信息包括骑士名字，电话，和当前位置
| 的距离，可用来遇到困难求助或者其它功能。
|
*/
class Action_show_around_knightinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取请求账号的公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $userinfo   = UserInfoSvc::ins()->getUserInfoByUserId($userid); // 获取请求账号的详细信息

        $ebikestate = EbikeStateSvc::ins()->getEbikeStateByEbikeId($userinfo['ebikeid']); // 获取请求骑士的车辆行驶数据
        if (!$ebikestate) {
            echo ResultSet::jfail(404, "Coords Not Found");
            return XNext::nothing();
        }

        $around  = XDao::query("EbikeStateQuery")->getAroundEbikeInfo($uclink['companyid']); // 获取附近同公司的骑士
        if (!$around) {
            echo ResultSet::jfail(404, "Knight Of Around Not Found");
            return XNext::nothing();
        } 

        foreach($around as $k => &$v) {
            $distance = CalcDistace::getDistance($ebikestate['latitude'], $ebikestate['longitude'], $v['latitude'], $v['longitude']); // 分别计算附近骑士距离

            $userinfo = UserInfoSvc::ins()->getUserInfoByEbikeId($v['ebikeid']);
            if ($userinfo) {
                $user  = UserSvc::ins()->getUserById($userinfo['userid']);  // 获取附近骑士的信息存入数组显示

                $ebike = EbikeSvc::ins()->getEbikeById($v['ebikeid']);

                $v['seqno']    = $ebike['seqno'];
                $v['name']     = $user['name'];
                $v['userid']   = $user['id'];
                $v['mobileno'] = $userinfo['mobileno'];
                $v['distance'] = $distance;
                $dist[$k] = $v['distance'];
            } else {
                unset($around[$k]); // 没骑士的电车过滤掉
            }
        }

        array_multisort($dist, SORT_ASC, $around); // 按距离正序排列

        echo ResultSet::jsuccess($around);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 显示骑士电车信息 Show Knight EbikeInfo Action
|-----------------------------------------------------------
|
| 这个Action主要负责显示骑士绑定的电车基本信息，以及公司
| 名称一些的基本信息。后期扩展可增加字段信息。
| 
*/
class Action_show_knight_ebikeinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取请求账号的公司id
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $company  = CompanySvc::ins()->getCompanyById($uclink['companyid']); // 获取请求账号的公司信息

        $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($userid);

        $ebikeinfo = "";
        if ($userinfo['ebikeid']) {
            $ebike     = EbikeSvc::ins()->getEbikeById($userinfo['ebikeid']); // 如果骑士已分配了电车，显示电车的信息

            $ebike['labor'] = $company['name'];
            $ebikeinfo      = $ebike;
            $ebikeinfo      = $ebikeinfo->toArray();
        }

        echo ResultSet::jsuccess($ebikeinfo);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 统计骑士的电车状态 Stat Knight EbikeInfo Action
|-----------------------------------------------------------
|
| 这个Action主要负责统计骑士的电车状况，主要统计的数据
| 包括电车电量，电车行驶里程，和平均速度。后期扩展
| 可改写此Action。
|
*/
class Action_stat_knight_ebikeinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid            = $request->userid;

        $userinfo          = UserInfoSvc::ins()->getUserInfoByUserId($userid); // 获取骑士的详细信息

        $data['power']     = array_fill(0, 15, 0);
        $data['total']     = array_fill(0, 15, 0);
        $data['averspeed'] = array_fill(0, 15, 0);
        if ($userinfo['ebikeid']) {
            $sensor = LinkSvc::ins()->getSensorIdByEbikeId($userinfo['ebikeid']);          
            for($i_day=14; $i_day>=0; $i_day--) {
                $pre_day = time()-$i_day * 24 * 60 * 60;           // 一些基本需求的数据统计：电车电量，行驶里程，平均速度

                $power[]     = EbikeJourneySvc::ins()->getMyPowerComsumption($sensor, $pre_day);
                $total[]     = EbikeJourneySvc::ins()->getMySomeDayTotalJourney($sensor, $pre_day);
                $averspeed[] = EbikeJourneySvc::ins()->getMyAverageSpeed($sensor, $pre_day);
            }

            $data[power]       = $power;
            $data['total']     = $total;
            $data['averspeed'] = $averspeed;
        }

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

