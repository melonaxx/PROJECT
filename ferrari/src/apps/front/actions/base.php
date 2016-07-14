<?php

/**
 * @brief  尝试进行身份验证和权限验证的action基类
 */
abstract class XAuthPossibleAction extends XAction
{
    /**
     * @brief 检查是否是POST方法
     */
    const POST = 1;
    /**
     * @brief 检查是否已经登录
     */
    const LOGIN = 2;
    /**
     * @brief 检查是否需要修改密码
     */
    const PWD = 4;
    /**
     * @brief 检查是否通过权限认证
     */
    const PERMISSION = 8;

    /**
     * @brief 异常时返回错误代码
     */
    const ERRNO = 1;
    /**
     * @brief 异常时跳转页面
     */
    const PAGE = 2;

    public function _before($request, $xcontext)
    {
        $checkPoint = $this->getCheckPoint();
        $handlerType = $this->getHandlerType();

        // 1. 查看是否需要限制是POST方法
        if (($checkPoint & self::POST) > 0) {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                echo ResultSet::jfail(405, "HTTP METHOD is not suported for this request");
                return false;
            }
        }

        // 2. 查看用户是否已经登录
        if (($checkPoint & self::LOGIN) > 0) {
            if (!$xcontext->userid) {
                if ($handlerType == self::ERRNO) {
                    echo ResultSet::jfail(4011, "You need to login first");
                } else {
                    $loginUrl = "http://" . $_SERVER['DOMAIN'] . "/login.php";

                    $errtype = $xcontext->auth_errtype;
                    if ($errtype > 0) {
                        $loginUrl .= "?errtype=$errtype";
                    }

                    header("Location: " . $loginUrl);
                }

                return false;
            }
        }

        // 3. 检查用户是否需要更改密码
        if (($checkPoint & self::PWD) > 0) {
            if ($xcontext->userinfo['status'] == User::STATUS_FIRST_LOGIN) {
                if ($handlerType == self::ERRNO) {
                    echo ResultSet::jfail(4012, "You need to modify your pwd");
                } else {
                    $setpwdUrl = "http://" . $_SERVER['DOMAIN'] . "/user/setpwd.php";
                    header("Location: " . $setpwdUrl);
                }

                return false;
            }
        }

        // 4. 检查权限
        if (($checkPoint & self::PERMISSION) > 0) {
            $permission = $this->getPermission();
            $cur_permission = $xcontext->permission;
            if ($permission) {
                if (!$cur_permission || !$cur_permission->allow($permission)) {
                    echo ResultSet::jfail(4031, "You have no permission");
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @brief 当前Action所需的权限。需要权限控制的Action必须重写本方法
     *
     * @return Permission类的一个示例，或者null。返回null时表示无权限限制
     */
    public function getPermission()
    {
        return null;
    }

    /**
     * @brief 需要检查哪些条件
     *
     * @return
     */
    public function getCheckPoint()
    {
        return 0;
    }

    /**
     * @brief 预检查不通过时，返回客户端的处理类型。默认是返回错误代码
     *
     * @return
     */
    public function getHandlerType()
    {
        return self::ERRNO;
    }
}

/**
 * @brief 需要身份验证和权限管理的Action基类，验证失败后会返回错误码
 */
abstract class XBaseAuthAction extends XAuthPossibleAction
{
    public function getCheckPoint()
    {
        return self::LOGIN | self::PERMISSION;
    }
}

/**
 * @brief 需要身份验证和权限管理，并且必须是post调用的Action基类，验证失败后会返回错误码
 */
abstract class XBasePostAuthAction extends XBaseAuthAction
{
    public function getCheckPoint()
    {
        return self::POST | parent::getCheckPoint();
    }
}

/**
 * @brief 需要身份验证和权限管理的，并且会限制第一次登录的用户的Action基类，验证失败后会返回错误码
 */
abstract class XAuthAction extends XAuthPossibleAction
{
    public function getCheckPoint()
    {
        return self::LOGIN | self::PWD | self::PERMISSION;
    }
}

/**
 * @brief 需要身份验证和权限管理的并要求是POST请求、并且会限制第一次登录的用户的Action基类，验证失败后会返回错误码
 */
abstract class XPostAuthAction extends XAuthAction
{
    public function getCheckPoint()
    {
        return self::POST | parent::getCheckPoint();
    }
}

abstract class XBaseLoginAction extends XAuthPossibleAction
{
    public function getCheckPoint()
    {
        return self::LOGIN | self::PERMISSION;
    }

    public function getHandlerType()
    {
        return self::PAGE;
    }
}

/**
 * @brief 如果身份验证失败，会跳转到登录页面的Action
 */
abstract class XLoginAction extends XBaseLoginAction
{
    public function getCheckPoint()
    {
        return self::PWD | parent::getCheckPoint();
    }
}
