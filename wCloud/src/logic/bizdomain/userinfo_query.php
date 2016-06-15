<?php

class UserInfoQuery extends Query
{
    public function getUserInfo($companyid, $usertype, $condition)
    {
        $sql = "select user.name,userinfo.* from userinfo left join user on user.id = userinfo.userid left join uclink on uclink.userid = userinfo.userid where uclink.companyid=? and user.usertype=?" . $condition;
        return $this->listByCmd($sql, array($companyid, $usertype));
    }
}
