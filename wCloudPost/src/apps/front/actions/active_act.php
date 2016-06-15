<?php

/**
 * 传感器激活验证
 */
class Action_a extends XPostAction
{
    public function _run($request, $xcontext)
    {
        // 检查各种参数
        $imei = XParamFilter::checkSensorImei($request->id);
        $_SERVER['WPOST_IMEI'] = $imei;
        if (!$imei) {
            echo ResultUtil::rafail(-2);
            return XNext::nothing();
        }

        $imsi = XParamFilter::checkIMSI($request->i);
        if (!$imsi) {
            echo ResultUtil::rafail(-4);
            return XNext::nothing();
        }

        $sysver = intval($request->v);
        if ($sysver <= 0) {
            echo ResultUtil::rafail(-5);
            return XNext::nothing();
        }

        $client   = GClientAltar::getWCloudClient();
        $result   = $client->activeSensor($imei, $imsi, $sysver);

        if (!$result) {
            echo ResultUtil::rafail(-1);
        } else {
            $errno = $result->errno;
            $conf = $result->data;
            if ($errno === 0 && $conf) {
                echo ResultUtil::rasuccess(array("conf"=>$conf));
            } else {
                if ($errno === 404) {
                    echo ResultUtil::rafail(1);
                } else if ($errno === 406) {
                    echo ResultUtil::rafail(2);
                } else {
                    echo ResultUtil::rafail(-1);
                }
            }
        }

        return XNext::nothing();
    }
}
