<?php
/**
 * 订单中的打回订单
 */
class OrderCallBackWriter extends DWriter
{
	/**
	 * 订单打回到某处
	 * @param  int $orderid 订单ID
	 * @param  string $type    要改变的订单状态: N 未审核,P:打单配货(已审核), T:条码验货, C:称重计费, F:扫单发货,
	 * @return bool
	 */
    public function dogobackaim($orderid,$type)
    {
        $sql = "UPDATE orderinfo SET orstatus=? WHERE id=?";

        return $this->exeByCmd($sql, array('orstatus'=>$type,'$id'=>$orderid));
    }

}