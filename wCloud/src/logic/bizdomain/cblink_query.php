<?php

class CBlinkQuery extends Query
{
    public function getEbike($companyid)
    {
        $sql = "select cblink.*,ebike.seqno from cblink left join ebike on cblink.ebikeid = ebike.id where (cblink.companyid = ? or cblink.useid = ?) and cblink.is_delete=0";
        return $this->listByCmd($sql, array($companyid, $companyid));
    }
}
