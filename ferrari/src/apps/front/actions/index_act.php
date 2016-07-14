<?php

class Action_index extends XLoginAction
{
    public function _run($request,$xcontext)
    {
        return XNext::useTpl("index.html");
    }
}

