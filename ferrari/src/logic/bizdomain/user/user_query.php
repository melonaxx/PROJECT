<?php

class UserQuery extends Query
{
    public function userlist($where,$start,$num)
    {
        $sql = "select * from user $where limit $start,$num";
        return $this->listByCmd($sql);
    }
    public function userlists($where)
    {
        $sql = "select * from user $where";
        return $this->listByCmd($sql);
    }
    public function userone($id)
    {
        $sql = "select * from user where id = ?";
        return $this->getByCmd($sql,array($id));
    }
    //根据申请人查id
    public function nameforid($name)
    {
        $sql = "select id from user where name like '%$name%'";
        return $this->getByCmd($sql);
    }

    /*列表所有的用用户信息*/
    public function listuserinfo()
    {
        $sql = "SELECT * FROM user";

        return $this->listByCmd($sql);
    }
}
