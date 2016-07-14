<?php
/**
 * @brief  通过商品ID获取商品所在仓库
 *
 * @param 商品ID
 *
 * @return 商品所在仓库ID列表
 * */
class GetStoreByPorIdQuery extends Query
{
	public function listStroinfo($productid)
	{
	    $sql = "select * from strrelated where productid=?";
	    return $this->listByCmd($sql,array('productid'=>$productid));
	}
}

/**
 * @brief  通过区位ID查询所在位架的数量
 *
 * @param 区位ID
 *
 * @return 商品所在的位架数量
 * */
class GetStoreAreaLocationTotalQuery extends Query
{
	public function AreaLoationTotal($alid)
	{
	    $sql = "select count(*) as total from strlocation where parentid=? and isdelete = 'N'";
	    return $this->listByCmd($sql,array('parentid'=>$alid));
	}
}

/**
 * @brief  查询一个商品是否在一个仓库中
 *
 * @param 仓库ID 商品ID
 *
 * @return bool
 * */
class IsGoodInStoreQuery extends Query
{
	public function goosinstore($storeid,$productid,$areaid,$shelvesid,$locationid)
	{
	    $sql = "select count(*) as total from strrelated where storeid=? and productid = ? and areaid=? and shelvesid=? and locationid=?";
	    return $this->getByCmd($sql,array('storeid'=>$storeid,'productid'=>$productid,'areaid'=>$areaid,'shelvesid'=>$shelvesid,'locationid'=>$locationid));
	}
}

/**
 * @brief  通过仓库ID和货位ID查询商品的信息
 *
 * @param 仓库ID 货位ID
 *
 * @return 商品列表
 * */
class ListGoodsBystrlocIdQuery extends Query
{
	public function listgoodsdata($storeid,$locationid)
	{
	    $sql = "select productid from strrelated where storeid=? and locationid=? and status = 0";
	    return $this->listByCmd($sql,array('storeid'=>$storeid,'locationid'=>$locationid));
	}
}
