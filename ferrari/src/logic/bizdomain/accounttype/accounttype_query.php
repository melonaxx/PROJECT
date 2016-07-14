<?php
//财务类型表
class accounttypeQuery extends Query
{
	//所有财务类型
	public function allaccounttype()
	{
	    $sql = "select id,typename,remark from accounttype where status='Y'";
	    return $this->listByCmd($sql);
	}
	//根据id查一条内容
	public function findaccounttype($id)
	{
	    $sql = "select id,typename,remark from accounttype where id = ? ";
	    return $this->getByCmd($sql,array($id));
	}
}