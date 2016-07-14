<?php
/**
 * 订单的售后->待处理售后单
 */
class ASaleProductQuery extends Query
{
	/**
	 * 订单售单处理显示列表
	 * @param  str $search   搜索的信息
	 * @param  int $page     当前页
	 * @param  int $pagesize 页大小
	 * @param  str $total    是否是总数还是信息列表
	 * @return obj           订单售后列表
	 */
	public function listASaleProduct($search,$page,$pagesize,$total)
	{
		$where = " WHERE o.isdelete='N' AND o.type='N' AND o.unusualid='0'";
		if (!empty($search)) {
			$where .=" AND (INSTR(o.onlineid,'{$search}') OR INSTR(s.asaleid,'{$search}'))";
		}

		//分页
		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
			$splitpage =" ORDER BY s.createtime DESC LIMIT {$sqlpage},{$pagesize}";
		}

		if ($total == 'list') {
			$sql = "SELECT s.id AS asalestoreid,s.asaleid,s.inedstore,s.outstore,s.comment,
			o.onlineid,o.id AS orderid,
			e.name AS storename,
			y.name AS shopname,y.id AS shopid
			FROM asaleinstore AS s
			LEFT JOIN orderinfo AS o ON o.id=s.orderid
			LEFT JOIN storeinfo AS e ON e.id=s.storeid
			LEFT JOIN systemshop AS y ON y.id=s.shopid".$where.$splitpage;

		} elseif ($total == 'total') {
			$sql = "SELECT count(*) AS total
			FROM asaleinstore AS s
			LEFT JOIN orderinfo AS o ON o.id=s.orderid
			LEFT JOIN storeinfo AS e ON e.id=s.storeid
			LEFT JOIN systemshop AS y ON y.id=s.shopid".$where;
		}

		return $this->listByCmd($sql);
	}
}
