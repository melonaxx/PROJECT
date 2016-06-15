<?php
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");

class WCloudGateClient extends GHttpClient
{
    public function __construct($conf)
    {
        parent::__construct($conf);
        $this->setHTTP_X_FORWARDED();
    }

/******************************************AAAAAA***********************************************/    
    /**
     * @brief 后台登陆验证
     *
     * @param $name 用户名
     * @param $passwd 密码
     *
     * @return
     */
    public function adminLogin($name, $passwd)
    {
        return $this->post("/login_admin.php", array(
            "name"   => $name,
            "passwd" => $passwd
        ));
    }

    /**
     * @brief 添加员工/骑士
     *
     * @param $userid
     * @param $mobileno
     *
     * @return
     */
    public function addEmployee($userid, $mobileno)
    {
        return $this->get("/add_employee.php", array(
            "userid"   => $userid,
            "mobileno" => $mobileno
        ));
    }

	/**
     * @brief 电车激活
     *
	 * @param  $seqno   电车序列号
     * @param  $userid  用户id
     *
     * @return
	 */
	public function activeEbike($seqno, $userid)
	{
		return $this->post("/active_ebike.php", array(
			"seqno"  => $seqno,
            "userid" => $userid,
		));
	}

    /**
     * @brief 公司认证
     *
     * @param $companyid
     * @param $status
     *
     * @return
     */
    public function authCompany($companyid, $status)
    {
        return $this->post("/auth_company.php", array(
            "companyid" => $companyid,
            "status"    => $status
        ));
    }

    /**
     * @brief 调拨车辆给员工
     *        相应的员工可
     *        分配车辆给
     *        劳务方
     *
     * @param $userid
     * @param $ebikeid
     *
     * @return
     */
    public function allotEbikeToUser($userid, $ebikeid)
    {
        return $this->post("/allot_ebike_to_user.php", array(
            "userid" => $userid,
            "ebikeid" => $ebikeid
        ));
    }

/******************************************CCCCCC***********************************************/    
    /**
     * @brief 完善用户信息
     *
     * @param $userid 用户id
     * @param $name   用户名
     * @param $email  邮箱
     * @param $qq     QQ号
     * @param $wechat 微信
     *
     * @return
     */
    public function completeuserinfo($userid, $name, $email, $qq, $wechat)
    {
        return $this->post("/complete_userinfo.php", array(
            "userid" => $userid,
            "name"   => $name,
            "email"  => $email,
            "qq"     => $qq,
            "wechat" => $wechat
        ));
    } 

    /**
     * @brief 公司信息存库
     *        做认证显示
     *
     * @param $city        城市名称
     * @param $name        公司名称
     * @param $mobileno    手机号
     * @param $email       邮箱
     * @param $registerid  公司注册号
     * @param $licence     公司营业执照 
     * @param $companytype 企业类型 
     * @param $userid      用户id
     *
     * @return
     */
    public function storeCompanyInfo($city, $name, $linkman, $mobileno, $email, $registerid, $licence, $companytype, $userid)
    {
        return $this->post("/store_companyinfo.php", array(
            "city"        => $city,
            "name"        => $name,
            "linkman"     => $linkman,
            "mobileno"    => $mobileno,
            "email"       => $email,
            "registerid"  => $registerid,
            "licence"     => $licence,
            "companytype" => $companytype,
            "userid"      => $userid
        ));
    }
/******************************************DDDDDD***********************************************/    
    /**
     * @brief 密码登陆验证
     *
     * @param $mobileno  手机号
     * @param $passwd    密码
     *
     * @return
     */
    public function validatorByPasswd($mobileno, $passwd) 
    {
        return $this->post("/validator_by_passwd.php", array(
            "mobileno" => $mobileno,
            "passwd"   => $passwd
        ));
    }

    /**
     * @brief 解密用户信息
     *
     * @param $userid 用户id
     * @param $token  解密信息
     *
     * @return
     */
    public function decryptUserInfo($userid, $token)
    {
        return $this->post("/decrypt_userinfo.php", array(
            "userid" => $userid,
            "token"  => $token
        ));
    }

