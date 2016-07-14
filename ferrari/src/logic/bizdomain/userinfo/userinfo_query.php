<?php

class userinfoQuery extends Query
{
    public function userinfolist($uid)
    {
        $sql = "select * from userinfo where userid = ?";
        return $this->getByCmd($sql, array($uid));
    }
    public function findid($uid)
    {
        $sql = "select id from userinfo where userid = ?";
        return $this->getByCmd($sql, array($uid));
    }
    public function findforpartment($id)
    {
        $sql = "select userid from userinfo where departmentid = ? and status = 0";
        return $this->listByCmd($sql, array($id));
    }
}

