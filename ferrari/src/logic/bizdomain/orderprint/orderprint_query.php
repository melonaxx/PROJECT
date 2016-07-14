<?php
/**
 * 订单印刷单查询显示
 */
class OrderPrintQuery extends Query
{
	/**
	 * 显示印刷单列表
	 * @param  str $search   搜索的信息
	 * @param  int $page     当前页
	 * @param  int $pagesize 页大小
	 * @param  str $total    是否是总数还是信息列表
	 * @param  str $status   搜索的条件
	 * @param  str $complate 是否完成的条件
	 * @return obj           印刷信息列表
	 */
	public function listOrderPrintData($search,$page,$pagesize,$total,$status,$complate)
	{
		$where = " WHERE o.isdelete='N' AND o.type='N' AND o.unusualid='0' AND n.affirm='{$status}' AND n.complatesta='{$complate}'";
		if (!empty($search)) {
			$where .=" AND (INSTR(o.id,'{$search}') OR INSTR(n.contents,'{$search}'))";
		}

		//分页
		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
			$splitpage =" GROUP BY o.id ORDER BY o.createtime DESC LIMIT {$sqlpage},{$pagesize}";
		}

		if ($total == 'list') {
			$sql = "SELECT o.createtime,o.id AS orderid,o.serviceid,
			s.id AS channelid,s.name AS channelname,
			d.type AS deltype,d.transportid,
			sum(p.total) AS prototal,
			n.contents,n.id AS printid,
			g.name AS catename,
			c.name AS cusname,c.mobile,
			y.name AS shopname
			FROM orderinfo AS o
			LEFT JOIN companysales AS s ON s.id=o.channelid
			LEFT JOIN orderdeliver AS d ON d.orderid=o.id
			LEFT JOIN orderproduct AS p ON p.orderid=o.id
			LEFT JOIN orderprint AS n ON n.orderid=o.id
			LEFT JOIN ordercategory AS g ON g.id=o.categoryid
			LEFT JOIN customerinfo AS c ON c.id=o.customerid
			LEFT JOIN systemshop AS y ON y.id=o.companyid".$where.$splitpage;

		} elseif ($total == 'total') {
			$sql = "SELECT count(distinct o.id) AS total
			FROM orderinfo AS o
			LEFT JOIN companysales AS s ON s.id=o.channelid
			LEFT JOIN orderdeliver AS d ON d.orderid=o.id
			LEFT JOIN orderproduct AS p ON p.orderid=o.id
			LEFT JOIN orderprint AS n ON n.orderid=o.id
			LEFT JOIN ordercategory AS g ON g.id=o.categoryid
			LEFT JOIN customerinfo AS c ON c.id=o.customerid
			LEFT JOIN systemshop AS y ON y.id=o.companyid".$where;
		}

		return $this->listByCmd($sql);
	}

	public function findfororderid($orderid)
	{
	    $sql = "select * from orderprint where orderid = ?";
	    return $this->getByCmd($sql,array($orderid));
	}
}