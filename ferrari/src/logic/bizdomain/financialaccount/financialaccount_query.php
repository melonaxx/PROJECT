<?php
class financialaccountQuery extends Query
{
	//所有财务科目
	public function allfinan()
	{
	    $sql = "select id,name,code,acctypeid,parent,remark from financialaccount where status = 'Y'";
	    return $this->listByCmd($sql);
	}
	public function findfinan($id)
	{
	    $sql = "select id,name,code,accgoryid,acctypeid,parent,remark,balance from financialaccount where id = ? and status = 'Y'";
	    return $this->getByCmd($sql,array($id));
	}
	//根据上级id查找
	public function findforparent($id)
	{
	    $sql = "select count(*) from financialaccount where parent = ? and status = 'Y'";
	    return $this->getByCmd($sql,array($id));
	}
	//根据类型id查找
	public function findfortype($id)
	{
	    $sql = "select id,name,code,acctypeid,parent,remark from financialaccount where status = 'Y' and acctypeid = ?";
	    return $this->listByCmd($sql,array($id));
	}
	//根据id查找科目名称
	public function findname($id)
	{
	    $sql = "select name from financialaccount where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
}