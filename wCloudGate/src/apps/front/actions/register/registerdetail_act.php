<?php

/**
 * @brief   注册后填写基本信息
 */
class Action_registerdetail extends XLoginAction
{
    public function _run($request,$xcontext)
    {   
        $xcontext->phone = $xcontext->phone;
        if($xcontext->status == 1) { 
            return XNext::useTpl('register/register_created.html');
        } else {
            $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainUrl);
        }
       
    }
}

/**
 * @brief   基本信息 验证
 */
class Action_doregisterdetail extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $name = $request->name;
        $email = $request->email;
        $qq = $request->qq;
        $wechat = $request->wechat;
        if(!$name || !$email ) {
            echo ResultSet::jfail(400 , "The user information missing ");
            return XNext::nothing();
        }

        $pname = preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,}$/u", $name);
        if(!$pname){
            echo ResultSet::jfail(4001 , "The name format error ");
            return XNext::nothing();
        }       

        $pemail = preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email);
        if(strlen($email) > 21 ){
            echo ResultSet::jfail(4002 , "The email should be eleven characters ");
            return XNext::nothing();
        }
        if(!$pemail){
            echo ResultSet::jfail(4002 , "The email format error ");
            return XNext::nothing();
        }

        $kqq = preg_match("/^\s*$/", $qq);
        if(!$kqq) {
            $pqq = preg_match("/^[1-9]\d{2,18}$/", $qq);
            if(!$pqq){
                echo ResultSet::jfail(4003 , "The qq format error");
                return XNext::nothing();
            }
        }

        $kwechat = preg_match("/^\s*$/", $wechat);
        if(!$kwechat) {
            $pwechat = preg_match("/^[a-zA-Z\d_]{3,}$/", $wechat);
            if(!$pwechat){
                echo ResultSet::jfail(4004 , "The wechat format error");
                return XNext::nothing();
            }
        }
     
        $id = $xcontext->userid;

        $client = GClientAltar::getWCloudGateClient();
        $result = $client->completeuserinfo($id , $name , $email , $qq , $wechat );
        if(!$result) {
            echo ResultSet::jfail(500 , "Internal server error ");
            return XNext::nothing();
        }

        $errno = $result->errno;
        if($errno == 0) {
            echo ResultSet::jsuccess("");
            return XNext::nothing();
        }else{
             echo ResultSet::jfail(5001, "Server Error: updateUserInfo fail ");
            return XNext::nothing();
        }

        
    }
}


