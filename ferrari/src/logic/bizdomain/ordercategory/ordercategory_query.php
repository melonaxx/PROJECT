<?php

class ordercategoryQuery extends Query
{
	//查询所有记录
	public function findall()
	{
	    $sql = "select * from ordercategory where isdelete='N'";
	    return $this->listByCmd($sql);
	}

	//查询一条记录
	public function findone($id)
	{
	    $sql = "select * from ordercategory where id=?";
	    return $this->getByCmd($sql,array($id));
	}
}