<?php

class LinkSvc 
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
    
    /**
     * @brief 创建电车--传感器关联
     *
     * @param [int] $sensorid [传感器id]
     * @param [int] $ebikeid  [电车id]
     *
     * @return
     */
    public function addLink($sensorid, $ebikeid)
    {
       $link           = new Link();
       $link->sensorid = $sensorid;
       $link->ebikeid  = $ebikeid;

        try {
            $link->insert();
            return $link;
        } catch(Exception $ex) {
            $this->logger->error("exception occur when addLink[$ebikeid,$sensorid]：".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  根据电车ID获取关联的传感器ID
     *
     * @param  [int] $ebikeid [电车id]
     *
     * @return 
     */
    public function getSensorIdByEbikeId($ebikeid)
    {
        $link = new Link();
        $link->get(array("ebikeid"=>$ebikeid));

        if ($link->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getEbikeIdBySensorId, ebikeid: $ebikeid");
            return 0;
        }

        return $link->sensorid;
    }

    /**
     * @brief  根据传感器
     *
     * @param  [type] $sensorid [description]
     * 
     * @return
     */
    public function getEbikeIdBySensorId($sensorid)
    {
        $link = new Link();
        $link->get(array("sensorid"=>$sensorid));

        if ($link->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getEbikeIdBySensorId, sensorid: $sensorid");
            return 0;
        }

        return $link->ebikeid;
    }

    public function updateLink($ebikeid, $sensorid)
    {
        $link = new Link();
        $link->sensorid = $sensorid;
        $result = $link->update(array("ebikeid" => $ebikeid));

        if ($link->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when updateLink, ebikeid: $ebikeid");
            return 0;
        }

        return $result;
    }
}