    /**
     * @brief 显示要分配的车辆信息
     *
     * @param $userid
     *
     * @return
     */
    public function showDistributeableEbike($userid, $role=null)
    {
        return $this->get("/show_distributable_ebike.php", array(
            "userid" => $userid,
            "role"   => $role
        )); 
    }

    /**
     * @brief 删除单个配置信息，恢复全局配置
     *
     * @return
     */
    public function removeSensorConf($sensorid)
    {
        return $this->get("/remove_sensorconf.php", array(
            "sensorid" => $sensorid
        ));
    }

    /**
     * @brief 删除单个历史信息
     *
     * @return
     */
    public function removeConfLog($id)
    {
        return $this->get("/remove_conflog.php", array(
            "id" => $id
        ));
    }

/******************************************EEEEEE***********************************************/    
    /**
     * @brief 分配电车
     *
     * @param $userid
     * @param $laborid
     * @param $seqno
     *
     * @return
     */
    public function distributeEbikeToLabor($userid, $laborid, $seqno)
    {
        return $this->post("/distribute_ebike_to_labor.php", array(
            "userid"  => $userid,
            "laborid" => $laborid,
            "seqno"   => $seqno
        ));
    }

	/**
     * @brief 获取当前用户下电车情况
     *
     * @param  [int] $userid  [当前劳务方id]
     *
	 * @return [array]        [电车情况汇总]
	 */
	public function StatEbike($userid)
	{
		return $this->post("/stat_ebike.php", array(
            "userid"   => $userid
		));
	}
     
    public function ebikeAssoc($userid, $seqno, $act)
    {
        return $this->post("/ebike_assoc.php", array(
            "userid" => $userid,
            "seqno" => $seqno,
            "act" => $act
        ));
    }

/******************************************GGGGGG***********************************************/    
    /**
     * @brief 获取所有省
     * 
     * @return
     */
    public function getprovince()
    {
        return $this->get("/get_province.php");
    }

    /**
     * @brief 根据省区号获取对应下面的市
     *
     * @param $parent 省区号
     *
     * @return
     */
    public function getcity($parent)
    {
        return $this->get("/get_city.php", array(
            "parent" => $parent
        ));
    }

    /**
     * @brief 短信登陆验证
     *
     * @param $mobileno 手机号
     * @param $note     短信验证码
     *
     * @return
     */
    public function validatorByNote($mobileno, $note, $cookie)
    {
        return $this->post("/validator_by_note.php", array(
            "mobileno" => $mobileno,
            "note"     => $note,
            "cookie"   => $cookie
        ));
    }

    /**
     * @brief 获取企业信息
     *
     * @param $userid
     *
     * @return
     */
    public function getCompanyInfo($userid)
    {
        return $this->get("/get_companyinfo.php", array(
            "userid" => $userid
        ));
    }

    /**
     * @brief 获取账号信息
     *
     * @param $userid
     *
     * @return
     */
    public function getAccountInfo($userid)
    {
        return $this->get("/get_accountinfo.php", array(
            "userid" => $userid
        ));
    }

    /**
     * @brief 获取异常车辆信息
     *
     * @param $userid
     *
     * @return
     */
    public function getExceptionEbike($userid)
    {
       return  $this->get("/get_exception_ebike.php", array(
            "userid" => $userid
        ));
    }

    /**
     * @brief 获取企业异常车辆信息
     *
     * @param $userid
     * @param $laborid
     *
     * @return
     */
    public function getExpEbikeFromLabor($userid, $laborid)
    {
        return $this->get("/get_expebike_from_labor.php", array(
            "userid"  => $userid,
            "laborid" => $laborid
        ));
    }

    /**
     * @brief 获取所有传感器配置
     *
     * @return
     */
    public function getSensorConf()
    {
        return $this->get("/get_sensorconf.php");
    }

    /**
     * @brief 获取单个传感器配置
     *
     * @return
     */
    public function getSingleSensorConf($sensorid)
    {
        return $this->get("/get_singlesensorconf.php", array(
            "sensorid" => $sensorid
        ));
    }

