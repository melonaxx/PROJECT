<?php
/**
 * @brief 客户信息列表
 *
 * @param
 *
 * @return 客户信息列表
 * */
class CustomerInfoQuery extends Query
{
	public function listcustomerdata($page,$pagesize,$type,$name,$total)
	{
		$where = ' WHERE isdelete="N" ';
		if (!empty($name) && empty($type)) {
			$where .=' AND name like "%{$name}%"';
		}
		if (!empty($type) && empty($name)) {
			$where .=" AND type='{$type}'";
		}
		if (!empty($type) && !empty($name)) {
			$where .= " AND name like '%{$name}%' AND type='{$type}'";
		}
		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
		}

		if ($total == 'list') {
			$sql = "SELECT *
			FROM customerinfo".$where."
			ORDER BY createtime DESC
			LIMIT $sqlpage,$pagesize";

		} else if ($total == 'total') {
			$sql = "SELECT count(*) As total
			FROM customerinfo".$where;
		}

		return $this->listByCmd($sql,array());
	}

	/*通过客户ID获取单条客户信息*/
	public function getCusInfo($customerid)
	{
		$sql = "SELECT * FROM customerinfo WHERE id=?";
		return $this->listByCmd($sql,array('id'=>$customerid));
	}
	/*通过客户名称模糊查询获取客户id和名称*/
	public function getforname($name)
	{
		$sql = "SELECT id,name FROM customerinfo WHERE instr(name,'$name')";
		return $this->listByCmd($sql);
	}
	/*通过客户ID获取单条客户信息*/
	public function find($customerid)
	{
		$sql = "SELECT * FROM customerinfo WHERE id=?";
		return $this->getByCmd($sql,array('id'=>$customerid));
	}
}