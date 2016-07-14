<?php

class orderftersonQuery extends Query
{
	//查询记录
	public function findchild($id)
	{
	    $sql = "select orderid from orderfterson where porderid = ? ";
	    return $this->listByCmd($sql,array($id));
	}
	//查询子订单的父订单id记录
	public function findparent($id)
	{
	    $sql = "select porderid from orderfterson where orderid = ? ";
	    return $this->listByCmd($sql,array($id));
	}
}