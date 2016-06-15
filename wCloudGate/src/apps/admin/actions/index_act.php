<?php

class Action_index extends XAction
{
    public function _run($request,$xcontext)
    {
        header("Location: /login.php");
        return XNext::nothing();
        // return XNext::gotoUrl("/login_index.php");
    }
}
