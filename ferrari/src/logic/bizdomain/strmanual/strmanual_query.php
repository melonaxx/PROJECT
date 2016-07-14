<?php
/**
 * @brief  手动出入库
 * @param
 * @return
 * */
class StrManualQuery extends Query
{
	/*列出出入库的记录*/
	public function listManualInOut($storeid,$page,$pagesize)
	{
		//仓库搜索
		$where = '';
		if ($storeid)
		{
			$where = " WHERE p.storeid = '$storeid' ORDER BY s.createtime DESC";
		}

		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
			$where .=" LIMIT {$sqlpage},{$pagesize}";
		}


		$sql = "SELECT s.id,s.createtime,s.productid,s.storeid,s.type,s.purposetype,s.total,s.staffid,s.comment
		FROM strmanual AS s
		LEFT JOIN strproduct AS p ON s.productid = p.productid AND s.storeid = p.storeid".$where;

		return $this->listByCmd($sql,array());
	}

	/*获取出入库的总数量*/
	public function totalManual($storeid)
	{
		//仓库搜索
		$where = '';
		if ($storeid)
		{
			$where = " WHERE p.storeid = '$storeid'";
		}

		$sql = "SELECT count(*) as total
		FROM strmanual AS s
		LEFT JOIN strproduct AS p ON s.productid = p.productid AND s.storeid = p.storeid".$where;

		return $this->getByCmd($sql,array());
	}
}