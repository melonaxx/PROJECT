<?php

/*
|-----------------------------------------------------
| 获取所有省 Get Province Action
|-----------------------------------------------------
|
| 这个Action主要负责获取数据库中所有省得数据，这种
| 数据常用的，而且是固定的，请求一次即可，然后
| 存入缓存，每次在请求的时候走缓存服务，可
| 缓解数据库压力。
|
*/
class Action_get_province extends XAction
{
    public function _run($request, $xcontext)
    {
        $new_province = XDao::query("CityCardQuery")->getProvince();
        if (!$new_province) {
            echo ResultSet::jfail(404, "Province Not Found");
            return XNext::nothing();
        }   

        echo ResultSet::jsuccess($new_province);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 获取省下所有城市  Get City Action
|-----------------------------------------------------
|
| 这个Action主要负责获取省下所有的城市，这个也可请
| 求一次，存入缓存，以后调用，走缓存即可
|
*/
class Action_get_city extends XAction
{
    public function _run($request, $xcontext)
    {
        $parent   = $request->parent;

        $new_city = XDao::query("CityCardQuery")->getCityByParent($parent); 
        if (!$new_city) {
            echo ResultSet::jfail(404, "City Not Found");
            return XNext::nothing();
        } 
        
        echo ResultSet::jsuccess($new_city);
        return XNext::nothing();
    }
}
