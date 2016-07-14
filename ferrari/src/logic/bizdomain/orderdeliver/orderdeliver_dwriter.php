<?php
/**
 * @brief 订单发货方式的修改
 *
 * @param
 *
 * @return bool
 * */
class OrderDeliverWriter extends DWriter
{
	/*通过订单ID修改发货快递*/
	public function editDeliverByOId($oid,$transportid)
	{
	    $sql = "UPDATE orderdeliver SET transportid=? WHERE orderid=?";

	    return $this->exeByCmd($sql,array('transportid'=>$transportid,'orderid'=>$oid));
	}
	//通过订单id修改全部信息
	public function update($transportid,$waybill,$freight,$realweight,$type,$orderid)
	{
	    $sql = "update orderdeliver set updatetime=now(),transportid=?,waybill=?,freight=?,realweight=?,type=? where orderid=?";

	    return $this->exeByCmd($sql,array($transportid,$waybill,$freight,$realweight,$type,$orderid));
	}
}