<?php

class Action_main_mainplatform extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
    	return XNext::useTpl('main/mainplatform.html');
    }
}