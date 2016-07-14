<?php
/**
 * @brief 查询所有公司
 *
 * @param
 *
 * @return 所有公司名称的列表
 * */
class companyQuery extends Query
{
	//所有公司
	public function allcompanyinfo()
	{
	    $sql = "select id,name,comment from company where isdelete='Y'";
	    return $this->listByCmd($sql);
	}
	//根据公司id查公司名称
	public function findname($id)
	{
	    $sql = "select name from company where id = ?";
	    return $this->getByCmd($sql,array($id));
	}
}