<?php
/**
 * @brief 通过partskey搜索商品的配件值。
 *
 * @param partskey 关键字
 *
 * @return 搜索到的高品配件列表
 **/
class SearchPartsByKeyQuery extends Query
{
	public function listpartsInfo($partskey)
	{
	    $sql = "select productid,name from product where name like '%$partskey%'";
	    return $this->listByCmd($sql);
	}
}

/**
 * @brief 通过商品ID获取配件的ID。
 *
 * @param productid
 *
 * @return 搜索到的商品配件列表
 **/
class GetPartsByIdQuery extends Query
{
	public function listpartsInfo($productid)
	{
	    $sql = "select * from proparts where productid=?";
	    return $this->listByCmd($sql,array('productid'=>$productid));
	}
}