<?php

class moneyrecodeQuery extends Query
{
	//查询所有记录
	public function findall()
	{
	    $sql = "select * from moneyrecode";
	    return $this->listByCmd($sql);
	}

}