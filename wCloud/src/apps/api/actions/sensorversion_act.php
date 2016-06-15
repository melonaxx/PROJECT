<?php

class Action_get_max_version extends XAction
{
    public function _run($request, $xcontext)
    {
        $maxcode = SensorVersionSvc::ins()->getMaxCode();

        echo ResultSet::jsuccess($maxcode);

        return XNext::nothing();
    }
}

class Action_sensorversion_upgrade extends XAction
{
    public function _run($request, $xcontext)
    {
        $code = $request->code;
        $name = $request->name;
        $uurl = $request->uurl;
        $upc  = $request->upc;
        $ups   = $request->ups;
        $md5  = $request->md5;
        $new_sensorversion = SenserVersionSvc::ins()->addVersion($code, $name, $uurl, $upc, $ups, $md5);
        if (!$new_sensorversion) {
            echo ResultSet::jfail(500, "Server Error:addVersion Fail");
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}
