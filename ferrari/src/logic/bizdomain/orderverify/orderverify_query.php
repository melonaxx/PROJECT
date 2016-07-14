<?php
/**
 * 订单进行审核页面信息查询
 */
class OrderVerifyQuery extends Query
{
	public function listverifydata($search,$page,$pagesize,$total)
	{
		$where = " WHERE o.isdelete='N' AND p.isdelete='N' AND o.type='N' AND o.orstatus='N' AND o.unusualid='0'";
		if (!empty($search)) {
			$where .=" AND (INSTR(o.onlineid,'{$search}') OR INSTR(c.name,'{$search}'))";
		}

		//分页
		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
			$splitpage =" GROUP BY o.id ORDER BY o.createtime DESC LIMIT {$sqlpage},{$pagesize}";
		}

		if ($total == 'list') {
			$sql = "SELECT o.createtime,o.id AS orderid,o.onlineid AS onlineid,o.serviceid,
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
}