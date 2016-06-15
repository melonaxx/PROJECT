<?php
session_start();
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
        // 首先根据尝试获取用户信息 和 用户权限
        // TODO $result = UserSvc::ins()->getUserInfoFromCookie();

        $uid = $_SESSION['uid'];
        // var_dump($_SESSION);
        // exit;
        if ($uid) {
            $xcontext->userid = $_SESSION['uid'];

            $xcontext->username = $_SESSION['username'];

            //一个人有的角色
             $role=XDao::query("Etc_roleQuery")->onerole($uid);
               $permis=new Permission(""); 
               foreach($role as $k=>$v){
                     $auth=XDao::query("Etc_roleQuery")->checkauth($v['jid']);
                     // var_dump($auth);
                     $auth1=$permis->add($auth['auth'])->toString();    
                }
            $xcontext->permission = new Permission($auth1);
        } else {
            $xcontext->userid = 0;
            $xcontext->username = "";
            $xcontext->permission = null;
        }
    }

    public function _after($request,$xcontext)
    {
    }
}

