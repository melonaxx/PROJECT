<?php
//店铺表
class systemshopQuery extends Query
{
	//查询所有店铺
	public function findall()
	{
	    $sql = "select id,name from systemshop where isdelete='N'";
	    return $this->listByCmd($sql);
	}

	public function findone($id)
	{
		$sql = "select name from systemshop where id = ?";
		return $this->getByCmd($sql,array($id));
	}
}