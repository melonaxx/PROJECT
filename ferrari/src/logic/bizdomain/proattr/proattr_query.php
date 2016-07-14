<?php
/**
 * @brief 通过商品ID获取商品的属性值与属性名称列表
 *
 * @param productid 商品ID
 *
 * @return 对应的属性名称与属性值的列表
 * */
class GetProAttrQuery extends Query
{
	public function getAttrListInfo($productid)
	{
	    $sql = "select * from proattr where productid=?";
	    return $this->listByCmd($sql,array('productid'=>$productid));
	}
}
