<?php
/**
 * @brief 查询所有渠道
 *
 * @param
 *
 * @return 所有渠道名称的列表
 * */
class companysalesQuery extends Query
{
	//所有渠道
	public function allcompanysalesinfo()
	{
	    $sql = "select id,name,comment from companysales where isdelete='Y'";
	    return $this->listByCmd($sql);
	}
	//所有一条渠道
	public function findone($id)
	{
	    $sql = "select id,name,comment from companysales where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
}