<?php

class Action_childaccount extends XAction
{
    public function _run($request,$xcontent)
    {
        return XNext::useTpl('/mage_childaccount/childaccount_index.html');
    }
}