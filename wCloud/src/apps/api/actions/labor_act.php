<?php

/*
|-----------------------------------------------------------
| 显示平台信息 Show Platform Info Action
|-----------------------------------------------------------
|
| 这个Action主要负责获取劳务方下的平台信息，并将其包
| 含的数据展示出来，也可作排序，根据条件来做排序。
|
*/
class Action_show_platform_info extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $info   = $request->info;
        $data   = $request->data;

        $cllink = $info ? $this->getPlatformInfo($userid, $data) : $this->getPlatformList($userid);

        $total   = $cllink['total'] ? array_pop($cllink) : null;
        $pageAll = $cllink['pageAll'] ? array_pop($cllink) : null;

        foreach($cllink as $k => &$v) {
            $company = CompanySvc::ins()->getCompanyById($v['platformid']); // 获取平台的信息
            if ($company) {
                if ($v['userid']) {
                    $user     = UserSvc::ins()->getUserById($v['userid']); // 如果有管员工，显示员工的基本信息

                    $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($v['userid']);

                    $v['mobileno'] = $userinfo['mobileno'] ? $userinfo['mobileno'] : 0;
                    $v['employeename'] = $user['name'] ? $user['name'] : "";
                }

                $owner = CBlinkSvc::ins()->getCBlinkByLab($v['laborid'], $v['platformid']); // 自有设置平台可查看的车辆
                if ($owner) {
                    $data = $this->getSeqnoInfo($owner, $v['platformid']);
                    $v['ownerseqno'] = $data;
                }

                $receive = CBlinkSvc::ins()->getCBlinkByLab($v['platformid'], $v['laborid']); // 接收平台的车辆
                if ($receive) {
                    $data = $this->getSeqnoInfo($receive, $v['platformid']);
                    $v['receiveseqno'] = $data;
                }

                $v['owner']   = $owner ? count($owner) : 0;
                $v['name']    = $company['name'];
                $v['receive'] = $receive ? count($receive) : 0;
                $v['linkman'] = $company['linkman'];
                $v['linkmono']= $company['mobileno'];
            } else {
                unset($cllink[$k]);
            }
        } 

        $cllink['pageAll'] = $pageAll;
        $cllink['total']   = $total;

        echo ResultSet::jsuccess($cllink);
        return XNext::nothing();
    }

    public function getSeqnoInfo($ebikeinfo, $platformid)
    {
        $data = array();
        foreach($ebikeinfo as $k => $v) {
            $ebike  = EbikeSvc::ins()->getEbikeById($v['ebikeid']);

            $allow  = CBlinkSvc::ins()->getCBlinkByUseId($platformid, $v['ebikeid']);

            $seqno  = $ebike['seqno'];
            $data[$seqno] = $allow ? 1 : 0; // 设置电车可查看
        } 

        return $data;
     }

    public function getPlatformList($userid)
    {
        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号对应的公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $cllink = CLlinkSvc::ins()->getsPlatformByLaborId($uclink['companyid']); // 获取劳务方关联的平台

        $cllink = $cllink ? Entity::convertToArray($cllink) : array();

        return $cllink;
    }

    public function getPlatformInfo($userid, $data)
    {
        $page = $data['page'];
        $num  = $data['num'];
        $name = $data['name'];

        $page = $page ? $page : null;
        $num  = $num ? $num : null;
        $name = $name ? " and company.name like '%$name%'" : "";

        $total = XDao::query("CLlinkQuery")->getTotalRowOfPlat($userid, $name); // 获取总条数
        $limit = HandlePage::getPage($page, $num, $total); // 设置分页
        
        $cllink = XDao::query("CLlinkQuery")->getRelatePlatform($userid, $limit['limit'], $name);  // 获取平台账号关联的所有劳务方
        $arr = array(
            "pageAll" => $limit['pageAll'],
            "total" => $total['sum']  
        );

        $cllink = $cllink ? array_merge($cllink, $arr) : array();
        
        return $cllink;
    }
}

/*
|-----------------------------------------------------------
| 显示骑士信息 Show Knight Info Action
|-----------------------------------------------------------
|
| 这个Action主要负责显示骑士列表。在劳务方账号下骑士模块
| 做显示用。
|
*/
class Action_show_knightinfo extends XAction
{
     public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $data    = $request->data;
        $kgid    = $data['kgid'];
        $name    = $data['name'];

