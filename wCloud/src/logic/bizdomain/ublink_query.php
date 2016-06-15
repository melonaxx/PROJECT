<?php

class UBlinkQuery extends Query
{
	public function countEBike($userid)
	{
		$sql = "select ublink.*,ebike.seqno from ublink left join ebike on ublink.ebikeid = ebike.id and ublink.userid = ? and ublink.is_delete=0";
		return $this->listByCmd($sql, array($userid));
	}

    public function getRelateEbikeId($companyid)
    {
        $sql = "select ublink.ebikeid from ublink left join uclink on ublink.userid=uclink.userid where uclink.companyid=? and ublink.is_delete=0";
        return $this->listByCmd($sql, array($companyid));
    }
}
