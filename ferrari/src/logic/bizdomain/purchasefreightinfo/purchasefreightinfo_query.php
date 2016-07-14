<?php
class PurchasefreightinfoQuery extends Query
{
	public function findall()
	{
	    $sql = "select * from purchasefreightinfo";
	    return $this->listByCmd($sql);
	}
	public function findallpage($start,$stop)
	{
	    $sql = "select * from purchasefreightinfo limit $start,$stop ";
	    return $this->listByCmd($sql);
	}
	//通过编号查是否存在
	public function findnum($number)
	{
	    $sql = "select count(*) as count from purchasefreightinfo where id = ?";
	    return $this->getByCmd($sql,array($number));
	}
}
