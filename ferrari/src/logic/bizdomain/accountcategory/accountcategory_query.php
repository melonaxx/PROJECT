<?php
//财务类别表
class accountcategoryQuery extends Query
{
	//所有财务类型
	public function allaccountcategory()
	{
	    $sql = "select id,goryname,remark,acctypeid from accountcategory where status='Y'";
	    return $this->listByCmd($sql);
	}
	//根据id查一条内容
	public function findaccountcategory($id)
	{
	    $sql = "select id,goryname,remark,acctypeid from accountcategory where id = ? ";
	    return $this->getByCmd($sql,array($id));
	}
	//根据类型id查下面的所有类别
	public function findforaccid($id)
	{
	    $sql = "select id,goryname from accountcategory where acctypeid = ? and status='Y'";
	    return $this->listByCmd($sql,array($id));
	}
}