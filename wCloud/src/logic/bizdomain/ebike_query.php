<?php

class EbikeQuery extends Query
{
    public function getEbike($companyid, $limit=null)
    {
        $sql = "select ebike.*, cblink.companyid, cblink.useid from ebike, cblink where companyid = ? and ebike.id = cblink.ebikeid and cblink.distribute=-1 and cblink.is_delete=0" . $limit;
        return $this->listByCmd($sql, array($companyid));
    }

    public function combineSearch($where, $limit)
    {
        $sql = "select ebike.*, cblink.useid as laborid, cblink.distribute, cblink.companyid from ebike left join cblink on cblink.ebikeid=ebike.id where cblink.is_delete=0" . $where . $limit;
        return $this->listByCmd($sql);
    }

    public function conditionSelect($where, $limit)
    {
        $sql = "select distinct ebike.*,cblink.useid as laborid,cblink.distribute,cblink.companyid from ebike left join cblink on cblink.ebikeid=ebike.id left join cllink on (cllink.laborid=cblink.useid and cllink.platformid=cblink.companyid) or (cllink.laborid=cblink.companyid and cllink.platformid=cblink.useid) where cblink.is_delete=0" . $where . $limit;
        return $this->listByCmd($sql);
    }

    public function countEbike($where)
    {
        $sql = "select count(distinct ebike.id) as sum from ebike left join cblink on cblink.ebikeid=ebike.id left join company on cblink.companyid=company.id where cblink.is_delete=0" . $where . " limit 1";
        return $this->getByCmd($sql);
    }

    public function countRows($where)
    {
        $sql = "select count(distinct ebike.id) as sum from ebike left join cblink on cblink.ebikeid=ebike.id left join cllink on cllink.laborid=cblink.useid and cllink.platformid=cblink.companyid where cblink.is_delete=0" . $where. " limit 1";
        return $this->getByCmd($sql);
    }

    public function getLostEbike($time)
    {
        $sql = "select id from ebike where updatetime < ?";
        return $this->listByCmd($sql, array($time));
    }
}
