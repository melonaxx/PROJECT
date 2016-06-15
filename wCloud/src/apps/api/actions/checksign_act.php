<?php

/*
|----------------------------------------------------------
| 验证签名 Check Sign Action
|----------------------------------------------------------
|
| 这个Action主要负责验证签名是否合法，主要根据signkey来
| 判断其合法性，后期可扩展其它方式的验签。
|
*/
class Action_check_sign extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $imei   = $request->imei;
        $sign   = $request->sign;

        $sensor = SensorSvc::ins()->getSensorByImei($imei); // 根据IMEI号获取传感器的信息
        if (!$sensor) {
            echo ResultSet::jfail(404, "Sensor Not Found");
            return XNext::nothing();
        }

        if (intval($sensor['flag']) === Sensor::FLAG_INIT) { // 判断传感器是否激活，未激活返回让其激活
            echo ResultSet::jfail(4031, "Sensor Not Activate");
            return XNext::nothing();
        }

        $data = $request->data;
        if (!$data) {
            $data = file_get_contents("php://input");
        }

        $result = CheckSignSvc::ins()->checkSign($sensor['id'], $sign, $data); // 验证签名
        if (!$result) {
            echo ResultSet::jfail(4032, "Sign Is Invalid");
            return XNext::nothing();
        }

        // 此时签名验证已通过，确认密钥已下发到传感器，因此更新传感器的状态
        // 即使更新失败，也不报错
        if (intval($sensor['flag']) === Sensor::FLAG_ACTIVATING) {
            $info = SensorSvc::ins()->enableSensor($sensor['id']);
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

