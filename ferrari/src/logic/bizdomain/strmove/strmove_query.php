<?php
/**
 * @brief  调拨单信息列表
 * @param
 * @return
 * */
class StrMoveQuery extends Query
{
	/*列出调拨单的记录*/
	public function listMovedata($startdate,$enddate,$total,$page,$pagesize)
	{
		$where = '';
		//转换时间戳

		if ($startdate && !$enddate)
		{
			$startdate = date('Y-m-d H:i:s',strtotime($startdate));
			$where = " WHERE createtime>='$startdate'";
		} else if(!$startdate && $enddate)
		{
			$enddate   = date('Y-m-d H:i:s',strtotime($enddate)+86400);
			$where = " WHERE createtime<='$enddate'";
		} else if ($startdate && $enddate)
		{
			$startdate = date('Y-m-d H:i:s',strtotime($startdate));
			$enddate   = date('Y-m-d H:i:s',strtotime($enddate)+86400);
			$where = " WHERE createtime BETWEEN '$startdate' AND '$enddate'";
		} else if ($startdate && $enddate && $startdate >= $enddate)
		{
			$startdate = date('Y-m-d H:i:s',strtotime($startdate));
			$enddate = $startdate;
			$where = " WHERE createtime BETWEEN '$startdate' AND '$enddate'";
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


		if ($total == 'total')
		{
			$sql = "SELECT count(*) AS total
				FROM strmove".$where;
		} else if ($total == 'list')
		{
			$where .=" ORDER BY createtime DESC LIMIT {$pages},{$pagesize}";
			$sql = "SELECT id,createtime,movetype,productid,moveoutid,moveinid,staffid,total,comment
				FROM strmove".$where.';';
		}
		return $this->listByCmd($sql,array());
	}

}