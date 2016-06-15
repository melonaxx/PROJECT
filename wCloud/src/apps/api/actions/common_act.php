<?php

/*
|---------------------------------------------------
| 统计车辆的状况 Stat Ebike Action
|---------------------------------------------------
|
| 这个Action主要负责统计车辆的各种状态，这些统计
| 包括车辆总数，运行的车辆，异常车辆，和正在休
| 息的车辆，以及一些历史统计信息。
|
*/
class Action_stat_ebike extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid   = $request->userid;

        $uclink   = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $ebike    = XDao::query("CBlinkQuery")->getEbike($uclink['companyid']); // 获取自有车辆和其它可查看车辆
        $stat     = new StatFactory();
        $result   = $stat->getEbikeStat($ebike); // 获取账号下的车辆各种状态的统计数据

        echo ResultSet::jsuccess($result);
        return XNext::nothing();
    }
}

/*
|---------------------------------------------------
| 添加员工/骑士 Add Employee Action
|---------------------------------------------------
|
| 这个Action主要负责添加员工和骑士，根据用户类型
| 来判断是员工还是骑士设置不同的状态，来执行
| 不同的分支，同时，要添加的员工可能未注
| 册或未完善信息，也要保证他的状态保
| 持原样，为了流程正常执行。
|
*/
class Action_add_employee extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid   = $request->userid;
        $mobileno = $request->mobileno; 

        $user = UserSvc::ins()->getUserById($userid); // 获取账号基本信息，做类型判断用
        if (!$user) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        }

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号对应的公司id, 用来跟用户建立关系
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $userinfo   = UserInfoSvc::ins()->getUserInfoByMobileNo($mobileno); // 根据手机号来查询用户信息，来判断用户是否注册
        $employeeid = $userinfo['userid']; 
        if (!$userinfo) {
            $new_user = UserSvc::ins()->addUser($mobileno); // 用户还未注册，这里以公司的名义执行预注册
            $employeeid = $new_user['id'];
        }

        $uclink   = UClinkSvc::ins()->createUClink($uclink['companyid'], $employeeid); // 这里建立用户和公司的关系

        $employee = UserSvc::ins()->getUserById($employeeid);    

        $status = $employee['status'] == User::STATUS_COMPLETE_INFO ? User::STATUS_NORMAL : $employee['status'];

        $usertype = $user['usertype'] == User::USERTYPE_LABOR ? User::USERTYPE_KNIGHT : User::USERTYPE_EMPLOYEE; // 根据账号类型确定是劳务方，将用户类型改成骑士 

        $new_usertype = UserSvc::ins()->updateUserStauts($employeeid, $status, $usertype); // 根据上面设定的类型、状态来更改
        if (!$new_usertype) {
            echo ResultSet::jfail(500, "Server Error");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|---------------------------------------------------
| 统计关联公司车辆状况 Stat Company EbikeInfo Action
|---------------------------------------------------
|
| 这个Action主要负责统计平台--劳务方/劳务方--平台
| 的车辆状况，主要统计车辆总数，异常车辆，休息
| 车辆，运行车辆的数据统计，以及历史统计。
|
*/
class Action_stat_company_ebikeinfo extends XAction
{
     public function _run($request, $xcontext)
    {
        $userid    = $request->userid;
        $companyid = $request->companyid;

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $gather = EbikeGather::getEbikeGather($company['companyid'], $companyid); // 获取符合的车辆集

        $stat   = new StatFactory();
        $result = $stat->getEbikeStat($gather); // 调用统计工厂类来获取当前请求的车辆所有状态统计数据

        echo ResultSet::jsuccess($result);
        return XNext::nothing();
    }
}

/*
|---------------------------------------------------
| 车辆绑定/解绑 Ebike Assoc Action
|---------------------------------------------------
|
| 这个Action主要负责车辆的绑定和解绑，根据不同的
| 执行操作，来走不同的分支。
|
*/
class Action_ebike_assoc extends XPostAction
{
     public function _run($request, $xcontext)
    {
        $seqno  = $request->seqno;
        $userid = $request->userid;
        $act    = $request->act;

        foreach($seqno as $v_seq) {
            $ebike = EbikeSvc::ins()->getEbikeBySeqno($v_seq);
            if (!$ebike) {
                echo ResultSet::jfail(404, "Ebike Not Found");
                return XNext::nothing();    
            }

            $company = UClinkSvc::ins()->getCompanyIdByUserId($userid);
            if (!$company) {
                echo ResultSet::jfail(404, "Company Not Found");
                return XNext::nothing();
            }

            switch ($act) {
                case "activate"; // 这里执行绑定
                   $result = CBlinkSvc::ins()->activateEbike($company['companyid'], $ebike['id']); 
                break;
                case "unwrap"; // 这里执行解绑
                   $result = CBlinkSvc::ins()->removeCBlink($company['companyid'], $ebike['id']); 
                break;
            }

            if ($result) {
                $seqgather['success'][] = $v_seq; // 记录成功的序列号，做提示用
            } else {
                $seqgather['fail'][]    = $v_seq; // 记录失败的序列号，做提示用
            }
        }

        echo ResultSet::jsuccess($seqgather);
        return XNext::nothing();
    }
}

/*
|---------------------------------------------------
| 普通添加车辆 Normal Add Ebike Action
|---------------------------------------------------
|
| 这个Action主要负责那些车跟传感器分离的，不是一体
| 的，添加车辆。
|
*/
class Action_normal_add_ebike extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $data   = $request->data;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        foreach ($data as $v) {
            $brand   = $v['brand'];
            $seqno   = $v['seqno'];
            $imei    = $v['imei'];
            $mobel   = $v['mobel'];
            $remarks = $v['remarks'];

            $result = RegisterDevSvc::ins()->RegisterDev($imei, $mobel, $seqno, $brand, $remarks, $uclink['companyid']);
            if (!$result) {
                $gather['fail'][] = $seqno;
            }
        }

        echo ResultSet::jsuccess($gather);
        return XNext::nothing();
    }
}

