<?php

abstract class XAdminPossibleAction extends XAction      
{                                                       
}                                                       

abstract class XPostAdminAction extends XAdminPossibleAction
{

    public function _before($request, $xcontext)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $msg = "HTTP METHOD is not suported for this request";
            echo ResultSet::jfail(405 , $msg);
            return false;
        }

        return parent::_before($request, $xcontext);
    }
}

abstract class XAdminAction extends XAdminPossibleAction
{
    public function _before($request, $xcontext)
    {
        $adminname = $_COOKIE['adminname'];
        $adminid   = $_COOKIE['adminid'];

        if($adminname && $adminid){
            return true;      
        }
       
        header("Location: /login.php" );
        return false;
    }
}

abstract class XAdminLoginAction extends XAdminPossibleAction
{
    public function _before($request, $xcontext)
    {
        $adminname = $_COOKIE['adminname'];
        $adminid   = $_COOKIE['adminid'];

        if($adminname && $adminid){
            header("Location: /users_check.php");
            return XNext::nothing();      
        }
       
        return XNext::nothing();
    }
}
