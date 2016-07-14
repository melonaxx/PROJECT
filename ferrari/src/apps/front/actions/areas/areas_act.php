<?php

/**
 *  @brief select province
 *  @retrun
 * */
class Action_warehouse_searchpro extends XAction
{
    public function _run($request, $xcontext)
    {
        $pro = XDao::query('AreasQuery')->getProByLevel();

        if(!$pro) {
            echo ResultSet::jfail('404','not found prolist!');
        }
        echo ResultSet::jsuccess($pro);
        return XNext::nothing();
    }
}

/**
 * @brief select city
 *
 * @param $parendid
 *
 * @return number name level
 * */
class Action_warehouse_searchcity extends XAction
{
    public function _run($request, $xcontext)
    {
        $proid = $request->proid;

        $city = XDao::query('AreasQuery')->getCityByLevel($proid);

        if(!$city) {
            echo ResultSet::jfail('404','not found citylist!');
        }
        echo ResultSet::jsuccess($city);
        return XNext::nothing();
    }
}

/**
 * @brief select county
 *
 * @param $parendid
 *
 * @return number name level
 * */
class Action_warehouse_searchcounty extends XAction
{
    public function _run($request, $xcontext)
    {
        $cityid = $request->cityid;

        $county = XDao::query('AreasQuery')->getCountyByLevel($cityid);

        if(!$county) {
            echo ResultSet::jfail('404','not found countylist!');
        }
        echo ResultSet::jsuccess($county);
        return XNext::nothing();
    }
}

/**
 * @brief get name by number
 *
 * @param $number
 *
 * @return number name level
 * */
class Action_warehouse_getnamebynumber extends XAction
{
    public function _run($request, $xcontext)
    {
        $number = $request->number;

        $areadata = XDao::query('AreasQuery')->getNameByNumber($number);

        if(!$areadata) {
            echo ResultSet::jfail('404','not found getnamebynumber!');
        }
        echo ResultSet::jsuccess($areadata);
        return XNext::nothing();
    }
}


