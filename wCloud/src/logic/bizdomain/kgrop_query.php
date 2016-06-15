<?php

class KGropQuery extends Query
{
    public function getKGropEbikeInfo($laborid)
    {
        $sql = "select userinfo.ebikeid, kgrop.name from userinfo left join kgrop on kgrop.id=userinfo.gropid where userinfo.ebikeid !=0 and kgrop.laborid=?";
        return $this->listByCmd($sql, array($laborid));
    }

    public function getKGropByName($name, $laborid)
    {
        $sql = "select * from kgrop where name like '%$name%' and laborid = ?";
        return $this->listByCmd($sql, array($laborid));
    }
}
