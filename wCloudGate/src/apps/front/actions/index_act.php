<?php

class Action_index extends XAction
{
    public function _run($request,$xcontext)
    {
    	if ($xcontext->userid) {
	    	$mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
	        return XNext::gotoUrl($mainUrl);
	    }else {
	    	$loginUrl = "http://" . $_SERVER['DOMAIN'] . "/login.php";
	        return XNext::gotoUrl($loginUrl);
	    }
    }
}

