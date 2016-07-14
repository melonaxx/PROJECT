<?php
class subjectbalanceQuery extends Query
{
	public function findlast($id)
	{
	    $sql = "select * from subjectbalance where faccountid = ? order by id desc limit 0,1";
	    return $this->getByCmd($sql,array($id));
	}
}