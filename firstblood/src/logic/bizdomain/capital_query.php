<?php

class CapitalQuery extends Query
{
	//查出所有分类
    public function all_capital_class()
    {
        $sql = "select * from capital_class";
        return $this->listByCmd($sql);
    }
    
    public function prefix($cid)
    {
        $sql = "select prefix from capital_class where id=?";
        return $this->getByCmd($sql,array($cid));
    }

    public function all_capital($where,$start,$stop)
    {
        $sql = "select *,(select classname from capital_class where capital_class.id = capital.cid)as classname from capital $where limit ?,?";
        return $this->listByCmd($sql,array($start,$stop));
    }

    //一个物品的信息
    public function one_capital($id)
    {
        $sql = "select *,capital.id as capitalid from capital,capital_class where capital.id = ? and capital.cid = capital_class.id";
        return $this->getByCmd($sql,array($id));
    }
    //所有的人
    public function all_people($where)
    {
        $sql = "select id,name from etc_peopleinfo $where";
        return $this->listByCmd($sql);
    }
    //物品总条数
    public function count_capital($where)
    {
        $sql = "select count(*)as num from capital $where";
        return $this->getByCmd($sql);
    }
}