    /**
     * @brief 获取传感器更改配置日志
     *
     * @return
     */
    public function getConfLog()
    {
        return $this->get("/get_conflog.php");
    }

/******************************************LLLLLL***********************************************/    
    /**
     * @brief 获取劳务方信息
     *
     * @param $userid  用户id
     * @param $sort
     *
     * @return
     */
    public function showLaborInfo($userid, $sort=null)
    {
        return $this->get("/show_labor_info.php", array(
            "userid" => $userid,
            "sort"   => $sort
        ));
    }

    public function normalAddEbike($userid, $data)
    {
        return $this->post("/normal_add_ebike.php", array(
            "data" => $data,
            "userid" => $userid
        ));
    }

/******************************************PPPPPP***********************************************/    
    /**
     * @brief 显示对应电车的运动轨迹
     *
     * @param $seqno
     *
     * @return
     */
    public function pathShow($seqno)
    {
        return $this->post("/path_show.php", array(
            "seqno" => $seqno
        ));
    }
 
/******************************************RRRRRR***********************************************/    
	/**
     * @brief 用户注册
     *
     * @param $mobileno
     * @param $passwd
     * @param $note
     * @param $cookie
     *
     * @return 
	 */
	public function registerUser($mobileno, $passwd, $note, $cookie)
	{
		return $this->post("/register_user.php", array(
			"mobileno"  => $mobileno,
            "passwd"    => $passwd,
            "note"      => $note,
            "cookie"    => $cookie
		));
	}

    public function removeCallInfo($id)
    {
        return $this->post("/remove_callinfo.php", array(
            "id" => $id
        ));   
    }
/******************************************SSSSSS***********************************************/    
    /**
     * @brief 根据名称搜素平台信息
     *
     * @param $userid
     * @param $name
     *
     * @return
     */
    public function searchPlatformByName($userid, $name)
    {
        return $this->get("/search_platform_by_name.php", array(
            "userid" => $userid,
            "name"   => $name
        ));  
    }

    /**
     * @brief 组合搜索账号下的电车信息
     *
     * @param $userid
     * @param $data
     *
     * @return
     */
    public function searchEbike($userid, $data)
    {
        return $this->get("/search_ebike.php", array(
            "userid" => $userid,
            "data"   => $data
        ));
    }

    /**
     * @brief 根据企业名称搜搜劳务方
     *
     * @param $name
     * @param $userid
     *
     * @return
     */
    public function searchLaborByName($name, $userid)
    {
        return $this->get("/search_labor_by_name.php", array(
            "name"   => $name,
            "userid" => $userid
        ));
    }

    /**
     * @brief 按条件搜索劳务方信息
     *
     * @param $userid
     * @param $name
     *
     * @return
     */
    public function searchLabor($userid, $act, $name=null)
    {
        return $this->get("/search_labor.php", array(
            "userid" => $userid,
            "name"   => $name,
            "act"    => $act
        ));
    }

	/**
     * @brief 显示对应车的动态信息
     *
     * @param  [varchar] $seqno  [电车序列号]
     *
	 * @return [type]         [description]
	 */
	public function showStatus($seqno)
	{
		return $this->post("/status_show.php", array(
			"seqno" => $seqno
		));
	}

    /**
     * @brief 显示注册公司
     *
     * @param $companytype
     *
     * @return
     */
    public function showCompany($companytype)
    {
        return $this->get("/show_company.php", array(
            "companytype" => $companytype
        ));
    }

    /**
     * @brief 统计当前登陆账号合作公司的车辆信息
     *
     * @param $userid
     * @param $companyid
     *
     * @return
     */
    public function statCompanyEbikeinfo($userid, $companyid)
    {
        return $this->get("/stat_company_ebikeinfo.php", array(
            "userid"    => $userid,
            "companyid" => $companyid
        ));
    }

    /**
     * @brief 根据请求显示相应的公司
     *
     * @param $status
     *
     * @return
     */
    public function showCompanyByStatus($status)
    {
        return $this->get("/show_company_by_status.php", array(
            "status" => $status
        ));
    }
    
