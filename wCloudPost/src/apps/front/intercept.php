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

