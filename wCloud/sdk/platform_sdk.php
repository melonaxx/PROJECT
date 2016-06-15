<?php
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");

class PlatformClient extends GHttpClient
{
/******************************************AAAAAA***********************************************/    
    /**
     * @brief 添加劳务方
     *
     * @param $platiformid 平台id
     * @param $userid  用户id
     * @param $laborid 要添加的劳务方id
     *
     * @return
     */
    public function addLabor($platformid, $userid, $laborid)
    {
        return $this->get("/add_labor.php", array(
            "userid"     => $userid,
            "laborid"    => $laborid,
            "platformid" => $platformid
        ));
    }

/******************************************DDDDDD***********************************************/    
    /**
     * @brief 删除劳务方
     *
     * @param $userid
     * @param $laborid
     *
     * @return
     */
    public function removeLabor($userid, $laborifd)
    {
        return $this->get("/remove_labor.php", array(
            "userid"  => $userid,
            "laborid" => $laborifd
        ));
    }

    /**
     * @brief 分配劳务方
     *
     * @param $platid 
     * @param $data
     * @param $userid
     *
     * @return
     */
    public function distributeLaborToEmployee($platid, $data, $userid)
    {
        return $this->post("/distribute_labor_to_employee.php", array(
            "userid" => $platid,
            "labor"  => $data,
            "empid"  => $userid
        ));
    }

    /**
     * @brief 删除员工
     *
     * @param $userid
     * @param $employeeid
     * @param $usertype
     * @param $ebikeid
     *
     * @return
     */
    public function removeEmployee($userid, $employeeid, $usertype=0, $ebikeid=0) 
    {
        return $this->get("/remove_employee.php", array(
            "userid"     => $userid,
            "employeeid" => $employeeid,
            "usertype"   => $usertype,
            "ebikeid"    => $ebikeid
        ));
    }

/******************************************GGGGGG***********************************************/    
    /**
     * @brief 根据手机号获取用户信息
     *
     * @param $mobileno
     *
     * @return
     */
    public function searchEmployeeByMobileno($mobileno)
    {
        return $this->get("/search_employee_by_mobileno.php", array(
            "mobileno" => $mobileno
        ));
    }

    /**
     * @brief 获取平台员工
     *
     * @param $userid
     *
     * @return
     */
    public function getemployee($userid, $data=null)
    {
        return $this->get("/get_employee.php", array(
            "userid" => $userid,
            "data" => $data
        ));
    }

    public function getEmployeeList($userid)
    {
        return $this->get("/get_employee_list.php", array(
            "userid" => $userid
        ));
    }

/******************************************SSSSSS***********************************************/    
    /**
     * @brief 根据名称获取员工信息
     *
     * @param $userid
     * @param $data
     * @param $usertype
     *
     * @return
     */
    public function searchEmployeeByName($userid, $usertype, $data)
    {
        return $this->get("/search_employee_by_name.php", array(
            "userid"   => $userid,
            "data"     => $data,
            "usertype" => $usertype
        ));

    }

    /**
     * @brief 获取所合作的劳务方信息
     *
     * @param $userid 当前登陆账号
     *
     * @return
     */
    public function showLaborInfo($userid, $data, $sort=null)
    {
        return $this->get("/show_labor_info.php", array(
            "userid" => $userid,
            "data"   => $data,
            "sort"   => $sort
        ));
    } 

    /**
     * @brief 显示未被分配的劳务方信息
     *
     * @param $userid
     *
     * @return
     */
    public function showDistributableLabor($userid)
    {
        return $this->post("/show_distributable_labor.php", array(
            "userid" => $userid
        ));
    }

    /**
     * @brief 统计员工下的车辆信息
     *
     * @param $userid
     *
     * @return
     */
    public function statEmployeeEbikeInfo($userid)
    {
        return $this->get("/stat_employee_ebikeinfo.php", array(
            "userid" => $userid
        ));
    }

    /**
     * @brief 显示周围同企业的骑士
     *
     * @param $userid
     *
     * @return
     */
    public function showAroundKnightinfo($userid)
    {
        return $this->get("/show_around_knightinfo.php", array(
            "userid" => $userid
        ));
    }

    public function showEbikeInfoFromLabor($userid, $laborid)
    {
        return $this->get("/show_ebikeinfo_from_labor.php", array(
            "userid" => $userid,
            "laborid" => $laborid
        ));
    }

    public function showLaborList($userid, $sort)
    {
        return $this->get("/show_labor_list.php", array(
            "userid" => $userid,
            "sort" => $sort
        ));
    }

/******************************************UUUUUU***********************************************/    
    /**
     * @brief 更换员工管理劳务方
     *
     * @param $platid  当前登陆的id
     * @param $userid  员工id
     * @param $laborid 劳务方id
     *
     * @return
     */
    public function updateEmployeeForLabor($platid, $userid, $laborid)
    {
        return $this->get("/update_employee_for_labor.php", array(
            "userid"  => $userid,
            "laborid" => $laborid,
            "platid"  => $platid
        ));
    }

    /**
     * @brief 解除分配
     *
     * @param $seqno
     * @param $userid
     *
     * @return
     */
    public function cancleDistributeEbike($seqno, $userid, $act=null) 
    {
        return $this->get("/cancle_distribute_ebike.php", array(
            "seqno"   => $seqno,
            "userid"  => $userid,
            "act"     => $act
        ));
    }

    /**
     * @brief 解除分配劳务方
     *
     * @param $userid
     * @param $laborid
     *
     * @return
     */
    public function cancleDistributeLabor($userid, $laborid)
    {
        return $this->get("/cancle_distribute_labor.php", array(
            "userid"  => $userid,
            "laborid" => $laborid
        ));
    }
}
