<?php

/**
 * 	@brief   后台登陆。
 */
class Action_login extends XAdminLoginAction
{
    public function _run($request,$xcontent)
    {
        return XNext::useTpl('/mage_login/login_index.html');
    }
}

/**
*  	@brief 验证管理员账号密码信息
* 	@return [bool] 登陆成功结果
*/
class Action_verify_login extends XPostAdminAction
{
	public function _run($request,$xcontent)
	{
		$name 	  = $request->name;
		$password = $request->password;

        $client = GClientAltar::getWCloudGateClient();
        $result = $client->adminLogin($name,$password);
        if($result && $result->errno === 0){
            $data = $result->data;
            setcookie('adminname',$data['name'],time()+3600);
            setcookie('adminid',$data['id'],time()+3600);
            echo ResultSet::jsuccess($result->errno);
            return XNext::nothing();
        }

        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing();
	}
}

/**
 *  @brief 后台退出
 */
class Action_loginout extends XAction
{
    public function _run($request, $xcontent)
    {
        setcookie('adminname','',time()-3600);   
        setcookie('adminid','',time()-3600);
        header("Location:/login.php");
        return XNext::nothing();
    }
}

/**
 *后台修改密码
 */
class Action_admin extends XAdminAction
{
    public function _run($request,$xcontext)
    {
        return XNext::useTpl("/mage_login/admin.html");
    }
}

class Action_doadmin extends XPostAction
{
    public function _run($request,$xcontext)
    {
        $adminid = $_COOKIE['adminid'];
        $name    = $request->name;
        $passwd  = $request->pwd1;
        
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->updateAdminUserInfo($adminid,$name,$passwd);
        
        if($result->errno === 0){
            setcookie('adminname','',time()-3600);   
            setcookie('adminid','',time()-3600);
            header("Location:/login.php");
            return XNext::nothing();    
        }   
        
        echo ResultSet::jfail($result->errno,$result->errmsg);
        return XNext::nothing(); 
    }
}