        $page  = $data['page'] ? $data['page'] : null;
        $cpage = $data['cpage'] ? $data['cpage'] : null;
        $kgid  = $kgid ? " and userinfo.gropid=$kgid" : "";
        $name  = $name ? " and user.name like '%$name%'" : "";

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取请求账号的公司信息
        if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }        

        $total  = XDao::query("UClinkQuery")->getTotalRowOfKnight($company['companyid'], $kgid, $name);
        $limit  = HandlePage::getPage($page, $cpage, $total); // 设置分页

        $uclink = XDao::query("UClinkQuery")->getRelateKnight($company['companyid'], $kgid, $name, $limit['limit']); // 获取所有关联骑士的信息  

        if ($uclink) {
            foreach ($uclink as $k => &$v) {
                if ($v['ebikeid']) {
                    $ebike = EbikeSvc::ins()->getEbikeById($v['ebikeid']);
                }

                if ($v['gropid']) {
                    $kgrop = KGropSvc::ins()->showKGropByGropId($v['gropid']);
                }

                $v['seqno']      = $v['ebikeid']? $ebike['seqno'] : 0;
                $v['kgname']     = $v['gropid']? $kgrop['name'] : "";
                $v['distribute'] = $v['ebikeid'] ? 1 : -1;
            }
        }

        $uclink['pageAll'] = $limit['pageAll'];
        $uclink['total']   = $total['sum'];

        echo ResultSet::jsuccess($uclink);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 根据名称搜索平台 Search Platform By Name Action
|-----------------------------------------------------------
|
| 这个Action主要负责根据输入的名称来搜索匹配的平台，是
| 模糊匹配，主要用在单项搜索，首页侧边栏，平台模块
| 等暂时用到，后期可扩展，也可改写该Action。
|
*/
class Action_search_platform_by_name extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号的公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "Labor Not Found");
            return XNext::nothing();
        }

        $name    = $request->name;
        $company = XDao::query(CompanyQuery)->getCompany($name, $companytype=Company::COMPANYTYPE_PLATFORM); // 根据名称搜索匹配的平台
        if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        foreach($company as $k => &$v) {
            $cllink = CLlinkSvc::ins()->getPlatform($v['id'], $uclink['companyid']);
            if ($cllink) {
                if ($cllink['userid']) {
                    $user = UserSvc::ins()->getUserById($cllink['userid']); // 有绑定的员工，填补员工信息

                    $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($cllink['userid']);

                    $v['employeename'] = $user ? $user['name'] : "";
                    $v['mobileno']     = $userinfo ? $userinfo['mobileno'] : 0;
                }

                $owner = CBlinkSvc::ins()->getCBlinkByLab($uclink['companyid'], $v['id']);// 统计可查看的自有车辆

                $receive = CBlinkSvc::ins()->getCBlinkByLab($v['id'], $uclink['companyid']);// 统计接收车辆数量

                $v['owner']   = $owner ? count($owner) : 0;
                $v['receive'] = $receive ? count($receive) : 0;
            } else {
                unset($company[$k]); // 过滤掉没和自己关联的平台
            }
        }

        echo ResultSet::jsuccess($company);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 给骑士分配电车 Knight Distribute Ebike Action
