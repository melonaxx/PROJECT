<?php
/**
 * 对待处理的售后单进行处理修改
 */
class OrderSaleInfoWriter extends DWriter
{
	/*关闭售后单*/
	public function closeOrderSale($saleid)
	{
		$sql = "UPDATE ordersaleinfo SET isdelete='Y' WHERE id=?";

		return $this->exeByCmd($sql,array('id'=>$saleid));
	}
	/*变为已解决售后单*/
	public function solveOrderSale($saleid)
	{
		$sql = "UPDATE ordersaleinfo SET status='Y' WHERE id=?";

		return $this->exeByCmd($sql,array('id'=>$saleid));
	}
}