<?php

class SenserVersionSvc
{
    private static $_ins = null;
    private $logger      = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }
    
    public function getMaxCode()
    {
        return XDao::query("SensorVersionQuery")->getMaxCode();
    }

    public function addVersion($code, $name, $uurl, $upc, $ups, $md5)
    {
        $version = new SensorVersion($code);
        $version->name = $name;
        $version->downloadurl = $uurl;
        $version->packgecount = $upc;
        $version->packagesize = $ups;
        $version->summd5 = $md5;

        try {
            $version->insert();
            return $version;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addVersion[$code] : " . $ex->getMessage());
            return false;
        }
    }

    public function updateVersion($code, $name, $uurl, $upc, $md5)
    {
        $version = new SensorVersion($code);
        $version->name = $name;
        $version->downloadurl = $uurl;
        $version->packgecount = $upc;
        $version->summd5 = $md5;

        try {
            $version->update();
            return $version;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateVersion[$code] : " . $ex->getMessage());
            return false;
        }
    }
    
    public function getLatestVersion()
    {
        return XDao::query("SensorVersionQuery")->getLatestVersion();
    }

    public function getVersionByCode($code)
    {
        $sensorversion = new SensorVersion($code);
        $sensorversion->get();

        if ($sensorversion->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            return null;
        }

        return $sensorversion;
    }
}
