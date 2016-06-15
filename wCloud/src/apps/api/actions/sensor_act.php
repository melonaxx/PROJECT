<?php

/**
 * @brief 检查传感器的状态、配置和版本
 */
class Action_check_sensor extends XPostAction
{
    public function _run($request, $xcontext)
    {
		$imei       = $request->imei;
        $version    = $request->ver;
        $upversion  = $request->upver;
        $sysversion = $request->sysver;
        $imsi       = $request->imsi;

		$sensor = SensorSvc::ins()->getSensorByImei($imei);
		if (!$sensor) {
		 	echo ResultSet::jfail(404, "Sensor Not Found");
		 	return XNext::nothing();
        }

        $sensorid = $sensor['id'];

        // 首先尝试更新IMSI
        if ($imsi) {
            $ret = SensorSvc::ins()->updateMobileNo($sensorid, $imsi);
            if (!$ret) {
                echo ResultSet::jfail(500, "Server Error Of UpdateMobileNo");
                return XNext::nothing();
            }
        }

        // 获取最新的配置
        $conf = SensorConfSvc::ins()->getSensorConfBySensorId($sensorid); 
        if (!$conf) {
            $conf = SensorConfSvc::ins()->getDefaultSensorConf();
        }

        // 判断传感器的配置是否是最新的
        if (!$version || $version != $conf['version']) {
           $new_conf =  $this->convertSensorConf($conf);
           echo ResultSet::jsuccess($new_conf);
        } else {
            $curr_version = $upversion ? $upversion : $sysversion;

            $nextver = $sensor['nextver'];
            // TODO  判断是否需要升级
            if ($curr_version && $nextver && $nextver != $curr_version){
                $upgrade = SenserVersionSvc::ins()->getVersionByCode($nextver); 
                if (!$upgrade) {
                    echo ResultSet::jsuccess();
                    return XNext::nothing();
                }

                $conf         = array();
                $conf['uurl'] = $upgrade['downloadurl'];
                $conf['uv']   = intval($upgrade['code']);
                $conf['upc']  = intval($upgrade['packgecount']);
                $conf['ups']  = intval($upgrade['packagesize']);
                $conf['umd5'] = $upgrade['summd5'];

                echo ResultSet::jsuccess($conf);
            } else {
                echo ResultSet::jsuccess();
            }
        }

        return XNext::nothing();
    }

    /**
     * @brief 更新传感器的配置
     *
     * @param $conf 数据库新的配置
     *
     * @return 要更新的配置信息
     */
    private function convertSensorConf($conf) {
        $new_conf = array();

        if (($conf['updatetype'] & SensorConf::FLAG_COLLECTFREQ) === SensorConf::FLAG_COLLECTFREQ) {
            $new_conf['cf'] = intval($conf['collectfreq']);
        }

        if (($conf['updatetype'] & SensorConf::FLAG_FREQ) === SensorConf::FLAG_FREQ) {
            $new_conf['f'] = intval($conf['freq']);
        } 

        if (($conf['updatetype'] & SensorConf::FLAG_WI) === SensorConf::FLAG_WI) {
            $new_conf['wi'] = intval($conf['wi']);
        } 

        if (($conf['updatetype'] & SensorConf::FLAG_WF) === SensorConf::FLAG_WF) {
            $new_conf['wf'] = intval($conf['wf']);
        } 

        $new_conf['v'] = intval($conf['version']);

        return $new_conf;
    } 
}

