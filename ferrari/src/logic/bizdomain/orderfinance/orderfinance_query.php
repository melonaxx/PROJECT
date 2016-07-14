<?php

class orderfinanceQuery extends Query
{
	//查询记录
	public function findone($id)
	{
	    $sql = "select * from orderfinance where orderid = ? ";
	    return $this->getByCmd($sql,array($id));
	}

}