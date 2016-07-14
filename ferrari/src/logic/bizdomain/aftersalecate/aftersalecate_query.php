<?php
/**
 * 订单售后信息列表
 */
class AfterSaleCateQuery extends Query
{
	public function listSaleCateData()
	{
		$sql = "SELECT id,catename,comment FROM aftersalecate WHERE isdelete='N'";

		return $this->listByCmd($sql);
	}
}