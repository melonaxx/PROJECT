<?php

// index
abstract class XAuthPossibleAction extends XAction
{
}

// 需要POST方法
abstract class XPostAuthAction extends XAuthPossibleAction
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

// 劳务方下的骑士 需要POST方法 
abstract class XKnightPostAuthAction extends XPostAuthAction
{

    public function _before($request, $xcontext)
    {
        if ($xcontext->usertype != UserType::LABOR ) {
            $msg = "This request is fail";
            echo ResultSet::jfail(405 , $msg);
            return false;
        }

        return parent::_before($request, $xcontext);
    }
}

// 需要GET方法
abstract class XGetAuthAction extends XAuthPossibleAction
{

    public function _before($request, $xcontext)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $msg = "HTTP METHOD is not suported for this request";
            echo ResultSet::jfail(405 , $msg);
            return false;
        }

        return parent::_before($request, $xcontext);
    }
}

// post 数据提交请求权限控制
abstract class XPermissionAuthAction extends XPostAuthAction
{
    public function _before($request, $xcontext)
    {
        $permission = $this->getPermission();
        $cur_permission = $xcontext->permission;
        if ($permission) {
            if (!$cur_permission || !$cur_permission->allow($permission)) {
                echo ResultSet::jfail(4031, "Please boss add permissions ");
                return false;
            }
        }

        return parent::_before($request, $xcontext);
    }

    public function getPermission()
    {
        return null;
    }
}

// get 数据提交请求权限控制
abstract class XGetPermissionAuthAction extends XGetAuthAction
{
    public function _before($request, $xcontext)
    {
        $permission = $this->getPermission();
        $cur_permission = $xcontext->permission;
        if ($permission) {
            if (!$cur_permission || !$cur_permission->allow($permission)) {
                echo ResultSet::jfail(4031, "Please boss add permissions ");
                return false;
            }
        }

        return parent::_before($request, $xcontext);
    }

    public function getPermission()
    {
        return null;
    }
}

// login
abstract class XLoginAction extends XAuthPossibleAction
{
    public function _before($request, $xcontext)
    {
        // 页面跳转
        if(!$xcontext->userid) {
            $loginurl = "http://" . $_SERVER['DOMAIN'] . "/login.php";
            header("Location: " . $loginurl);
            return false;
        }

        return true;

    }
}

// 完善信息 认证
abstract class XUserinfoAction extends XLoginAction
{
    public function _before($request, $xcontext)
    {
        if(parent::_before($request , $xcontext) === false) {
            return fasle;
        }

        $status = $xcontext->status;
        if($status !== "0") {
            $url = "http://" . $_SERVER['DOMAIN'] . "/register";
            $php = ".php";
            switch ($status) {
                case '1':
                    $info = "detail";
                    break;

                case '2':
                    $info = "detailafter";
                    break;

                case '3':
                    $info = "type";
                    break;

                case '4':
                    $info = "audit";
                    break;

                case '5':
                    $info = "failure";
                    break;

                case '6':
                    $info = "success";
                    break;

                case '-2':
                    $info = "";
                    break;

                default:

                    break;
            }
            header("Location: " .$url . $info . $php );
            return false;
        }

        return true;
    }
}


// 页面加载 权限控制
abstract class XPermissionAction extends  XUserinfoAction
{
    public function _before($request, $xcontext)
    {
        if(parent::_before($request , $xcontext) === false) {
            return fasle;
        }

        $permission = $this->getPermission();
        $cur_permission = $xcontext->permission;
        if ($permission) {
            if (!$cur_permission || !$cur_permission->allow($permission)) {
                $mainurl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
                header("Location: " . $mainurl);
                return false;
            }
        }

        return true;
    }

    public function getPermission()
    {
        return null;
    }
}


