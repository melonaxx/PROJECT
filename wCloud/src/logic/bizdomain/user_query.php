<?php

class UserQuery extends Query
{
    public function getUserByUserType($usertype)
    {
        $sql = "select user.id, user.name, userinfo.mobileno as phone from user left join userinfo on user.id = userinfo.userid where usertype = ? and user.status=0";
        return $this->listByCmd($sql, array($usertype));
    }
}
