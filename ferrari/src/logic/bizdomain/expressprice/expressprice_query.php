<?php
class ecpresspriceQuery extends Query
{
	//根据快递公司id查规则
	public function cidforprice($id)
	{
	    $sql = "select id,arealist,storeid,firstweight1,firstweight2,firstweight3,firstweight4,firstweight5,firstprice1,firstprice2,firstprice3,firstprice4,firstprice5,weightincrease,priceincrease from expressprice where expressid = ?";
	    return $this->listByCmd($sql,array($id));
	}
	public function findprice($id)
	{
	    $sql = "select id,expressid,arealist,storeid,firstweight1,firstweight2,firstweight3,firstweight4,firstweight5,firstprice1,firstprice2,firstprice3,firstprice4,firstprice5,weightincrease,priceincrease from expressprice where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
}