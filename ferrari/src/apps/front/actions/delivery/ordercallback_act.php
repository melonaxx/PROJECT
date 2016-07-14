<?php
/**
 * 进行订单的打回操作
 */
class Action_delivery_ordercallback extends XAction
{
    public function _run($request, $xcontext)
    {
		$orderid = $request->orderid;
		$type    = $request->type;

		//开启事务
		$writer = XDao::dwriter('DWriter');
		$writer->beginTrans();

    	$orderstatus = XDao::dwriter('OrderCallBackWriter')->dogobackaim($orderid,$type);

    	if (!$orderstatus) {
    		$writer->rollback();
            echo ResultSet::jfail(500, "Server Error：ordercallback Fail");
            return XNext::nothing();
        }
        $writer->commit();
        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}