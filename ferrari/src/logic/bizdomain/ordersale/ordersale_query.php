<?php
/**
 * 订单的售后服务
 */
class OrderSaleQuery extends Query
{
	/**
	 * 订单售后显示列表
	 * @param  str $search   搜索的信息
	 * @param  int $page     当前页
	 * @param  int $pagesize 页大小
	 * @param  str $total    是否是总数还是信息列表
	 * @return obj           订单售后列表
	 */
	public function listOrderSaleData($search,$page,$pagesize,$total)
	{
		$where = " WHERE o.isdelete='N' AND o.type='N' AND o.unusualid='0'";
		if (!empty($search)) {
			$where .=" AND (INSTR(o.id,'{$search}') OR INSTR(c.name,'{$search}'))";
		}

		//分页
		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
			$splitpage =" GROUP BY o.id ORDER BY o.createtime DESC LIMIT {$sqlpage},{$pagesize}";
		}

		if ($total == 'list') {
			$sql = "SELECT o.createtime,o.id AS orderid,o.serviceid,o.custimes,
			s.id AS channelid,s.name AS channelname,
			d.type AS deltype,d.transportid,d.waybill,
			sum(p.total) AS prototal,
			r.name AS storename,
			g.name AS catename,
			c.name AS cusname,c.mobile,
			y.name AS shopname
			FROM orderinfo AS o
			LEFT JOIN companysales AS s ON s.id=o.channelid
			LEFT JOIN orderdeliver AS d ON d.orderid=o.id
			LEFT JOIN orderproduct AS p ON p.orderid=o.id
			LEFT JOIN storeinfo AS r ON r.id=o.storeid
			LEFT JOIN ordercategory AS g ON g.id=o.categoryid
			LEFT JOIN customerinfo AS c ON c.id=o.customerid
			LEFT JOIN systemshop AS y ON y.id=o.companyid".$where.$splitpage;

		} elseif ($total == 'total') {
			$sql = "SELECT count(distinct o.id) AS total
			FROM orderinfo AS o
			LEFT JOIN companysales AS s ON s.id=o.channelid
			LEFT JOIN orderdeliver AS d ON d.orderid=o.id
			LEFT JOIN orderproduct AS p ON p.orderid=o.id
			LEFT JOIN storeinfo AS r ON r.id=o.storeid
			LEFT JOIN ordercategory AS g ON g.id=o.categoryid
			LEFT JOIN customerinfo AS c ON c.id=o.customerid
			LEFT JOIN systemshop AS y ON y.id=o.companyid".$where;
		}

		return $this->listByCmd($sql);
	}
}
