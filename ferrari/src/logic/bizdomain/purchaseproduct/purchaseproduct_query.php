<?php

class PurchaseproductQuery extends Query
{
	public function getsid($id)
	{
	    $sql = "select * from purchaseproduct where purchaseid = ?";
	    return $this->listByCmd($sql,array($id));
	}
}