/*
|---------------------------------------------------
| 劳务方查询 Search Labor Action
|---------------------------------------------------
|
| 这个Action主要负责查询劳务方，在员工账号下的
| 劳务方执行搜索，根据不同的需求，来执行不
| 同的分支，获取相应的数据。
|
*/
class Action_search_labor extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $name   = $request->name;
        $act    = $request->act;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid);

        switch($act) {
            case "name"; // 执行根据劳务方名字来查询劳务方
                    $result = $this->queryName($uclink['companyid'], $name);
            break;
            case "employee"; // 执行根据员工来查询劳务方
                    $result = $this->queryEmployee($userid);
            break;
            case "both"; // 执行根据员工和劳务方名称来查询劳务方
                    $result = $this->queryBoth($userid, $name);            
            break;
        }

        echo ResultSet::jsuccess($result);
        return XNext::nothing();     
    }
 
     public function queryBoth($userid, $name)
     {
         $labor = XDao::query("CLlinkQuery")->getLabor($userid, $name); // 根据公司名称获取对应关联的劳务方
         if (!$labor) {
             return $labor = null;
         }

         foreach($labor as &$v_lab) {
             $gather = EbikeGather::getEbikeGather($v_lab['platformid'], $v_lab['laborid']); // 获取符合的车辆集

             $v_lab['sum'] = count($gather);

             if ($v_lab['userid']) {
                 $user = UserSvc::ins()->getUserById($v_lab['userid']);  // 获取用户基本信息

                 $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($v_lab['userid']); // 获取用户详情信息
             }

             $v_lab['username'] = $user['name'] ? $user['name'] : "";
             $v_lab['mobileno'] = $userinfo ? $userinfo['mobileno'] : 0;
         }

         return $labor;
     }

     public function queryEmployee($userid)
    {
        $labor = CLlinkSvc::ins()->getLaborByUserId($userid); // 获取员工关联的劳务方
        if (!$labor) {
            return $labor = null;
        }

        foreach($labor as &$v_lab) {
            $company = CompanySvc::ins()->getCompanyById($v_lab['laborid']); // 获取公司信息
            $v_lab['name'] = $company['name'];

            $gather = EbikeGather::getEbikeGather($v_lab['platformid'], $v_lab['laborid']); // 获取符合的车辆集

            $v_lab['sum'] = count($gather);

            if ($v_lab['userid']) {
                $user = UserSvc::ins()->getUserById($v_lab['userid']); // 获取用户的基本信息

                $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($v_lab['userid']); // 获取用户的详情信息
            }

            $v_lab['username'] = $user['name'] ? $user['name'] : "";
            $v_lab['mobileno'] = $userinfo ? $userinfo['mobileno'] : 0;
        }

        $labor = Entity::convertToArray($labor);

        return $labor;
    }

    public function queryName($companyid, $name)
    {
        $labor  = XDao::query("CompanyQuery")->getCompanyByName($name, $companyid); // 根据名称获取平台下的劳务方
        if (!$labor) {
            return $labor = null;
        }

        foreach($labor as &$v_lab) {
            $gather = EbikeGather::getEbikeGather($v_lab['platformid'], $v_lab['laborid']); // 获取符合的车辆集

            $v_lab['sum'] = count($gather);

            if ($v_lab['userid']) {
                $user  = UserSvc::ins()->getUserById($v_lab['userid']); // 获取用户的基本信息

                $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($v_lab['userid']); // 获取用户的详细信息
            }

            $v_lab['username'] = $user['name'] ? $user['name'] : "";
            $v_lab['mobileno'] = $userinfo ? $userinfo['mobileno'] : 0;
        }

        return $labor;
     }
}

