<?php
class PurchasebillattachQuery extends Query
{
	//根据票据id查出借的科目
	public function findb($id)
	{
	    $sql = "select * from purchasebillattach where purchasebillid = ? and direction='B'";
	    return $this->listByCmd($sql,array($id));
	}
	//根据票据id查出贷的科目
	public function findi($id)
	{
	    $sql = "select * from purchasebillattach where purchasebillid = ? and direction='I'";
	    return $this->listByCmd($sql,array($id));
	}
}
