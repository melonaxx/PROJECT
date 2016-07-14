<?php
/**
 * 称重计费页面
 */
class Action_delivery_deliverweight extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("delivery/deliver_weight.html");
    }
}