/*
|---------------------------------------------------
| 组合查询 Combine Search Action
|---------------------------------------------------
|
| 这个Action主要负责车辆管理的组合查询，这里分了
| 员工和公司的组合查询两种情况，根据不同的
| 角色调不同的方法，数据大了后期优化
| 可重写Action.
| 
*/
class Action_combine_search extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $data   = $request->data;

        $user   = UserSvc::ins()->getUserById($userid);
        if (!$user) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        }

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid);
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $addon = $this->queryCondition($data, $uclink['companyid']);

        if ($user['usertype'] == User::USERTYPE_EMPLOYEE) {
            $ebike = $this->queryFromEmp($userid, $data, $addon);            
        } else {
            $ebike = $this->queryFromCompany($uclink['companyid'], $data, $addon);
        }

        foreach($ebike as $k => &$v) {
            if (is_array($v)) {
                $userinfo    = UserInfoSvc::ins()->getUserInfoByEbikeId($v['id']);
                $v['knight'] = "";
                if ($userinfo) {
                    $user = UserSvc::ins()->getUserById($userinfo['userid']);

                    $v['knight'] = $user['name'] ? $user['name'] : $userinfo['mobileno'];
                }

                $plat      = CompanySvc::ins()->getCompanyById($v['companyid']);
                $v['name'] = $plat['name'];

                if ($v['laborid']) {
                    $company = CompanySvc::ins()->getCompanyById($v['laborid']);
                }

                $sensorid = LinkSvc::ins()->getSensorIdByEbikeId($v['id']);
                if ($sensorid) {
                    $sensor = SensorSvc::ins()->getSensorById($sensorid);
                } 

                $v['imei']  = $sensorid ? $sensor['imei'] : "";
                $v['labor'] = $v['laborid']? $company['name'] : "";
                $v['owner'] = $uclink['companyid'] == $v['companyid'] ? 1 : 0;
                $v['knightid'] = $userinfo ? $userinfo['userid'] : 0;
            }
        }

        echo ResultSet::jsuccess($ebike);
        return XNext::nothing();
    }

    public function queryCondition($data, $companyid) 
    {
        $distribute = $data['distribute'];
        if ($distribute) {
            $addon[] = " cblink.distribute = $distribute";
        }

        $allot = $data['allot'];
        if ($allot) {
            $addon[] = " ebike.allot = $allot";
        }

        $exception  = $data['exception'];
        if ($exception && $exception !=4) {
            $addon[] = " ebike.exception = $exception";
        } 

        if ($exception == 4) {
            $addon[] = " ebike.exception > 0";
        }

        $seqno      = $data['seqno'];
        if ($seqno) {
            $addon[] = " ebike.seqno = $seqno";
        }

        $belong     = $data['belong'];
        if ($belong == 1) {
            $addon[] = " cblink.companyid = $companyid";
        } 

        if ($belong == 2) { 
            $addon[] = " cblink.useid = $companyid";
        }

        $laborid = $data['laborid'];
        if ($laborid) {
            $addon[] = " (cblink.useid = $laborid or cblink.companyid = $laborid)";  
        }

        $status = $data['status'];
        if ($status) {
            $addon[] = " ebike.status = $status";
        }

        return $addon;
    }

    public function queryFromCompany($companyid, $data, $addon=array())
    {
        $where = " and cblink.companyid=$companyid or cblink.useid=$companyid";
        if (count($addon) > 0) {
            $where = " and (cblink.companyid=$companyid or cblink.useid=$companyid) and " . implode(" and ", $addon); 
        }

        $total  = XDao::query("EbikeQuery")->countEbike($where);

        $result = $this->handlePage($where, $data, $total);

        $ebike  = XDao::query("EbikeQuery")->combineSearch($where, $result['limit']);
        if (!$ebike) {
            echo ResultSet::jfail(404, "Ebike Not Found");
            return XNext::nothing();
        }

        $ebike['pageall'] = $result['pageAll'];
        $ebike['total']   = $total['sum'];

        return $ebike;
    }

    public function handlePage($where, $data, $total=null)
    {
        $page    = $data['page'] ? intval($data['page']) : 10; // 每页显示的数据条数，默认显示10条

        $pageAll = $total ? ceil($total['sum']/$page) : 0; // 计算总页数

        $num     = $data['num'] ? ((intval($data['num'])-1)*$page) : 0; // 当前页

        $limit   = " limit $num, $page"; // 设置分页

        return array(
            "limit"   => $limit,
            "pageAll" => $pageAll
        );
    }

    public function queryFromEmp($userid, $data, $addon=array())
    {
        $ublink  = UBlinkSvc::ins()->showAllEbikeByUserId($userid);
        $segment = "";
        if ($ublink) {
            $ublink  = Entity::convertToArray($ublink);
            $ebikeid = array_column($ublink, "ebikeid");
            $ebikeid = implode(",", $ebikeid);
            $segment = " or ebike.id in ($ebikeid)";
        }

        $where = " and cllink.userid=$userid" . $segment;
        if (count($addon) > 0) {
            $where = " and (cllink.userid=$userid" . $segment . ") and" . implode(" and ", $addon); 
        }

        $total  = XDao::query("EbikeQuery")->countRows($where);

        $result = $this->handlePage($where, $data, $total);

        $ebike  = XDao::query("EbikeQuery")->conditionSelect($where, $result['limit']);
        if (!$ebike) {
            echo ResultSet::jfail(404, "Ebike Not Found");
            return XNext::nothing();
        }

        $ebike['pageall'] = $result['pageAll'];
        $ebike['total']   = $total['sum'];

        return $ebike;
    }
}

