<?php

class UClinkQuery extends Query
{
    public function getEmployeeByCompanyId($companyid, $usertype, $name, $limit)
    {
        $sql = "select user.*, userinfo.mobileno, userinfo.email from user left join uclink on uclink.userid = user.id left join userinfo on user.id = userinfo.userid where uclink.companyid = ? and user.usertype = ? and uclink.is_delete=0" . $name . $limit;
        return $this->listByCmd($sql, array($companyid, $usertype));
    }

    public function getTotalRow($companyid, $usertype, $name)
    {
        $sql = "select count(*) as sum from user left join uclink on uclink.userid = user.id left join userinfo on user.id = userinfo.userid where uclink.companyid = ? and user.usertype = ? and uclink.is_delete=0" . $name;
        return $this->getByCmd($sql, array($companyid, $usertype));
    }

    public function getRelateKnight($companyid, $kgid, $name, $limit)
    {
        $sql = "select  user.name, user.status, user.id, userinfo.gropid, userinfo.ebikeid, userinfo.mobileno from uclink left join user on uclink.userid=user.id left join userinfo on user.id=userinfo.userid  where user.usertype=4 and uclink.is_delete=0 and uclink.companyid=?" . $kgid . $name . $limit;
        return $this->listByCmd($sql, array($companyid));
    }

    public function getTotalRowOfKnight($companyid, $kgid, $name)
    {
        $sql = "select  count(*) as sum from uclink left join user on uclink.userid=user.id left join userinfo on user.id=userinfo.userid  where user.usertype=4 and uclink.is_delete=0 and uclink.companyid=?" . $kgid . $name;
        return $this->getByCmd($sql, array($companyid));
    }
}
