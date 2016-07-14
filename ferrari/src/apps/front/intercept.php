<?php
class FntView implements XScopeInterceptor
{
    static function viewProc($tpl,$vars,$request,$xcontext)
    {
        $tpldirs = $_SERVER['TPL_FNT_DIR'];
        $xcontext->$tpldirs ;
        if($tpl == "AUTO")
        {
            $xcontext->_view=$tpldirs ."/" . $xcontext->_action  . ".html";
        }
        else
        {
            $xcontext->_view= $tpldirs ."/".$tpl;
        }
    }

    public function _before($request,$xcontext)
    {
        self::viewProc($xcontext->_view,$xcontext->_view_vars,$request,$xcontext);
    }

    public function _after($request,$xcontext)
    {
    }
}

class FntErrorPoc extends OneIns implements XErrorInterceptor
{
    public function _procError($e,$request,$xcontext)
    {
        if ($_SERVER['ENV'] == "dev") {
            // 开发环境下在页面显示错误信息
            $xcontext->exception = $e;
            $xcontext->errormsg = $e->getMessage();
            return XNext::useTpl("error.html");
        } else {
            return XNext::nothing();
        }
    }
}

class FntUserInputErrorPoc extends OneIns implements XErrorInterceptor
{
    public function _procError($e,$request,$xcontext)
    {
        if ($_SERVER['ENV'] == "dev") {
            // 开发环境下在页面显示错误信息
            $xcontext->exception = $e;
            $xcontext->errormsg = $e->getMessage();
            return XNext::useTpl("error.html");
        } else {
            echo "user input error";
            return XNext::nothing();
        }
    }
}


class Authorization implements XScopeInterceptor
{
    public function _before($request,$xcontext)
    {
        // 首先根据cookie尝试获取用户信息
        $result = UserSvc::ins()->getUserInfoFromCookie();
        list($retcode, $user) = $result;

        if ($retcode === 0) {
            // 身份验证通过
            $xcontext->auth_errtype = 0;
            $xcontext->userid = $user->id;
            $xcontext->username = $user->name;
            $xcontext->userinfo = $user;
            $roleid = XDao::query("userroleQuery")->findrole($user->id);
            $permiss = new Permission("");
            foreach($roleid as $v){
                $roleidid = $v['roleid'];
                $auth = XDao::query("roleQuery")->findauth($roleidid);
                $aaa=$permiss->add($auth['authority'])->toString();
            }
            $xcontext->permission=new Permission($aaa);
        } else {
            $xcontext->auth_errtype = $retcode;
            $xcontext->userid = 0;
            $xcontext->username = "";
            $xcontext->userinfo = null;
        }
    }

    public function _after($request,$xcontext)
    {
    }
}
