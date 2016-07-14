<?php
class bankactactionQuery extends Query
{
	public function allbank($where)
	{
	    $sql = "select createtime,id,bankid,staffid,type,purpose,changepce,endingpce from bankactaction $where";
	    return $this->listByCmd($sql);
	}
	public function allbanks($where,$start,$num)
	{
	    $sql = "select createtime,id,bankid,staffid,type,purpose,changepce,endingpce from bankactaction $where limit $start,$num";
	    return $this->listByCmd($sql);
	}
}