<?php
class PurchasefreinvoiceinfoQuery extends Query
{
	public function findall()
	{
	    $sql = "select * from purchasefreinvoiceinfo";
	    return $this->listByCmd($sql);
	}
	public function findallpage($start,$stop)
	{
	    $sql = "select * from purchasefreinvoiceinfo limit $start,$stop ";
	    return $this->listByCmd($sql);
	}
}
