<?php

/*
|-----------------------------------------------------------
| 显示电车状态信息 Show Status Action
|-----------------------------------------------------------
|
| 这个Action主要负责显示电车状态数据，和一些电车的基本情
| 况，包括骑士的一些基本信息，电车的位置等，后期有其
| 它需求显示，可重写此Action。
|
*/
class Action_status_show extends XPostAction
{
 	public function _run($request, $xcontext)
	{
		$seqno   = $request->seqno;

		$ebike   = EbikeSvc::ins()->getEbikeBySeqno($seqno); // 根据电车序列号获取电车的信息
		if (!$ebike) {
			echo ResultSet::jfail(404, "Ebike Not Found");
			return XNext::nothing();
		}

        $ebikestate = XDao::query("EbikeStateQuery")->getEbikeStateByEbikeId($ebike['id']); // 查询电车的定位
		if (!$ebikestate) {
			echo ResultSet::jfail(404, "Coords Not Found");
			return XNext::nothing();
		}

        $userinfo = UserInfoSvc::ins()->getUserInfoByEbikeId($ebike['id']); // 获取骑士的一些基本信息
        if ($userinfo) {
            $user = UserSvc::ins()->getUserById($userinfo['userid']);

            $ebikestate['name'] = $user ? $user['name'] : "";
        }

        $ebikestate['seqno']    = $seqno;
        $ebikestate['mobileno'] = $userinfo ? $userinfo['mobileno'] : 0;

		echo ResultSet::jsuccess($ebikestate);
		return XNext::nothing();
	}
}

/*
|-----------------------------------------------------------
| 电车路径轨迹显示 Path Show Action
|-----------------------------------------------------------
|
| 这个Action主要负责根据需求显示一些间隔时间的定位数据
| ,然后根据这些定位数据可在地图上现在电车的轨迹，根
| 剧这些轨迹可作数据分析。
|
*/
class Action_path_show extends XPostAction
{
	public function _run($request, $xcontext)
	{
		$seqno   = $request->seqno;

		$ebike   = EbikeSvc::ins()->getEbikeBySeqno($seqno); // 根据电车序列号获取电车的信息
		if (!$ebike) {
			echo ResultSet::jfail(404, "Ebike Not Found");
			return XNext::nothing();
		}

        $sensorid = LinkSvc::ins()->getSensorIdByEbikeId($ebike['id']); // 获取电车绑定的传感器
        if (!$sensorid) {
            echo ResultSet::jfail(404, "Sensor Not Found");
            return XNext::nothing();
        }

        $time = time() - 3600;   // 设置30分钟的路径，这里可设置动态的，后期可作改动

        $jpourney = EbikeJourneySvc::ins()->getMySomeTimeJournet($sensorid, time(), $time, time());

		$userinfo = UserInfoSvc::ins()->getUserInfoByEbikeId($ebike['id']);
		if (!$userinfo) {
			echo ResultSet::jfail(404, "Ebike Not Distribute");
			return XNext::nothing();
		}

		$user = UserSvc::ins()->getUserById($userinfo['userid']);

        if ($jpourney) {
            foreach ($jpourney as &$v) {
                $v['name']     = $user['name'];
                $v['mobileno'] = $user['mobileno'];
                $v['seqno']    = $seqno;
            }
        }

		echo ResultSet::jsuccess($jpourney);
		return XNext::nothing();
	}
}	