/*
|---------------------------------------------------
| 获取异常车辆信息 Get Exception Ebike Action
|---------------------------------------------------
|
| 这个Action主要负责查询异常车辆信息，这里分了员
| 工和公司的查询两种情况，根据执行不用请求
| 调用不同的方法获取数据。
|
*/
class Action_get_exception_ebike extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $user   = UserSvc::ins()->getUserById($userid); // 获取当前账号的基本信息
        if (!$user) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        }         

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid);

        if ($user['usertype'] == User::USERTYPE_EMPLOYEE) {
            $gather = $this->getEmpEbike($userid);  // 用户请求为员工
        } else {
            $gather = $this->getCompanyEbike($company['companyid']); // 用户请求为公司
        } 

        $data = EbikeGather::getExcepStat($gather); // 获取异常的统计数据

        $factory = new StatFactory(); // 统计历史异常数据
        $exp     = $factory->getExpStat($gather);

        $data = array_merge($data, $exp);

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }

     public function getEmpEbike($userid)
    {
        $ublink = UBlinkSvc::ins()->showAllEbikeByUserId($userid);
        $oco    = $ublink ? Entity::convertToArray($ublink) : array();

        $cllink = CLlinkSvc::ins()->getLaborByUserId($userid);
        $cnt    = array();
        if ($cllink) {
            foreach ($cllink as $v_cl) {
                $gather[] = EbikeGather::getEbikeGather($v_cl['platformid'], $v_cl['laborid']); // 获取符合的车辆集
            }

            for($i = 0; $i <= count($gather)-1; $i++) {
                $cnt = array_merge($cnt, $gather[$i]);
            }
        }

        $colect = array();
        if ($oco || $cnt) {
            $colect = array_merge($oco, $cnt);
        }

        return $colect;
    }

    public function getCompanyEbike($companyid)
    {
        $owner      = CBlinkSvc::ins()->getCBlink($companyid);
        $own        = $owner ? Entity::convertToArray($owner) : array();

        $additional = CBlinkSvc::ins()->getCBlinkByUseId($companyid);
        $add        = $additional ? Entity::convertToArray($additional) : array();

        $gather = array();
        if ($own || $add) {
            $gather = array_merge($own, $add);
        }

        return $gather;
    }
}

