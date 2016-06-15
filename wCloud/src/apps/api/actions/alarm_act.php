<?php

/*
|----------------------------------------------------------
| 传感器报警记录 Alarm Action
|----------------------------------------------------------
|
| 这个Action主要负责接收报警的传感器，验证是否是合法的
| 的传感器，同时更改传感器对应电车的状态，MYSQL存闪
| 时记录，SSDB存历史记录，做数据统计分析可用，后
| 期扩展可改写此Action。
|
*/
class Action_alarm extends XPostAction
{
	public function _run($request, $xcontext)
	{
		$imei   = $request->imei;

		$sensor = XDao::query("SensorQuery")->getSensorByImei($imei); // 根据IMEI号获取传感器信息
        if (!$sensor) {
        	echo ResultSet::jfail(404, "Sensor Not Found");
        	return XNext::nothing();
        }

        $ebikeid = LinkSvc::ins()->getEbikeIdBySensorId($sensor['id']); // 根据查询到的传感器ID来获取关联的电车信息
        if ($ebikeid) {
            $excep['exp']  = Ebike::EXCEPTION_ARAM;
            $result = EbikeSvc::ins()->updateExceptionByEbikeId($ebikeid, $excep); // 将电车的闪时异常状态更改记录
            if (!$result) {
                echo Register::jfail(500, "Server Error");
                return XNext::nothing();
            }
        }

        $ecs = EbikeJourneySvc::ins()->addExceptionLog($sensor['id'], time(), $excep=Ebike::EXCEPTION_ARAM); // 将异常状态存ssdb，做历史记录，做数据统计用      

        echo ResultSet::jsuccess();
        return XNext::nothing();
 	}
}

