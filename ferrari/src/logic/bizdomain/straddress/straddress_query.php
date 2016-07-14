<?php
/**
 * @brief  显示仓库的发货址信息
 *
 * @param 仓库ID
 *
 * @return 省市的信息列表
 * */
class ShowShipAddressQuery extends Query
{
	public function listShipAddressinfo($storeid)
	{
	    $sql = "select * from straddress where storeid=?";
	    return $this->listByCmd($sql,array('storeid'=>$storeid));
	}
}

/**
 * @brief 通过仓库ID查询每个仓库分布的发货地址数
 *
 * @param 仓库ID
 *
 * @return 仓库民发货地址的个数
 * */
class getAddressByStoreIdQuery extends Query
{
	public function getShipNumber($storeid)
	{
	    $sql = "select count(*) as addrestotal from straddress where storeid=?";
	    return $this->getByCmd($sql,array('storeid'=>$storeid));
	}
}
