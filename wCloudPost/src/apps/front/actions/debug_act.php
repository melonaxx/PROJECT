<?php

pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");
pylon_include_sdk("/home/q/php/bridge_sdk/", "bridge_sdk.php");

class Action_debug extends XAction
{
    public function _run($request, $xcontext)
    {
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $xcontext->client_ip = $client_ip;
        $imei = XParamFilter::htmlSpecial($request->id);
        $xcontext->imei = $imei;

        $debug_logs = RedisClient::ins()->listLogItem($client_ip, $imei);
        if ($debug_logs) {
            $last_log = json_decode($debug_logs[0], true);

            $xcontext->fromtime = $last_log['t'];
            $xcontext->debug_logs = $debug_logs;
        } else {
            $xcontext->fromtime = 0.0;
            $xcontext->debug_logs = array();
        }

        return XNext::useTpl("debug.html");
    }
}

class Action_list_debug_logs extends XAction
{
    public function _run($request, $xcontext)
    {
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $xcontext->client_ip = $client_ip;

        $fromtime = sprintf("%f", $request->fromtime);

        $imei = XParamFilter::htmlSpecial($request->id);
        $xcontext->imei = $imei;

        $debug_logs = RedisClient::ins()->listLogItem($client_ip, $imei, $fromtime);
        foreach($debug_logs as &$log) {
            $log = json_decode($log, true);
            $log['ft'] = date("Y-m-d H:i:s", intval($log['t']));
            $log['ip'] = htmlspecialchars($log['ip']);
            $log['req'] = str_replace("\n", "<br>", htmlspecialchars($log['req']));
            $log['rsp'] = htmlspecialchars($log['rsp']);
        }

        echo ResultSet::jsuccess($debug_logs);
        return XNext::nothing();
    }
}

class Action_close extends XPostAction
{
    public function _run($request, $xcontext)
    {
		$imei     = XParamFilter::checkSensorImei($request->imei);

		if (!$imei) {
            echo ResultSet::jfail(400, "IMEI is invalid");
			return XNext::nothing();
		}
		
		$client   = GClientAltar::getWCloudClient();
		$result   = $client->closeSensor($imei);

        if ($result && $result->errno === 0) {
            echo ResultSet::jsuccess(true);
        } else {
            echo ResultSet::jfail($result->errno, $result->errmsg);
        }

        return XNext::nothing();
    }
}

class Action_update_conf extends XPostAction
{
    public function _run($request, $xcontext)
    {
		$imei     = XParamFilter::checkSensorImei($request->imei);

		if (!$imei) {
            echo ResultSet::jfail(400, "IMEI is invalid");
			return XNext::nothing();
		}

        $cf = intval($request->cf);
        $f = intval($request->f);
        $url = $request->url;
        $wi = intval($request->wi);
        $wf = intval($request->wf);
        $wurl = $request->wurl;

        if (!$cf && !$f && !$url && !$wi && !$wf && !$wurl) {
            echo ResultSet::jfail(400, "params can not be empty");
			return XNext::nothing();
        }
		
		$client   = GClientAltar::getWCloudClient();
		$result   = $client->updateSensorConf($imei, $cf, $f, $url, $wi, $wf, $wurl);

        if ($result && $result->errno === 0) {
            echo ResultSet::jsuccess(true);
        } else {
            echo ResultSet::jfail($result->errno, $result->errmsg);
        }

        return XNext::nothing();
    }
}

class Action_update_nextver extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $imei = XParamFilter::checkSensorImei($request->imei);
		if (!$imei) {
            echo ResultSet::jfail(400, "IMEI is invalid");
			return XNext::nothing();
		}
        
        $nextver = XParamFilter::checkNextVer($request->nextver);       
        if (!$nextver) {
            echo ResultSet::jfail(400, "NextVer is invalid");
            return XNext::nothing();
        }
        
        $client  = GClientAltar::getWCloudClient();
        $result  = $client->updateNextVer($imei, $nextver);

        if ($result && $result->errno === 0) {
            echo  ResultSet::jsuccess();
        } else {
            echo ResultSet::jfail($result->errno, $result->errmsg);
        }

        return XNext::nothing();
    }
}

class Action_upgrade extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("upgrade.html");
    }
}

class Action_uploadbin extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $version = intval($request->version);
        $name = htmlspecialchars($request->name);

        $tmp_file = $_FILES['bin']['tmp_name'];

        if (!is_uploaded_file($tmp_file)) {
            echo ResultSet::jfail(400, "Not Upload File");
            return XNext::nothing();
        }

        $md5 = md5_file($tmp_file);
        $space = substr($md5, -8);
        $prefix = "$version/$space";

        $package_count = 0;
        $handle = fopen($tmp_file, "rb");
        $packagesize = filesize($tmp_file);
        while (!feof($handle)) {
            $package = fread($handle, 1024);
            if ($package) {
                ++$package_count;
                $pak_md5 = md5($package, true);
                $package .= $pak_md5;

                $ret = BridgeSvc::ins()->uploadContent($package, "$prefix/$package_count");
                if ($ret[0] !== 0) {
                    echo ResultSet::jfail(500, "Fail to upload package $package_count");
                    return XNext::nothing();
                }
            }
        }

        $uurl           = "http://7xsicf.com1.z0.glb.clouddn.com/$prefix/";
        $xcontext->upc  = intval($package_count);
        $xcontext->ups  = intval($packagesize);
        $xcontext->umd5 = $md5;
        $xcontext->uurl = $uurl; 

        // 把升级信息写入数据库
        $client   = GClientAltar::getWCloudClient();
        $result   = $client->sensorVersion($version, $name, $uurl, $package_count, $packagesize, $md5);
        if ($result->errno != 0) {    
            echo ResultSet::jfail(500, "Server Error");
            return XNext::nothing();
        }

        return XNext::useTpl("uploadbin.html");
    }
}

class RedisClient
{
    private static $_ins = null;
    private $logger      = null;
    private $redisclient = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }

        $conf = new GRedisConf($_SERVER['REDIS_HOST'], $logger);
        $client = new GRedisClient($conf);

        $this->redisclient = $client;
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }

    public function addLogItem($ip, $uri, $request, $response, $imei)
    {
        $global_key = "debug_log";
        $key = "log_" . $imei;

        $microtime = microtime(true);
        $member = md5($ip . ":" . $microtime . ":" . mt_rand() . ":" . mt_rand());

        // 首先构造请求和响应的string
        $data = json_encode(array("t"=>$microtime, "ip"=>$ip, "uri"=>$uri, "req"=>$request, "rsp"=>$response));
        $this->redisclient->set($key, $member, $data);

        // 存入zset
        // 全局的zset和对应imei的zset各存一份
        $this->redisclient->zAdd($key, $key, $microtime, $member);
        $this->redisclient->zAdd($global_key, $global_key, $microtime, $member);
    }

    public function listLogItem($ip, $imei="", $fromtime=0.0)
    {
        if (!$imei) {
            $key = "debug_log";
        } else {
            $key = "log_" . $imei;
        }

        if ($fromtime <= 0) {
            // 只取最近100条数据
            $members = $this->redisclient->zRevRange($key, $key, 0, 99);
        } else {
            $members = $this->redisclient->zRevRangeByScore($key, $key, 
                "+inf", $fromtime + 0.0001);
        }

        if ($members) {
            return $this->redisclient->mGet($key, $members);
        } else {
            return array();
        }
    }
}
