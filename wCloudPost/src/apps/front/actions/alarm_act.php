<?php

/**
* 报警状态记录验证
*/
class Action_w extends XPostAction
{
	public function _run($request, $xcontext)
	{
		$imei   = XParamFilter::checkSensorImei($request->id);
        $_SERVER['WPOST_IMEI'] = $imei;

		if (!$imei) {
			echo ResultUtil::rcfail(-3);
			return XNext::nothing();
		}

        if ($request->s === 101 || $request->s === 102) {
            echo ResultUtil::rcfail(1);
			return XNext::nothing();
		}

		$data    = $request->s.":".$request->t;
        $sign    = $request->sign;
		$client  = GClientAltar::getWCloudClient();
		$result  = $client->checkSign($imei, $sign, $data);
        $errno   = $result->errno; 

		if ($errno === 4031) {
            echo ResultUtil::rcfail(-1);
		} else if ($errno === 4032) {
            echo ResultUtil::rcfail(-2);
		} else if($errno === 0) {
            $client = GClientAltar::getWCloudClient();
            $result = $client->alarmCheck($imei);
            $errno  = $result->errno;
            if ($errno === 0) {
                echo ResultUtil::rcsuccess();
            } else {
            	echo ResultUtil::rcfail(-3);
            }
		}

        return XNext::nothing();
	}
}
