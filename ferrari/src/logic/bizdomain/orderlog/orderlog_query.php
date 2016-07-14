<?php
class orderlogQuery extends Query
{
	//按订单查询操作记录
	public function findfororderid($orderid)
	{
	    $sql = "select orderlog.*,user.name as username from orderlog,user where orderlog.orderid = ? and orderlog.staffid = user.id and orderlog.isdelete='N'";
	    return $this->listByCmd($sql,array($orderid));
	}
}