class Action_update_conf_by_imei extends XAction
{
    public function _run($request, $xcontext)
    {
        $imei = $request->imei;
        $cf   = intval($request->cf);
        $f    = intval($request->f);
        $wi   = intval($request->wi);
        $wf   = intval($request->wf);

		$sensor = SensorSvc::ins()->getSensorByImei($imei);
		if (!$sensor) {
		 	echo ResultSet::jfail(404, "Sensor Not Found");
		 	return XNext::nothing();
        }

        // 配置的版本号使用当前时间至2016-01-01的秒数
        $version = time() - strtotime("2016-01-01");

        $sensorid = $sensor['id'];

        $sensorconf = SensorConfSvc::ins()->getSensorConfBySensorId($sensorid);
        if (!$sensorconf) {
            $sensorconf = SensorConfSvc::ins()->getDefaultSensorConf();

            $collectferq = $cf ? $cf : $sensorconf['collectfreq'];
            $freq = $f ? $f : $sensorconf['freq'];
            $wi = $wi ? $wi : $sensorconf['wi'];
            $wf = $wf ? $wf : $sensorconf['wf'];

            $result = SensorConfSvc::ins()->addSensorConf($version, $sensorid, 
                $collectferq, $freq,  $wi, $wf);

            if (!$result) {
                echo ResultSet::jfail(500, "Server Error：addSensorConf Fail");
                return XNext::nothing();
            }
        } else {
            $result = SensorConfSvc::ins()->updateSingleSensorConf($version, $sensorid, 
                $cf, $f, $wi, $wf);
            
            if (!$result) {
                echo ResultSet::jfail(500, "Server Error：updateSingleSensorConf Fail");
                return XNext::nothing();
            }
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

class Action_update_nextver extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $imei    = $request->imei;
        $nextver = $request->nextver;

        $new_sensor = SensorSvc::ins()->updateSensorNextVer($imei, $nextver);
        if (!$new_sensor) {
            echo ResultSet::jfail(500, "updateSensorNextVer is fail");
            return XNext::nothing();
        }
        
        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

class Action_show_sensorinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $sensor = XDao::query("SensorQuery")->getAllSensor(); 
        if (!$sensor) {
            echo ResultSet::jfail(404, "Sensor Not Found");
            return XNext::nothing();
        }

        foreach($sensor as &$v_sensor) {
            $ebikeid = LinkSvc::ins()->getEbikeIdBySensorId($v_sensor['id']);
            if ($ebikeid) {
                $ebike = EbikeSvc::ins()->getEbikeById($ebikeid);

                $v_sensor['seqno'] = $ebike['seqno'] ? $ebike['seqno'] : 0;
            }
        }

        echo ResultSet::jsuccess($sensor);
        return XNext::nothing();
    }
}

/**
 * @brief 传感器的imei号入库
 *        返回 成功的:imei
 *             失败的:imei
 */
class Action_store_sensorimei extends XAction
{
    public function _run($request, $xcontext)
    {
        $imei = $request->imei;

        foreach($imei as $v_imei) {
            $sensor = SensorSvc::ins()->addSensor($v_imei);
            if (!$sensor) {
                $data['fail'][]    = $v_imei;
            } else {
                $data['success'][] = $v_imei;
            }
        }

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

/**
 * @brief 电动车的序列号入库
 *        返回 成功的:seqno
 *             失败的:seqno
 */
class Action_store_ebikeseqno extends XAction
{
    public function _run($request, $xcontext)
    {
        $seqno = $request->seqno;
        $mobel = $request->mobel;

        foreach($seqno as $v_seqno) {
            $ebike = EbikeSvc::ins()->addEbike($mobel, $v_seqno);
            if (!$ebike) {
                $data['fail'][]    = $v_seqno;
            } else {
                $data['success'][] = $v_seqno;
            }
        }

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

class Action_store_bslink extends XAction
{
    public function _run($request, $xcontext)
    {
        $seqno  = $request->seqno;

        $ebike  = EbikeSvc::ins()->getEbikeBySeqno($seqno);
        if (!$ebike) {
            echo ResultSet::jfail(404, "Ebike Not Found");
            return XNext::nothing();
        }

        $sensorid = $request->sensorid;
        $new_link = LinkSvc::ins()->addLink($sensorid, $ebike['id']);
        if (!$new_link) {
            echo ResultSet::jfail(500, "server error: exception when storeBSlink");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-------------------------------------------------------
| 修改电车传感器关联 Update Relate Sensor Action
|-------------------------------------------------------
|
| 这个Action主要负责修改电车关联的传感器，主要用在
| 个账号对应的的车辆管理对应的，传感器修改
|
*/
class Action_update_relate_sensor extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $imei    = $request->imei;
        $ebikeid = $request->ebikeid;
    
        $sensor = SensorSvc::ins()->getSensorByImei($imei); // 验证传感器的合法性
        if (!$sensor) {
            echo ResultSet::jfail(404, "Sensor Not Found");
            return XNext::nothing();
        }

        $link = LinkSvc::ins()->updateLink($ebikeid, $sensor['id']); // 修改关联属性
        if (!$link) {
            echo ResultSet::jfail(4031, "Update Link Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}
