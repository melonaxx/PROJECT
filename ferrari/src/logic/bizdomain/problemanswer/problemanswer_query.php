<?php
class ProblemanswerQuery extends Query
{
	public function questioninfo($platid)
	{
	    $sql = "select id,platformid,problem,answer from problemanswer where isdelete = 'N' and platformid = ?" ;
	    return $this->listByCmd($sql,array($platid));
	}
	//查询该平台下问题的数量
	public function countquest($platid)
	{
	    $sql = "select count(*) from problemanswer where platformid = ? and isdelete = 'N'" ;
	    return $this->getByCmd($sql,array($platid));
	}
}