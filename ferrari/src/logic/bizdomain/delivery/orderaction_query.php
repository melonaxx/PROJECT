<?php
/**
 * 发货中的打单配货、扫单发货、称重计费信息查询
 */
class OrderActionQuery extends Query
{
	//通过订单ID查找
	public function listOrderByordAction($onlineid)
	{
		$sql = "SELECT o.id AS orderid,o.onlineid,o.cusmsg,o.comment,
		d.waybill,d.transportid,d.type AS transtype,
		c.name AS cusname,c.mobile
		FROM orderinfo AS o
		LEFT JOIN orderdeliver AS d ON d.orderid=o.id
		LEFT JOIN customerinfo AS c ON c.id=o.customerid
		WHERE o.onlineid=?";

		return $this->getByCmd($sql,array($onlineid));
	}

	//通过快递单查找
	public function listOrderByexpAction($express)
	{
		$sql = "SELECT o.id AS orderid,o.onlineid,o.cusmsg,o.comment,
		d.waybill,d.transportid,d.type AS transtype,
		c.name AS cusname,c.mobile
		FROM orderinfo AS o
		LEFT JOIN orderdeliver AS d ON d.orderid=o.id
		LEFT JOIN customerinfo AS c ON c.id=o.customerid
		WHERE d.waybill=?";

		return $this->getByCmd($sql,array($express));
	}

	//列出订单中的商品信息
	public function listProByOidAction($orderid)
	{
		$sql = "SELECT p.barcode,p.image,p.productid,
		d.total,d.orderid
		FROM product AS p
		LEFT JOIN orderproduct AS d ON d.productid=p.productid
		WHERE d.orderid=?";

		return $this->listByCmd($sql,array($orderid));
	}
}