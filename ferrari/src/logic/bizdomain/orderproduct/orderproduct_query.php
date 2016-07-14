<?php

class OrderproductQuery extends Query
{
	public function findfororderid($orderid)
	{
	    $sql = "select * from orderproduct where orderid = ? and isdelete = 'N'";
	    return $this->listByCmd($sql,array($orderid));
	}
	//查找所有子订单的商品
	public function findchildproduct($orderid)
	{
	    $sql = "select productid,sum(total) as totals from orderproduct where orderid in ($orderid) and isdelete = 'N' group by productid";
	    return $this->listByCmd($sql);
	}
	//查找合并前订单的商品信息
	public function findhe($orderid)
	{
	    $sql = "select * from orderproduct where orderid in ($orderid) and isdelete = 'N'";
	    return $this->listByCmd($sql);
	}
	//查询优惠和应付的和
	public function findys($orderid)
	{
	    $sql = "select sum(discount) as youhuis,sum(payment) as pays from orderproduct where orderid = ? and isdelete = 'N'";
	    return $this->getByCmd($sql,array($orderid));
	}
}