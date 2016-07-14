<?php

class orderinfoQuery extends Query
{
	//查询未审核主订单
	public function findnmain($where)
	{
	    $sql = "select * from orderinfo $where";
	    return $this->listByCmd($sql);
	}
	//查询未审核主订单分页
	public function findnmainpage($where,$start,$stop)
	{
	    $sql = "select * from orderinfo $where limit $start,$stop";
	    return $this->listByCmd($sql);
	}
	//验证订单是为审核通过的主订单
	public function findnum($id)
	{
	    $sql = "select count(*) as count from orderinfo where id = ? and orstatus = 'P' and isdelete = 'N' and type = 'Y'";
	    return $this->getByCmd($sql,array($id));
	}
	//按id 查订单信息
	public function findforid($id)
	{
	    $sql = "select * from orderinfo where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
	public function findforids($id)
	{
	    $sql = "select * from orderinfo where id = ? and isdelete = 'N'";
	    return $this->getByCmd($sql,array($id));
	}
	public function finddel()
	{
	    $sql = "select * from orderinfo where isdelete = 'Y'";
	    return $this->listByCmd($sql);
	}
}