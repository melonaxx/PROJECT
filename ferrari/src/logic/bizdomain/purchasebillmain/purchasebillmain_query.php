<?php
class PurchasebillmainQuery extends Query
{
	//票据记录页面
	public function findall()
	{
	    $sql = "select * from purchasebillmain where bankid = 0";
	    return $this->listByCmd($sql);
	}
	//票据记录页面分页
	public function findallpage($start,$stop)
	{
	    $sql = "select * from purchasebillmain where bankid = 0 limit $start,$stop";
	    return $this->listByCmd($sql);
	}
	//税点记录页面
	public function findal()
	{
	    $sql = "select * from purchasebillmain where bankid <> 0";
	    return $this->listByCmd($sql);
	}
	//税点记录页面分页
	public function findalpage($start,$stop)
	{
	    $sql = "select * from purchasebillmain where bankid <> 0 limit $start,$stop";
	    return $this->listByCmd($sql);
	}
	//根据id查采购单编码
	public function findpurchaseid($id)
	{
	    $sql = "select purchaseid from purchasebillmain where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
}
