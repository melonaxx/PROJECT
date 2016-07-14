<?php
/**
 * @brief 查询仓库中所有区、架、位的信息
 *
 * @param [storid] 区、架、位ID
 *
 * @return 区、架、位的所有信息
 * */
class StoreListQuery extends Query
{
	public function listStoreData($parentid)
	{
	    $sql = "select id,placeno from strlocation where parentid=? and isdelete='N'";
	    return $this->listByCmd($sql,array($parentid));
	}
}

/**
 * @brief 查询仓库中所有库区总数
 *
 * @param [storid] 仓库ID
 *
 * @return 该仓库中所有库区总数
 * */
class StoreAreaTotalQuery extends Query
{
	public function listStoreAreaTotal($storeid)
	{
	    $sql = "select count(id) as areatotal from strlocation where parentid=? and isdelete='N'";
	    return $this->listByCmd($sql,array($storeid));
	}
}

/**
 * @brief  通过区架位ID查询父类ID与名称
 *
 * @param  区架位ID
 *
 * @return 商品父类信息
 * */
class getkParentByaflQuery extends Query
{
	public function listparentafl($aflid)
	{
	    $sql = "select * from strlocation where id=? and isdelete='N'";
	    return $this->getByCmd($sql,array('id'=>$aflid));
	}
}
