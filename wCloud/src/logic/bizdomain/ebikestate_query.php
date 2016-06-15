<?php

class EbikeStateQuery extends Query
{
    public function getEbikeStateByEbikeId($ebikeid)
    {
        $sql = "select ebike.seqno,ebikestate.* from ebike,ebikestate where ebikeid = ? and ebike.id= ? order by updatetime desc limit 1";
        return $this->getByCmd($sql, array($ebikeid, $ebikeid));
    }

    public function getAroundEbikeInfo($companyid)
    {
        $sql = "select ebikestate.* from ebikestate left join cblink on ebikestate.ebikeid=cblink.ebikeid where cblink.companyid=?"; 
        return $this->listByCmd($sql, array($companyid));
    }

    public function getRestEbike()
    {
        $sql = "select sensorid from ebikestate where geohash=lastgeohash";
        return $this->listByCmd($sql);
    }
}

