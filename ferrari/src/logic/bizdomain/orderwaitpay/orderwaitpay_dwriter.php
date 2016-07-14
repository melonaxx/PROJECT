<?php
/**
 * 待收款订单中对订单的修改
 */
class WaitPayOrderWriter extends DWriter
{
	/*关闭订单*/
	public function doCloseOrderById($id)
	{
		$sql = "UPDATE orderinfo SET isdelete='Y' WHERE id=?";

		return $this->exeByCmd($sql,array('id'=>$id));
	}
}