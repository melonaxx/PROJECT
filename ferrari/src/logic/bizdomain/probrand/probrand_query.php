<?php
/**
 * @brief 查询所有品牌信息
 *
 * @param
 *
 * @return 所有品牌
 **/
class ListBrandQuery extends Query
{
	public function listBrandInfo()
	{
	    $sql = "select id,name,parentid from probrand where isdelete='N'";
	    return $this->listByCmd($sql);
	}
}


/**
 * @brief 检测该商品下是否有子品牌（用于删除）
 *
 * @param  品牌ID
 *
 * @return bool 是否子品牌
 **/
class BrandIsChildQuery extends Query
{
	public function havebrandchild($brandid)
	{
	    $sql = "select count(*) as total from probrand where parentid = ? and isdelete='N'";
	    return $this->getByCmd($sql,array('parentid'=>$brandid));
	}
}