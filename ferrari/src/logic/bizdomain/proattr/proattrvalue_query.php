<?php
/**
 * @brief 查询商品中属性值列表
 *
 * @param
 *
 * @return 所有属性值的列表
 * */
class ListAttrValuesQuery extends Query
{
	public function listAttrValueInfo($panameid)
	{
	    $sql = "select id,optional,attribid from proattrvalue where isdelete='N' and attribid = ?";
	    return $this->listByCmd($sql,array('attribid'=>$panameid));
	}
}

/**
 * @brief 能过商品ID查询商品属性名称下属性值的个数
 *
 * @param $proattid
 *
 * @return 所有属性值个数
 * */
class AttrTotalByAttrNameIdQuery extends Query
{
	public function attrvaluetotal($panameid)
	{
	    $sql = "select count(*) as total from proattrvalue where isdelete='N' and attribid = ?";
	    return $this->listByCmd($sql,array('attribid'=>$panameid));
	}
}
