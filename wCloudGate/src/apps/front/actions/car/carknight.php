<?php

/**
 * @brief   骑士车辆管理
 */
class Action_carknight extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype != UserType::KNIGHT) {
            $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainUrl);
        }
    	$client = GClientAltar::getLaborClient();
        $result = $client->showKnightEbikeInfo($xcontext->userid );
        $data = $result->data;
        $xcontext->data = $data;
        $xcontext->knightseqno = $result->data ? $result->data['seqno'] : 0;
        $xcontext->knighttype = $xcontext->usertype;
        
    	return XNext::useTpl("/car/carknight.html");
    }
}