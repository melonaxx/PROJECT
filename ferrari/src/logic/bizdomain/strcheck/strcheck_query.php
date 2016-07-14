<?php
/**
 * @brief  仓库盘点单列表查询
 *
 * @param int $storeid 仓库ID
 * @param string $startdate 起始时间
 * @param string $enddate 结束时间
 * @param int $page 当前页数
 * @param int $pagesize 页大小
 * @param int $total 是否是总条数
 *
 * @return 仓库盘点单列表
 * */
class ShowStoreCheckListQuery extends Query
{
	public function listCheciInfo($storeid,$startdate,$enddate,$page,$pagesize,$total)
	{
		$where = '';
		if (!empty($storeid)) {
			$where.=" WHERE c.storeid='{$storeid}'";
		}
		//有起始时间没有结束时间
		if ($startdate && !$enddate)
		{
			$startdate = date('Y-m-d H:i:s',strtotime($startdate));
			$where .= " AND c.createtime>='$startdate'";
		} else if(!$startdate && $enddate)
		{
			$enddate   = date('Y-m-d H:i:s',strtotime($enddate)+86400);
			$where .= " AND c.createtime<='$enddate'";
		} else if ($startdate && $enddate)
		{
			$startdate = date('Y-m-d H:i:s',strtotime($startdate));
			$enddate   = date('Y-m-d H:i:s',strtotime($enddate)+86400);
			$where .= " AND c.createtime BETWEEN '$startdate' AND '$enddate'";
		} else if ($startdate && $enddate && $startdate >= $enddate)
		{
			$startdate = date('Y-m-d H:i:s',strtotime($startdate));
			$enddate = $startdate;
			$where .= " AND c.createtime BETWEEN '$startdate' AND '$enddate'";
		}

		/*分页数据*/
		if (!empty($page) && !empty($pagesize))
		{
			if (empty($page))
			{
				$page = 1;
			}
			if (empty($pagesize))
			{
				$pagesize = Core_Lib_Page::PAGESIZE;
			}
			$pages = ($page-1)*$pagesize;

		}

		if ($total == 'list') {
			$sql = "SELECT s.name AS storename,
		    p.name AS productname,p.productid AS productid,p.image,
		    c.createtime,c.staffid,c.id,c.comment,c.total,c.oldtotal,c.newtotal
		    FROM strcheck AS c
		    LEFT JOIN product AS p ON c.productid=p.productid
		    LEFT JOIN storeinfo AS s ON s.id=c.storeid".$where." ORDER BY c.createtime DESC LIMIT {$pages},{$pagesize}";

		} elseif ($total == 'total') {
			$sql = "SELECT count(*) AS total
		    FROM strcheck AS c
		    LEFT JOIN product AS p ON c.productid=p.productid
		    LEFT JOIN storeinfo AS s ON s.id=c.storeid".$where;
		}

	    return $this->listByCmd($sql,array('storeid'=>$storeid));
	}
}