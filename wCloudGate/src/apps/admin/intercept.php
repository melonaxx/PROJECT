<?php
class AdminNavView implements XScopeInterceptor
{
    static function viewProc($tpl,$vars,$request,$xcontext)
    {

        $tpld =  $_SERVER['TPL_ADM_DIR'] ;
        $xcontext->tpld=$tpld;
        if($tpl == "AUTO")
        {
            $xcontext->_view= $tpld. "/".  $xcontext->_action  . ".html";
        }
        else
        {
            $xcontext->_view= $tpld."/".$tpl;
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


class AdminErrorPoc extends OneIns implements XErrorInterceptor
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

class AdminLogin implements XScopeInterceptor
{
    static function viewProc($tpl,$vars,$request,$xcontext)
    {

        $tpld =  $_SERVER['TPL_ADM_DIR'] ;
        $xcontext->tpld=$tpld;
        if($tpl == "AUTO")
        {
            $xcontext->_view= $tpld. "/".  $xcontext->_action  . ".html";
        }
        else
        {
            $xcontext->_view= $tpld."/".$tpl;
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
