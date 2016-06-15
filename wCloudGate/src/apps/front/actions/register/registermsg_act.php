<?php

/**
 * @brief   注册后填写基本信息 等待邀请或认证
 */
class Action_registerdetailafter extends XLoginAction
{
    public function _run($request,$xcontext)
    {        
        $xcontext->phone = $xcontext->phone;
        $xcontext->id = $userid;

        if($xcontext->status == 2 || $xcontext->status == 5) {
            return XNext::useTpl('/register/register_wait.html');
        } else {
            $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainUrl);
        }
    
    }
}

/**
 * @brief   注册后认证 审核中
 */
class Action_registeraudit extends XAction
{
    public function _run($request,$xcontext)
    {
        //$xcontext->id = $userid;
        //if($xcontext->status == 4) {
            return XNext::useTpl('/register/register_audit.html');
        //} else {
        //    $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
        //    return XNext::gotoUrl($mainUrl);
        //}
        
    }
}

/**
 * @brief   注册后认证 失败
 */
class Action_registerfailure extends XAction
{
    public function _run($request,$xcontext)
    {
        //$xcontext->id = $userid;
        //if($xcontext->status == 5) {
            return XNext::useTpl('/register/register_failure.html');
        //} else {
        //    $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
        //    return XNext::gotoUrl($mainUrl);
        //}

    }
}

/**
 * @brief   注册后认证 成功
 */
class Action_registersuccess extends XAction
{
    public function _run($request,$xcontext)
    {
        //$xcontext->id = $userid;
        //if($xcontext->status == 0) {
            return XNext::useTpl('/register/register_success.html');
        //} else {
        //    $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
        //    return XNext::gotoUrl($mainUrl);
        //}

    }
}