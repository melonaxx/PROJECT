<?php

/**
 * @brief 获取所有传感器配置
 */
class Action_get_sensorconf extends XAction
{
    public function _run($request, $xcontext)
    {
        $sensorconf = XDao::query("SensorConfQuery")->getAllSensorConf();
        if (!$sensorconf) {
            echo ResultSet::jfail(404, "Sensor Not Found");
            return XNext::nothing();
        }

        foreach ($sensorconf as &$v) {
            if ($v['sensorid'] != 0){
                $ebikeid = LinkSvc::ins()->getEbikeIdBySensorId($v['sensorid']);
                if (!$ebikeid) {
                    echo ResultSet::jfail(404, "Ebike Not Found");
                    return XNext::nothing();
                }

                $ebike = EbikeSvc::ins()->getEbikeById($ebikeid);
            }

            $v['seqno']   = $ebike ? $ebike['seqno'] : 0;
            $v['ebikeid'] = $ebikeid ? $ebikeid : 0;
        }

        echo ResultSet::jsuccess($sensorconf);
        return XNext::nothing();
    }   
}

/**
 * @brief 获取单个传感器配置
 */
class Action_get_singlesensorconf extends XAction
{
    public function _run($request, $xcontext)
    {
        $sensorid = $request->sensorid;

        $sensorconf = SensorConfSvc::ins()->getSensorConfBySensorId($sensorid);
        if (!$sensorconf) {
            echo ResultSet::jfail(404, "Sensro Not Found");
            return XNext::nothing();
        }

        $sensorid = $sensorconf->sensorid;
        if ($sensorid) {
            $ebikeid  = LinkSvc::ins()->getEbikeIdBySensorId($sensorid);
            if (!$ebikeid) {
                echo ResultSet::jfail(404, "Ebike Not Found");
                return XNext::nothing();
            }
        }

        $sensorconf['ebikeid'] = $ebikeid ? $ebikeid : 0;

        $sensorconf = $sensorconf->toArray();

        echo ResultSet::jsuccess($sensorconf);
        return XNext::nothing();
    }        
}

/**
 * @brief 修改传感器配置
 */
class Action_update_sensorconf extends XAction
{
    public function _run($request, $xcontext)
    {
        $data = $request->data;
        $version     = $data['ver'];
        $collectfreq = $data['cf'];
        $freq        = $data['f'];
        $wi          = $data['wi'];
        $wf          = $data['wf'];
        $seqno       = $data['seqno'];

        if (!$seqno) {
            // 这里执行修改默认的配置
            $new_conf = SensorConfSvc::ins()->updateSensorConf($version, $collectfreq, $freq, $wi, $wf);
            if (!$new_conf) {
                echo ResultSet::jfail(4031, "UpdateSensorConf Fail");
                return XNext::nothing();
            }

            $new_conf = SensorConfSvc::ins()->getDefaultSensorConf();
        } else {
            $ebike = EbikeSvc::ins()->getEbikeBySeqno($seqno);
            if (!$ebike) {
                echo ResultSet::jfail(404, "Ebike Not Found");
                return XNext::nothing();
            }

            // 这里执行修改单个传感器配置
            $sensorid = LinkSvc::ins()->getSensorIdByEbikeId($ebike['id']);
            if (!$sensorid) {
                echo ResultSet::jfail(404, "Sensor Not Found");
                return XNext::nothing();
            }

            $sensorconf = SensorConfSvc::ins()->getSensorConfBySensorId($sensorid);
            if (!$sensorconf) {
                $result = SensorConfSvc::ins()->addSensorConf($version, $sensorid, $collectfreq, $freq, $wi, $wf);
            } else {
                $result = SensorConfSvc::ins()->updateSingleSensorConf($version, $sensorid, $collectferq, $freq, $wi, $wf);
            }

            if (!$result) {
                echo ResultSet::jfail(500, "Server Error");
                return XNext::nothing();
            }

            $new_conf = SensorConfSvc::ins()->getSensorConfBySensorId($sensorid);
        }

        $conflog = $this->addConfLogs($new_conf); 
        if (!$conflog) {
            echo ResultSet::jfail(500, "Server Error Of AddConfLog");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }

    private function addConfLogs($data)
    {
        $v          = $data['version'];
        $cf         = $data['collectfreq'];
        $f          = $data['freq'];
        $sensorid   = $data['sensorid'];
        $wi         = $data['wi'];
        $wf         = $data['wf'];
        $updatetype = $data['updatetype'];

        $conflog = ConfLogSvc::ins()->addConfLog($v, $cf, $f, $sensorid, $wi, $wf, $updatetype);

        return $conflog;
    }
}

/**
 * @brief 获取更改配置日志 
 */
class Action_get_conflog extends XAction
{
    public function _run($request, $xcontext)
    {
        $conflog = XDao::query("ConfLogQuery")->getConfLog();
        if ($conflog) {
            foreach ($conflog as &$v) {
                if ($v['sensorid'] != 0){
                    $ebikeid = LinkSvc::ins()->getEbikeIdBySensorId($v['sensorid']);
                    if (!$ebikeid) {
                        echo ResultSet::jfail(404, "ebike not found");
                        return XNext::nothing();
                    }

                    $ebike = EbikeSvc::ins()->getEbikeById($ebikeid);
                }

                $v[seqno]     = $ebike ? $ebike['seqno'] : 0;
                $v['ebikeid'] = $ebikeid ? $ebikeid : 0;
            }
        }

        echo ResultSet::jsuccess($conflog);
        return XNext::nothing();
    }   
}

/**
 * @brief 删除单个配置信息
 */
class Action_remove_sensorconf extends XAction
{
    public function _run($request, $xcontext)
    {
        $sensorid = $request->sensorid;

        if ($sensorid) {
            $result = SensorConfSvc::ins()->removeSensorConf($sensorid);
            if (!$result) {
                echo ResultSet::jfail(500, "Server Error Of Remove Conf");
                return XNext::nothing();
            }
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/**
 * @brief删除单个历史记录信息
 */
class Action_remove_conflog extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->id;

        $result   = ConfLogSvc::ins()->removeConfLog($id);
        if (!$result) {
            echo ResultSet::jfail(500, "Server Error Of Remove ConfLog");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

