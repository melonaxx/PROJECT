<?php
/**
 * @brief 查询商品中属性名称列表
 *
 * @param
 *
 * @return 所有属性名称的列表
 * */
class ListAttrQuery extends Query
{
	public function listAttrInfo()
	{
	    $sql = "select id,name from proattrname where isdelete='N'";
	    return $this->listByCmd($sql);
	}
}

/**
 * @brief 通过商品的属性名ID获取商品的属性值列表
 *
 * @param attrnameid
 *
 * @return 对应的属性值的列表
 * */
class ListAttrValueQuery extends Query
{
	public function listAttrValueInfo($attrnameid)
	{
	    $sql = "select id,optional from proattrvalue where isdelete='N' and attribid=$attrnameid";
	    return $this->listByCmd($sql);
	}
}
