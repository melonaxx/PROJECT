<?php
/**
 * @brief 查询商品中单位名称列表
 *
 * @param
 *
 * @return 所有单位名称的列表
 * */
class ListProunitQuery extends Query
{
	public function listProunitinfo()
	{
	    $sql = "select id,name from prounit where isdelete='N'";
	    return $this->listByCmd($sql);
	}

	//根据id查单位名字
	public function getdwname($id)
	{
	    $sql = "select name from prounit where isdelete='N' and id=?";
	    return $this->getByCmd($sql,array($id));
	}
}
