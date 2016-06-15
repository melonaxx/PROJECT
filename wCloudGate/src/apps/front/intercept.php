<?php

class Authorization implements XScopeInterceptor
{
    public function _before($request,$xcontext) 
    {
        // 首先根据cookie尝试获取用户信息
        list($retcode, $user) = UserSvc::ins()->getUserInfoFromCookie();

        if ($retcode === 0) {
            // 身份验证通过
            $xcontext->auth_errtype = 0;
            $xcontext->userid = $user['id'];
            $xcontext->username = $user['name'];
            $xcontext->phone = $user['mobileno'];
            $xcontext->userinfo = $user;
            $xcontext->status = $user['status'];
            $xcontext->usertype = $user['usertype'];
            $xcontext->authority = $user['authority'];
            $xcontext->permission = new Permission($user['authority']);
        } else {
            $xcontext->auth_errtype = $retcode;
            $xcontext->userid = 0;
            $xcontext->username = "";
            $xcontext->phone = "";
            $xcontext->userinfo = null;
            $xcontext->status = "";
            $xcontext->usertype = "";
            $xcontext->permission = "";
        }
    }

    public function _after($request,$xcontext)
    {
    }
}

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
        $xcontext->username = $xcontext->username;        
        if($xcontext->usertype == 0) {
            $xcontext->employee = false;
            $state = $xcontext->authority;
            $ste = explode("." , $state);
            if($ste[0] > 0) {
                $xcontext->employee = true;
            }
            $operation = EmployeeOperation::operation($ste[2] , $ste[1] , $ste[0]);

            $xcontext->operation = $operation;
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


