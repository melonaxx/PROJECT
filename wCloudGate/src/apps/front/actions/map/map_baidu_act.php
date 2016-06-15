<?php

//地图定位
class Action_baidumap extends XAction
{
    public function _run($request,$xcontext)
    {
        $uname = $_SESSION['data']['mobileno'];
        if(!empty($_SESSION['data']['name'])){
            $uname = $_SESSION['data']['name'];
        }
        $xcontext->uname = $uname;



        return XNext::useTpl('baidumap.html');
    }
}