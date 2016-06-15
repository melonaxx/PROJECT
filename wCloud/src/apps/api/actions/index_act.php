<?php

class Action_index extends XAction
{
    public function _run($request,$xcontext)
    {
        echo "This is API Sys";
        return XNext::nothing();
    }
}






