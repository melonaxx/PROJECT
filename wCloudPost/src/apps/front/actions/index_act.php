<?php

class Action_index extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl('index.html');
    }
}









