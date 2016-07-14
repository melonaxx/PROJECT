<?php
//售后留言表
class aftersalemsgQuery extends Query
{
	//根据订单id查询售后留言
	public function findallfororderid($orderid)
	{
	    $sql = "select * from aftersalemsg where orderid = ? and isdelete = 'N'";
	    return $this->listByCmd($sql,array($orderid));
	}
	
}