|-----------------------------------------------------------
|
| 这个Action主要负责给骑士分配电车，然后电车给骑士建立关
| 联，电车相应状态更改，声明电车已被分配，骑士就可查
| 询该电车的状况。
|
*/
class Action_knight_distribute_ebike extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid   = $request->userid;
        $ebikeid  = $request->ebikeid;

        $userinfo = UserInfoSvc::ins()->addEbikeById($userid, $ebikeid); // 电车跟骑士建立关联

        $ebike = EbikeSvc::ins()->updateDistribute($ebikeid, $allot=Ebike::ALLOT_BIND); // 电车建立关联后，修改该电车的状态，声明已被分配
        if (!$ebike) {
            echo ResultSet::jfail(403, "Knight Distribute Ebike Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 给骑士解绑电车 Knight UnWrap Ebike Action
|-----------------------------------------------------------
|
| 这个Action主要负责解绑分给骑士的电车，释放电车，电车状
| 态也要恢复未分配状态，其它骑士可重新跟此电车建立关系。
| 
*/
class Action_knight_unwrap_ebike extends XPostAction
{
     public function _run($request, $xcontext)
    {
       $userid   = $request->userid; 
       $ebikeid  = $request->ebikeid;

       $userinfo = UserInfoSvc::ins()->unwrapEbike($userid); // 解除电车跟骑士的关联
       if (!$userinfo) {
            echo ResultSet::jfail(4031, "UnWrap Ebike Fail");
            return XNext::nothing();
       }

       $ebike = EbikeSvc::ins()->updateDistribute($ebikeid, $allot=Ebike::ALLOT_UNBIND); // 将电车恢复未分配的状态
       if (!$ebike) {
            echo ResultSet::jfail(403, "Update Distribute Of UnBind Fail");
            return XNext::nothing();
       }

       echo ResultSet::jsuccess();
       return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 显示劳务方车辆信息 Show Labor EbikeInfo Action
|-----------------------------------------------------------
|
| 这个Action主要负责查询当前劳务方的所有车辆，包括，自己
| 绑定的车和平台分配的车辆，同时还显示车辆的状态，即
| 是否已经和骑士绑定。后期功能扩展可改写Action。
|
*/
class Action_show_labor_ebikeinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $laborid = $request->laborid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号的公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }        

        if ($laborid) {
            /* 根据指定劳务方来查询车辆信息 */
            $owner = CBlinkSvc::ins()->getCBlinkByLab($uclink['companyid'], $laborid);

            $allow = CBlinkSvc::ins()->getCBlinkByLab($laborid, $uclink['companyid']);

            $own = $owner ? Entity::convertToArray($owner) : array();
            $aow = $allow ? Entity::convertToArray($allow) : array();
            if ($own || $aow) {
                $cblink = array_merge($own, $aow);
            }
        } else {
            /* 显示当前账号下的所有车辆信息 */
            $owner = CBlinkSvc::ins()->getCBlink($uclink['companyid']);

            $use   = CBlinkSvc::ins()->getCBlinkByUseId($uclink['companyid']);

            $own = $owner ? Entity::convertToArray($owner) : array();
            $use = $use ? Entity::convertToArray($use) : array();
            if ($own || $use) {
                $cblink = array_merge($own, $use); 
            }
        }

        if ($cblink) {
            foreach($cblink as $k => &$v) {
                $ebike = EbikeSvc::ins()->getEbikeById($v['ebikeid']);

                $v['seqno'] = $ebike['seqno'];
                $v['allot'] = $ebike['allot'];
            }
        }

        echo ResultSet::jsuccess($cblink);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 设置允许平台可查看的车 Allow Platform Look Action
|-----------------------------------------------------------
|
| 这个Action主要负责设置劳务方自己绑定的车辆赋予关联
| 平台可查看，同样车的绑定状态也要更改，目前做的
| 一车只能设置一个平台查看，后期可扩展多方
| 查看，改写此Action
|
*/
class Action_allow_platform_look extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid     = $request->userid;
        $ebikeid    = $request->ebikeid;
        $platformid = $request->platformid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号的公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $distribute = CBlink::DISTRIBUTE_TRUE;
        $cblink     = CBlinkSvc::ins()->updateUseIdByCompanyId($uclink['companyid'], $ebikeid, $platformid, $distribute); // 设置关联的公司可查看
        if (!$cblink) {
            echo ResultSet::jfail(500, "Server Error Of Allow Look");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 取消平台查看 Forbid Platform Look Action
|-----------------------------------------------------------
|
| 这个Action主要负责取消平台查看劳务方的车辆状况，取消
| 后，平台将不在能查看到被取消的车的动态状况。目前
| 鉴于车的查看是一对一，取消也是一对一的，后期
| 扩展可改写此Action。
|
*/
class Action_forbid_platform_look extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $ebikeid = $request->ebikeid;

        $uclink  = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号的公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $platformid = 0; 
        $distribute = CBlink::DISTRIBUTE_FALSE;
        $cblink     = CBlinkSvc::ins()->updateUseIdByCompanyId($uclink['companyid'], $ebikeid, $platformid, $distribute); // 取消平台的查看所要更改的状态
        if (!$cblink) {
            echo ResultSet::jfail(500, "Server Error");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 创建骑士分组 Store KGrop Action
|-----------------------------------------------------------
|
| 这个Action主要负责创建骑士分组，骑士分组可划分区域，方
| 遍管理。后期可扩展多态化分组，改写此Action。
|
*/
class Action_store_kgrop extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $name    = $request->name;
        $userid  = $request->userid;

        $uclink  = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取骑士对应的劳务方id
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $kgrop   = KGropSvc::ins()->createKGrop($name, $uclink['companyid']); // 创建分组跟劳务方id关联
        if (!$kgrop) {
            echo ResultSet::jfail(500, "Server Error Of CreateKGrop");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 显示分组信息 Show KGrop Action
|-----------------------------------------------------------
|
| 这个Action主要负责显示劳务方下所有分组信息，包括分组
| 下的骑士数量一些数据。
|
*/
class Action_show_kgrop extends XAction
{ 
     public function _run($request, $xcontext)
    {
        $userid  = $request->userid;
        $uclink  = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号的公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $kgrop = KGropSvc::ins()->showAllKGropByLabId($uclink['companyid']); // 获取劳务方下的所有分组
        if (!$kgrop) {
            echo ResultSet::jfail(404, "KGrop Not Found");
            return XNext::nothing();
        }

        foreach($kgrop as &$v_kg) {
            $userinfo    = UserInfoSvc::ins()->getUserInfoByKGropId($v_kg['id']);

            $v_kg['cnt'] = $userinfo ? count($userinfo) : 0; // 统计分组下的骑士数量
        }

        $kgrop = Entity::convertToArray($kgrop);

        echo ResultSet::jsuccess($kgrop);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 修改分组名字 Update KGrop Action
|-----------------------------------------------------------
|
| 这个Action主要负责修改分组的名称。
|
*/
class Action_update_kgrop extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $name = $request->name;
        $id   = $request->id;

        $kgrop = KGropSvc::ins()->updateKGropById($id, $name); // 修改分组名称
        if (!$kgrop) {
            echo ResultSet::jfail(5032, "UpdateKGrop Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 删除分组 Destroy KGrop Action
|-----------------------------------------------------------
|
| 这个Action主要负责删除不需要的分组。删除分组之前要清
| 除组内的所有骑士，可将骑士重新分组。
|
*/
class Action_destroy_kgrop extends XPostAction
{
     public function _run($request, $xcontext)
    {
        $id = $request->id;

        $kg = UserInfoSvc::ins()->getUserInfoByKGropId($id); // 查看该分组内有没有骑士
        if ($kg) {
            $userinfo = UserInfoSvc::ins()->updateUserInfoByKGorpId($id); // 清楚组内的骑士
            if (!$userinfo) {
                echo ResultSet::jfail(403, "UpdateUserInfo Fail");
                return XNext::nothing();
            }
        }

        $kgrop = KGropSvc::ins()->destroyKGropById($id); // 删除分组
        if (!$kgrop) {
            echo ResultSet::jfail(500, "Server Error Of DestroyKGrop");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 为骑士分组 KGrop For Knight Action
|-----------------------------------------------------------
|
| 这个Action主要负责为骑士分组，一个骑士只能在一个组，后
| 期扩展可重写Action。
|
*/
class Action_kgrop_for_knight extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $kgid   = $request->kgid;
        $userid = $request->userid;

        $result = UserInfoSvc::ins()->updateKGropByUserId($userid, $kgid);
        if (!$result) {
            echo ResultSet::jfail(403, "Update KGrop Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 统计分组信息 Stat KGrop Info Action
|-----------------------------------------------------------
|
| 这个Action主要负责统计劳务方下分组情况，每个分组的车
| 辆统计，统计的内容包括，车辆总数，运行状态，异常
| 状态，休息状态，后期可扩展其它数据可改写此
| Action。
|
*/
class Action_stat_kgrop_info extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号对应公司信息
        if (!$uclink) {
            echo ResultSet::jfail(404, "company not found");
            return XNext::nothing();
        }

        $kgrop    = XDao::query("KGropQuery")->getKGropEbikeInfo($uclink['companyid']); // 获取分组内的车辆

        $stat     = new StatFactory();
        $result   = $stat->getEbikeStat($kgrop); // 获取账号下的车辆各种状态的统计数据

        echo ResultSet::jsuccess($result);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 统计单组车辆信息 Stat EbikeInfo By KGrop Action
|-----------------------------------------------------------
|
| 这个Action主要负责根据请求统计单组的车辆信息，这里组内
| 的车辆是根据骑士内的车辆来统计的，所以要过滤哪些没
| 分配车的骑士，统计的数据有车辆总数，运行车辆，休
| 息车辆，异常车辆，和一些历史状态供分析的数据。
| 
*/
class Action_stat_ebikeinfo_by_kgrop extends XAction
{
    public function _run($request, $xcontext)
    {
        $kgid = $request->id;

        $userinfo = UserInfoSvc::ins()->getUserInfoByKGropId($kgid); // 获取分组下的所有骑士

        if ($userinfo) {
            foreach($userinfo as $k => $v_user) {
                if (!$v_user['ebikeid']) {
                    unset($userinfo[$k]); // 过滤掉没车的骑士，可统计分组内的车辆数据
                }
            }
        }

        $stat     = new StatFactory();
        $result   = $stat->getEbikeStat($userinfo); // 获取车辆各种状态的统计数据

        echo ResultSet::jsuccess($result);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------------
| 搜索分组 Search KGrop By Name Action
|-----------------------------------------------------------
|
| 这个Action主要负责搜索分组的信息，做的是模糊匹配搜索，后
| 器可改写此功能。
|
*/
class Action_search_kgrop_by_name extends XAction
{
    public function _run($request, $xcontext)
    {
        $name   = $request->name;
        $userid = $request->userid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取请求账号对应的公司id
        if (!$uclink) {
            echo ResultSet::jfail(404, "company not found");
            return XNext::nothing();
        }

        $kgrop = XDao::query("KGropQuery")->getKGropByName($name, $uclink['companyid']); // 获取符合请求的分组
        if (!$kgrop) {
            echo ResultSet::jfail(404, "kgrop not found");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($kgrop);
        return XNext::nothing();
    }
}
