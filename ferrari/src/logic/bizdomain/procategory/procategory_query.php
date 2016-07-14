<?php
/**
 * @brief 查询商品分类信息
 *
 * @param
 *
 * @return 所有分类
 **/
class ListCategoryQuery extends Query
{
	public function listCategoryInfo()
	{
	    $sql = "select id,name,parentid from procategory where isdelete='N'";
	    return $this->listByCmd($sql);
	}
}

/**
 * @brief 通过ID进行判断本身是否有子类
 *
 * @param cateid
 *
 * @return bool
 **/
class HasCateByIdQuery extends Query
{
	public function HasChildCateInfo($cateid)
	{
	    $sql = "select count(id) as total from procategory where parentid= ? and isdelete='N'";
	    return $this->getByCmd($sql,array('parentid'=>$cateid));
	}
}