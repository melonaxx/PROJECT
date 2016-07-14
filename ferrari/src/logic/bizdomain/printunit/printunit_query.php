<?php
/**
 * 印刷单单位
 */
class printunitQuery extends Query
{
	public function findall()
	{
	    $sql = "SELECT * FROM printunit WHERE isdelete='N'";
	    return $this->listByCmd($sql);
	}

	/*通过印刷单位的ID查询印刷单位的名称*/
	public function getunitbyid($unitid)
	{
		$sql = "SELECT id,name FROM printunit WHERE id=? AND isdelete='N'";

		return $this->getByCmd($sql,array('id'=>$unitid));
	}
}