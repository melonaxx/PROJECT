<?php

class Action_main_mainknight extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
    	return XNext::useTpl('main/mainknight.html');
    }
}

class Action_nearbyknight extends XPostAction
{
    public function _run($request,$xcontext)
    {
        $userid = $request->userid;
        $client = GClientAltar::getPlatformClient();
        $result = $client->showAroundKnightinfo($userid);

        if($result && $result->errno === 0){
            $data = $result->data;
            for($i=0;$i<count($data);$i++){
                if($data[$i]['userid']===$userid)
                {
                    unset($data[$i]);
                }
            }
            $data =  array_values($data);
            echo ResultSet::jsuccess($data);
            return XNext::nothing();
        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
    }
}
