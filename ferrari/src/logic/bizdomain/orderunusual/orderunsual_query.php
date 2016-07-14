<?php
/**
 * 展示异常订单列表
 */
class OrderUnusualQuery extends Query
{
	/*列出订单异常列表*/
	public function listOrderUnusualData()
	{
		$sql = "SELECT id,name FROM orderunusual";

		return $this->listByCmd($sql);
	}
}