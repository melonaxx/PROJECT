<?php
//模块表
class moduleQuery extends Query
{
	//查询所有模块
	public function allmodule()
	{
	    $sql = "select id,value,name from module where isdelete='Y'";
	    return $this->listByCmd($sql);
	}
}
//权限表
class authorityQuery extends Query
{
	//根据模块id查询权限
	public function auth($v)
	{
	    $sql = "select id,value,name,moduleid from authority where isdelete='Y' and moduleid = ?";
	    return $this->listByCmd($sql,array($v));
	}
}
class roleQuery extends Query
{
	//查询所有角色
	public function roleinfo()
	{
	    $sql = "select * from role where isdelete='Y'";
	    return $this->listByCmd($sql);

	}
	//根据id查角色
	public function findrole($id)
	{
		$sql = "select * from role where id = ?";
		return $this->getBycmd($sql,array($id));
	}
	//根据id查角色名字
	public function findrolename($id)
	{
		$sql = "select name from role where id = ?";
		return $this->getBycmd($sql,array($id));
	}
	//根据id查权限
	public function findauth($id)
	{
		$sql = "select authority from role where id = ?";
		return $this->getBycmd($sql,array($id));
	}
}
