<?php
/**
 * 对审核中的订单进行修改
 */
class OrderVerifyWriter extends DWriter
{
	/*对订单进行审核*/
	public function doVerifySuccess($id)
	{
		$sql = "UPDATE orderinfo SET orstatus='P' WHERE id=?";

		return $this->exeByCmd($sql,array('id'=>$id));
	}

	/*对订单进行异常处理*/
	public function doUnusualByOId($oid,$unusualid,$ocomment)
	{
		$sql = "UPDATE orderinfo
				SET unusualid=$unusualid,comment = concat(comment,'[异常备注]:{$ocomment}')
				WHERE id = $oid";

		return $this->exeByCmd($sql,array('unusualid'=>$unusualid,'id'=>$oid));
	}
}