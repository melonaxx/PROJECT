<?php

class PurchasefinanceQuery extends Query
{
	public function findall($id)
	{
	    $sql = "select * from purchasefinance where purchaseid = ?";
	    return $this->getByCmd($sql,array($id));
	}
}