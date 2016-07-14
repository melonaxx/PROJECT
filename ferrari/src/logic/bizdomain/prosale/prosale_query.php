<?php
/**
 * @brief 通过商品ID查询在售状态
 *
 * @param 商品ID productid
 *
 * @return 商品在售状态
 * */
class GetProSaleQuery extends Query
{
	public function getProSaleInfo($productid)
	{
	    $sql = "select * from prosale where productid=?";
	    return $this->listByCmd($sql,array('productid'=>$productid));
	}
}
