<?php

/**
 * @brief   注册 类型
 */
class Action_registertype extends XLoginAction
{
    public function _run($request,$xcontext)
    {        
        $xcontext->phone = $xcontext->phone;

        $client = GClientAltar::getWCloudGateClient();
        $result = $client->getprovince();
        $data = $result->data;
        for($i = 0; $i < count($data); $i++) {
            if($data[$i]['number'] == 990000) 
                break;
            $province[$data[$i]['number']] = $data[$i]['name'];
        }

        $xcontext->province = $province;

        if($xcontext->status == 2) { 
            return XNext::useTpl('/register/register_role.html');
        } else {
            $mainUrl = "http://" . $_SERVER['DOMAIN'] . "/main.php";
            return XNext::gotoUrl($mainUrl);
        }
        
    }
}

/**
 * @brief   二级城市
 */
class Action_registerselected extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $number = $request->city;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->getcity($number);
        $data = $result->data;
        for($i = 0; $i < count($data); $i++) {
            if($data[$i]['number'] == 990000) 
                break;
            $province[$data[$i]['number']] = $data[$i]['name'];
        }
        echo ResultSet::jsuccess($province);
        return XNext::nothing();
    }
}

/**
 * @brief   注册 类型验证
 */
class Action_doregistertype extends XPostAuthAction
{
    public function _run($request,$xcontext)
    {
        $city = $request->city;
        $name = $request->name;
        $linkman = $request->linkman;
        $mobileno = $request->phone;
        $email = $request->email;
        $imgname = $request->imgname;
        $licence = $request->licence;
        $companytype = $request->type;

        if(!$city || !$name || !$linkman  || !$mobileno  || !$imgname || !$licence ) {
            echo ResultSet::jfail(400 , "The authenticate information missing ");
            return XNext::nothing();
        }

        $city = $request->city;
        $pcity = preg_match("/^\s*$/", $city);
        if($pcity){
            echo ResultSet::jfail(4001 , "The city not found ");
            return XNext::nothing();
        } 
      
        $name = $request->name;
        $pname = preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{3,}$/u", $name);
        if(!$pname){
            echo ResultSet::jfail(4002 , "The name format error ");
            return XNext::nothing();
        } 

        $linkman = $request->linkman;
        $plinkman = preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,}$/u", $linkman);
        if(!$plinkman){
            echo ResultSet::jfail(4003 , "The linkname format error ");
            return XNext::nothing();
        } 

        $pmobileno = preg_match("/^1\d{10}$/", $mobileno);
        if(!$pmobileno){
            echo ResultSet::jfail(4004 , "The phone number is eleven ");
            return XNext::nothing();
        } 

        if($email) {
            $pemail = preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email);
            if(!$pemail){
                echo ResultSet::jfail(4005 , "The email format error ");
                return XNext::nothing();
            } 
        }               

        $pcompanytype = preg_match("/^[1-3]{1}$/", $companytype);
        if(!$pcompanytype){
            echo ResultSet::jfail(4006 , "The company type not found ");
            return XNext::nothing();
        }         

        $plicence = preg_match("/^[^\s]{2,30}$/", $licence);
        if(!$plicence){
            echo ResultSet::jfail(4007 , "The business license number is fifteen ");
            return XNext::nothing();
        } 

        // 营业执照图片
        $licencephoto = $imgname;

        $id = $xcontext->userid;
        $client = GClientAltar::getWCloudGateClient();
        $result = $client->storeCompanyInfo($city , $name , $linkman , $mobileno , $email , $licence , $licencephoto , $companytype , $id );
        if(!$result) {
            echo ResultSet::jfail(500 , "Internal server error ");
            return XNext::nothing();
        }

        $errno = $result->errno;
        if($errno == 0) {
            echo ResultSet::jsuccess("");
            return XNext::nothing();
        }else{
            echo ResultSet::jfail(5001 , "Registration failed ");
            return XNext::nothing();
        }        

    }
}