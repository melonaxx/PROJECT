<?php

class Action_quanxian extends XAction
{
    public function _run($request, $xcontext)
    {
    	$ql = SundialQL::create()
          ->select()
          ->from("user")
          ->leftJoin("userinfo", "$0.id=$1.userid");
          // ->where("$0.gender", '1')
          // ->ors()
          // ->where("$1.type", "A")
          // ->where("$1.address", '$in', array('beijing', 'shanghai'))
          // ->endOr();
		$result = $ql->getStatement();  
		echo "<pre>";
		var_dump($result);
    }
}
	