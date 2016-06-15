<?php

class CLlinkQuery extends Query
{
    public function getLabor($userid, $name)
    {
       $sql = " select cllink.*, company.name from cllink left join company on cllink.laborid=company.id where company.name like '%$name%' and cllink.userid=?";
       return $this->listByCmd($sql, array($userid));
    }

    public function getRelateLabor($userid, $limit, $employeeid, $name)
    {
        $sql = "select cllink.*, company.name from cllink left join uclink on uclink.companyid=cllink.platformid left join company on company.id=cllink.laborid where uclink.userid=? and cllink.is_delete=0" . $employeeid . $name . $limit;
        return $this->listByCmd($sql, array($userid));
    }

    public function getTotalRow($userid, $employeeid, $name)
    {
        $sql = "select count(*) as sum  from cllink left join uclink on uclink.companyid=cllink.platformid left join company on company.id=cllink.laborid where uclink.userid=? and cllink.is_delete=0" . $employeeid . $name;
        return $this->getByCmd($sql, array($userid));
    }

    public function getRelatePlatform($userid, $limit, $name)
    {
        $sql = "select cllink.*, company.name from cllink left join uclink on uclink.companyid=cllink.laborid left join company on company.id=cllink.platformid where uclink.userid=? and cllink.is_delete=0" . $name . $limit;
        return $this->listByCmd($sql, array($userid)); 
    }

    public function getTotalRowOfPlat($userid, $name)
    {
        $sql = "select count(*) as sum  from cllink left join uclink on uclink.companyid=cllink.laborid left join company on company.id=cllink.platformid where uclink.userid=? and cllink.is_delete=0" . $name;
        return $this->getByCmd($sql, array($userid));
    }

    public function getRelateEmp($userid, $limit, $name)
    {
        $sql = "select cllink.*, company.name from cllink left join company on company.id=cllink.laborid where cllink.userid=? and cllink.is_delete=0" . $name . $limit;
        return $this->listByCmd($sql, array($userid));
    }

    public function getTotalRowOfEmp($userid, $name)
    {
        $sql = "select count(*) as sum from cllink left join company on company.id=cllink.laborid where cllink.userid=? and cllink.is_delete=0" . $name;
        return $this->getByCmd($sql, array($userid));
    }
}
