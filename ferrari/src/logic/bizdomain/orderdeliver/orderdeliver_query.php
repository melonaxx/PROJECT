<?php

class orderdeliverquery extends Query
{
	public function findfororderid($orderid)
	{
	    $sql = "select * from orderdeliver where orderid = ?";
	    return $this->getByCmd($sql,array($orderid));
	}

}