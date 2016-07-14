<?php
/**
 * 订单的售后->待处理售后单
 */
class SaleServiceProcessQuery extends Query
{
	/**
	 * 订单售单处理显示列表
	 * @param  str $search   搜索的信息
	 * @param  int $page     当前页
	 * @param  int $pagesize 页大小
	 * @param  str $total    是否是总数还是信息列表
	 * @return obj           订单售后列表
	 */
	public function listSaleProcess($search,$page,$pagesize,$total)
	{
		$where = " WHERE o.isdelete='N' AND o.type='N' AND o.unusualid='0' AND s.status='N' AND s.isdelete='N'";
		if (!empty($search)) {
			$where .=" AND (INSTR(o.onlineid,'{$search}') OR INSTR(c.name,'{$search}'))";
		}

		//分页
		if ($page && $pagesize)
		{
    		$sqlpage = ($page-1)*$pagesize;
			$splitpage =" ORDER BY s.createtime DESC LIMIT {$sqlpage},{$pagesize}";
		}

		if ($total == 'list') {
			$sql = "SELECT s.id AS saleid,s.backpay,s.backexpress,s.number,s.saletype,s.staffid,
			o.customerid,o.onlineid,o.id AS orderid,
			g.catename,
			c.name AS cusname,c.mobile,
			y.name AS shopname,y.id AS shopid
			FROM ordersaleinfo AS s
			LEFT JOIN orderinfo AS o ON o.id=s.orderid
			LEFT JOIN aftersalecate AS g ON g.id=s.cateid
			LEFT JOIN customerinfo AS c ON c.id=o.customerid
			LEFT JOIN systemshop AS y ON y.id=o.companyid".$where.$splitpage;

		} elseif ($total == 'total') {
			$sql = "SELECT count(*) AS total
			FROM ordersaleinfo AS s
			LEFT JOIN orderinfo AS o ON o.id=s.orderid
			LEFT JOIN aftersalecate AS g ON g.id=s.cateid
			LEFT JOIN customerinfo AS c ON c.id=o.customerid
			LEFT JOIN systemshop AS y ON y.id=o.companyid".$where;
		}

		return $this->listByCmd($sql);
	}

	/*通过售后ID获取商品信息列表*/
	public function listProBySaleId($saleid)
	{
		$sql = 'SELECT total,productid FROM asaleproduct WHERE asaleid=?';

		return $this->listByCmd($sql,array('saleid'=>$saleid));
	}
}
