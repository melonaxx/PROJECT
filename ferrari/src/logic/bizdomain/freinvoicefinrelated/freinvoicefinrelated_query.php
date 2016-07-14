<?php
class FreinvoicefinrelatedQuery extends Query
{
	public function findb($id)
	{
	    $sql = "select * from freinvoicefinrelated where infoid = ? and direction='B'";
	    return $this->listByCmd($sql,array($id));
	}
	//根据票据id查出贷的科目
	public function findi($id)
	{
	    $sql = "select * from freinvoicefinrelated where infoid = ? and direction='I'";
	    return $this->listByCmd($sql,array($id));
	}
}