    /**
     * @brief 显示不同类型的用户/平台信息
     *
     * @param $usertype
     *
     * @return
     */
    public function showUserList($usertype)
    {
        return $this->get("/show_user_list.php", array(
            "usertype" => $usertype
        ));
    }

    /**
     * @brief 存储传感器imei号
     *
     * @param $imei
     *
     * @return array[success:imei, fail:imei]
     */
    public function storeSensorIMEI($imei)
    {
        return $this->get("/store_sensorimei.php", array(
            "imei" => $imei
        ));
    }

    /**
     * @brief 获取所有传感器信息
     *
     * @return
     */
    public function showSensorInfo()
    {
        return $this->get("/show_sensorinfo.php");
    }

    /**
     * @brief 存储电动车的序列号
     *
     * @param $seqno
     * @param $mobel
     *
     * @return array[success:seqno, fail:seqno]
     */
    public function storeEbikeSeqno($seqno, $mobel)
    {
        return $this->get("/store_ebikeseqno.php", array(
            "seqno" => $seqno,
            "mobel" => $mobel
        ));
    }

    /**
     * @brief 存储传感器车辆绑定
     *
     * @param $seqno
     * @param $sensorid
     *
     * @return
     */
    public function storeBSlink($seqno, $sensorid)
    {
        return $this->get("/store_bslink.php", array(
            "seqno"  => $seqno,
            "sensorid" => $sensorid
        ));
    }

    public function showEbikeInfoFromEmp($userid)
    {
        return $this->get("/show_ebikeinfo_from_emp.php", array(
            "userid" => $userid
        ));
    }

    public function storeCallInfo($content)
    {
        return $this->post("/store_callinfo.php", array(
            "content" => $content
        ));
    }

    public function showCallInfoList()
    {
        return $this->get("/show_callinfo_list.php");
    }

    public function showCallInfo()
    {
        return $this->get("/show_callinfo.php");
    }

    private function setHTTP_X_FORWARDED()
    {
        $this->setHeaders("X-Forwarded-For", $_SERVER['REMOTE_ADDR']);
    }

    public function setDisplayCallInfo($sid, $hid=0)
    {
        return $this->post("/set_display_callinfo.php", array(
            "sid" => $sid,
            "hid" => $hid
        ));
    }

    public function showLaborInfoFromEmp($userid, $data)
    {
        return $this->get("/show_labor_info_from_emp.php", array(
            "userid" => $userid,
            "data" => $data
        ));
    }

/******************************************UUUUUU***********************************************/    
    /**
     * @brief 修改用户信息
     *
     * @param $userid
     * @param $data
     *
     * @return
     */
    public function updateUserInfo($userid, $data)
    {
        return $this->post("/update_userinfo.php", array(
            "userid" => $userid,
            "data"   => $data
        ));
    }

    /**
     * @brief 修改密码
     *
     * @param $userid  用户id
     * @param $passwd  修改后的密码
     *
     * @return 
     */
    public function updatePasswd($userid, $passwd)
    {
        return $this->post("/update_passwd.php", array(
            "userid" => $userid,
            "passwd" => $passwd
        ));
    }

    /**
     * @brief 修改配置
     *
     * @param $data 要修改的参数
     *
     * @return
     */
    public function updateSensorConf($data)
    {
        return $this->get("/update_sensorconf.php", array(
            "data" => $data
        ));
    }

    /**
     * @brief 修改员工权限
     *
     * @param $userid
     * @param $auth
     *
     * @return
     */
    public function updatePermission($userid, $auth)
    {
        return $this->post("/update_permission.php", array(
            "userid" => $userid,
            "auth" => $auth
        ));
    }

    public function updateRelateSensor($imei, $ebikeid)
    {
        return $this->post("/update_relate_sensor.php", array(
            "imei" => $imei,
            "ebikeid" => $ebikeid
        ));
    }
 
    public function updateAdminUserInfo($userid, $name, $passwd)
    {
        return $this->post("/update_adminuserinfo.php", array(
            "userid" => $userid,
            "name"   => $name,
            "passwd" => $passwd            
        ));
    }
}

