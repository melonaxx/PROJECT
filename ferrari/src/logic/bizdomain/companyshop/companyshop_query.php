<?php
/**
 * @brief 查询所有店铺的信息
 *
 * @param
 *
 * @return 所有店铺
 **/
class ListCShopQuery extends Query
{
	public function listComShopInfo()
	{
	    $sql = "select id,name from systemshop where isdelete='N'";
	    return $this->listByCmd($sql);
	}
}