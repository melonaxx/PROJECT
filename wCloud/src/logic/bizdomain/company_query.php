<?php

class CompanyQuery extends Query
{
    public function getCompanyByName($name, $companyid)
    {
        $sql = "select cllink.*, company.name from cllink left join company on cllink.laborid=company.id where company.name like '%$name%' and cllink.platformid = ? and company.status = 0";
        return $this->listByCmd($sql, array($companyid));
    }

    public function getCompany($name, $companytype)
    {
        $sql = "select * from company where name like '%$name%' and status=0 and companytype=?";
        return $this->listByCmd($sql, array($companytype));
    }

    public function getAllCompany($companytype)
    {
        $sql = "select company.*, uclink.userid, user.status from company left join uclink on company.id=uclink.companyid left join user on user.id = uclink.userid where user.usertype=? and company.companytype= ? order by createtime desc";
        return $this->listByCmd($sql, array($companytype, $companytype));
    }

    public function getCompanyByStatus($status, $condition)
    {
        $sql = "select company.*, uclink.userid from company left join uclink on company.id=uclink.companyid left join user on user.id=uclink.userid where company.status= ?" . $condition;
        return $this->listByCmd($sql, array($status));
    }
}
