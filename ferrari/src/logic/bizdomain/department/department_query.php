<?php
/**
 * @brief 查询所有部门
 *
 * @param
 *
 * @return 所有部门名称的列表
 * */
class departmentQuery extends Query
{
	//添加页面的下拉框内容
	public function partmentinfo()
	{
	    $sql = "select name,id,parent_id from department where isdelete='Y'";
	    return $this->listByCmd($sql);
	}
	//部门列表内容
	public function allpartmentinfo()
	{
	    $sql = "select * from department where isdelete='Y'";
	    return $this->listByCmd($sql);
	}
	public function findfuid($id)
	{
		$sql = "select parent_id from department where id = $id";
		return $this->getByCmd($sql,array($id));
	}
	public function findname($id)
	{
		$sql = "select name from department where id = $id";
		return $this->getByCmd($sql,array($id));
	}
	//根据id查名称和备注
	public function findcomname($id)
	{
		$sql = "select name,comment from department where id = $id";
		return $this->getByCmd($sql,array($id));
	}
	public function findforparent($id)
	{
		$sql = "select count(*) from department where parent_id = $id";
		return $this->getByCmd($sql,array($id));
	}
}