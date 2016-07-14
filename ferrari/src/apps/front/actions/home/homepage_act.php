<?php

class Action_home_homepage extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("home/homepage.html");
    }
}

class Action_home_miss extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("home/miss.html");
    }
}
class Action_home_nopower extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("home/nopower.html");
    }
}
class Action_home_homeindex extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $name = '马新';
        $xcontext->name = $name;
        return XNext::useTpl("index.html");
    }
}