/*
|----------------------------------------------------------
| 获取平台下的劳务方异常车辆 Get Expebike From Labor Action
|----------------------------------------------------------
|
| 这个Action主要负责获取平台下的劳务方异常车辆，这里是查
| 询单个劳务方的信息，后期扩展重写Action。
|
*/
class Action_get_expebike_from_labor extends XAction
{
    public function _run($request, $xcontext)
    {

        $userid  = $request->userid;
        $laborid = $request->laborid;

        $company = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号对应公司的信息
        if (!$company) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $cblink = CBlinkSvc::ins()->getCBlinkByLab($company['companyid'], $laborid); // 获取请求劳务方车辆
        if (!$cblink) {
            echo ResultSet::jfail(404, "Ebike Not Found");
            return XNext::nothing();
        }

        $cblink = Entity::convertToArray($cblink);
    
        $data   = EbikeGather::getExcepStat($cblink); // 获取异常的统计数据

        $factory = new StatFactory(); // 统计历史异常数据
        $exp     = $factory->getExpStat($cblink);

        $data = array_merge($data, $exp);

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

/*
|----------------------------------------------------------
| 获取公司的信息 Get CompanyInfo Action
|----------------------------------------------------------
|
| 这个Action主要负责获取企业的信息，后期添加新的内容
| 可添加字段。
|
*/
class Action_get_companyinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $uclink = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号关联的公司id
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $company  = CompanySvc::ins()->getCompanyById($uclink['companyid']); // 根据公司id获取公司的基本信息

        $city     = CityCardSvc::ins()->getCityByNumber($company['site']); // 将区位码转化成省市
        $province = CityCardSvc::ins()->getCityByNumber($city['parent']);

        $company = $company->toArray();
        $company['province'] = $province;
        $company['city']     = $city;

        echo ResultSet::jsuccess($company);
        return XNext::nothing();
    }
}

/*
|----------------------------------------------------------
| 获取账号信息 Get AccountInfo Action
|----------------------------------------------------------
|
| 这个Action主要负责获取账号的基本信息，后期添加新的内容
| 可添加字段。
|
*/
class Action_get_accountinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;

        $user   = UserSvc::ins()->getUserById($userid); // 获取账号的基本信息
        if (!$user) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        }

        $userinfo = UserInfoSvc::ins()->getUserInfoByUserId($userid); // 获取用户详细信息

        $uclink   = UClinkSvc::ins()->getCompanyIdByUserId($userid); // 获取账号关联的公司id
        if (!$uclink) {
            echo ResultSet::jfail(404, "Company Not Found");
            return XNext::nothing();
        }

        $company = CompanySvc::ins()->getCompanyById($uclink['companyid']); // 根据公司id获取公司的信息
        
        $data['mobileno'] = $userinfo['mobileno'];
        $data['name']     = $user['name'];
        $data['email']    = $userinfo['email'];
        $data['company']  = $company['name'];

        echo ResultSet::jsuccess($data);
        return XNext::nothing();
    }
}

