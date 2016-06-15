<?php

/**
 * @brief   骑士
 */
class Action_knight extends XUserinfoAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype != UserType::LABOR) {
            $mainurl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainurl);
        }
        $userid = $xcontext->userid;        
        $num = $request->num;
        $page = $request->page;
        $pageall = $request->pageall;
        $name = $request->name;
        $kgid = $request->gid;
        $searchdata = array();

        if($name || $kgid || $num || $page ) {
            if($num < 1) $num = 1;              
            if($num > $pageall) $num = $pageall;
            $searchdata = array(
                'name' => htmlspecialchars($name) ,
                'kgid' => intval($kgid) ,
                'cpage' => intval($num) ,
                'page' => intval($page)
            );
        }

        $client = GClientAltar::getLaborClient();
        $result = $client->showKnightInfo($userid  , $searchdata);
        if($result && $result->errno == 0) {
            $data = $result->data;
            if(is_array($data)) {
                $pageall = array_pop($data);
                $pagenum = array_pop($data);
            } else {
                $data = "";
            }
        }
        
        $xcontext->pageall = $pagenum ? $pagenum : 0;  //共几页      
        $xcontext->count  =$pageall ? $pageall : 0;  //共几条
        $xcontext->num = $num ? $num : 1;
        $xcontext->page = $page ? $page : PageNum::PAGENUM;
        $xcontext->data = $data;

        $client = GClientAltar::getLaborClient();
        $resultp = $client->showKGrop($userid );
        $xcontext->pdata = $resultp->data ? $resultp->data : "";

        return XNext::useTpl('/knight/knight.html');
    }
}

/**
 * @brief   显示车辆
 */
class Action_knightcarshow extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype != UserType::LABOR) {
            echo ResultSet::jfail(401 , 'You can not have access to the request');
            return XNext::nothing();
        }        
        $userid = $xcontext->userid;
        $client = GClientAltar::getLaborClient();
        $result = $client->showLaborEbikeInfo($userid );

        if($result && $result->errno == 0) {
            $data = $result->data ? $result->data : "";
            echo ResultSet::jsuccess($data);
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }
}

/**
 * @brief   劳务方 分配骑士车辆
 */
class Action_knightcar extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype != UserType::LABOR) {
            echo ResultSet::jfail(401 , 'You can not have access to the request');
            return XNext::nothing();
        }
        $knightid = $request->knightid;
        $ebikeid = $request->ebikeid;
        $client = GClientAltar::getLaborClient();
        $result = $client->knightDistributeEbike(intval($knightid) , intval($ebikeid) );

        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }
}

/**
 * @brief   劳务方解除骑士车辆
 */
class Action_knightuncar extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype != UserType::LABOR) {
            echo ResultSet::jfail(401 , 'You can not have access to the request');
            return XNext::nothing();
        }        
        $knightid = $request->knightid;
        $ebikeid = $request->ebikeid;
        $client = GClientAltar::getLaborClient();
        $result = $client->knightUnwrapEbike( intval($knightid) , intval($ebikeid) );

        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess($ebikeid);
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();

    }
}

/**
 * @brief   骑士删除
 */
class Action_knightdel extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        if($xcontext->usertype != UserType::LABOR) {
            echo ResultSet::jfail(401 , 'You can not have access to the request');
            return XNext::nothing();
        }
        $knightid = $request->knightid;
        $ebikeid = $request->ebikeid;
        $userid = $xcontext->userid;
        $type = UserType::KNIGHT;
        $client = GClientAltar::getPlatformClient();
        $result = $client->removeEmployee($userid , intval($knightid) , $type , intval($ebikeid) );
        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

/**
 * @brief   骑士分组显示
 */
class Action_knightgroupshow extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $client = GClientAltar::getLaborClient();
        $result = $client->showKGrop($userid );
        if($result && $result->errno == 0) {
            $data = $result->data ? $result->data : "";
            echo ResultSet::jsuccess($data);
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

/**
 * @brief   骑士分组增加
 */
class Action_knightgroupadd extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $userid = $xcontext->userid;
        $name = $request->name;
        $client = GClientAltar::getLaborClient();
        $result = $client->storeKGrop($userid , htmlspecialchars($name) );
        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

/**
 * @brief   骑士分组修改名称
 */
class Action_knightgroupedit extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $id = $request->id;
        $name = $request->name;
        $client = GClientAltar::getLaborClient();
        $result = $client->updateKGrop(intval($id) , htmlspecialchars($name) );
        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

/**
 * @brief   骑士分组删除
 */
class Action_knightgroupdel extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $id = $request->id;
        $client = GClientAltar::getLaborClient();
        $result = $client->destroyKGrop(intval($id) );
        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}

/**
 * @brief   骑士分组分配
 */
class Action_knighttogroup extends XKnightPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $id = $request->id;
        $kid = $request->kid;
        $client = GClientAltar::getLaborClient();
        $result = $client->kGropForKnight(intval($kid) , intval($id) );
        if($result && $result->errno == 0) {
            echo ResultSet::jsuccess();
            return XNext::nothing();
        }

        echo ResultSet::jfail(403  , 'The request failed');
        return XNext::nothing();
    }
}
