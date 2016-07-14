<?php
/**
 * 订单售单查询
 */
class Action_order_saleservicequery extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("order/saleservice_query.html");
    }
}