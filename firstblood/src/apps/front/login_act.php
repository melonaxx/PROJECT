<?php
session_start();
class Action_login extends XAction
{
    public function _run($request, $xcontext)
    {
        $errtype = intval($request->errtype);
        $xcontext->errtype = $errtype;

        return XNext::useTpl("login.html");
    }
}


class Action_dologin extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $username = $_POST['username'];
        $password = sha1(md5($_POST['pwd']));

        $list=XDao::query("Etc_userrQuery")->denglu($username,$password);

        if($list){
            $_SESSION['username']=$username;
            $_SESSION['uid']=$list['id'];

               //一个人有的角色
               $role=XDao::query("Etc_roleQuery")->onerole($list['id']);

               $permission=new Permission(""); 
               foreach($role as $k=>$v){
                     if($v['jid'] == "1"){
                        $root="root";
                     }
                     $auth=XDao::query("Etc_roleQuery")->checkauth($v['jid']);
                     $auth1=$permission->add($auth['auth'])->toString();  

                }

             $array['P_VIEW_BASIC']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_BASIC);
             
             $array['P_EDIT_BASIC']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);

             $array['P_VIEW_PAY']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAY);

             $array['P_EDIT_PAY']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);

             $array['P_VIEW_KAOQIN']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_KAOQIN);

             $array['P_VIEW_PAPER']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAPER);

             $array['P_VIEW_CAPITAL']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_CAPITAL);
             
             $array['root']=$root;
             $_SESSION['auth']=$array;
             unset($_SESSION['code']);
            header("location:index.html");
        }else{
            unset($_SESSION['code']);
            return XNext::useTpl("login.html");      
        }
    }
}

class Action_jqlog extends XPostAction
{
    public function _run($request, $xcontext)
    {

            $username = $_POST['username'];
            $password = sha1(md5($_POST['password']));
            $row=XDao::query("Etc_userrQuery")->denglu($username,$password);
            if($row){
               echo 1;
            }
    }
}
class Action_code extends XPostAction
{
    public function _run($request, $xcontext)
    {
        if($_POST['code']==$_SESSION['code']){
            echo 1;
        }
        
    }
}
class Action_loginout extends XAction
{
    public function _run($request, $xcontext)
    {
        session_unset();
        session_destroy();
        return XNext::useTpl("login.html");
    }
}
class Action_changeuser extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("adduser.html");
    }
}
//添加用户
class Action_addadmin extends XAction
{
    public function _run($request, $xcontext)
    {
        $info=$request->attr;
        $result=etc_userrSvc::ins()->addadmin($info);
        if ($result) {
            return XNext::gotourl($_SERVER['DOMAIN'].'/showadmin.php');
        }else{
            echo "添加失败";
        }
    }
    
}
//用户自己修改密码
class Action_updatepass extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("changepass.html");
    }
}
class Action_dochangepass extends XAction
{
    public function _run($request, $xcontext)
    {
         $password = $_POST['repassword'];
         $userid = $_POST['userid'];
         $result=etc_userrSvc::ins()->changepass($password,$userid);
         if($result){
            return XNext::useTpl("changesuccess.html");
         }else{
            echo "修改失败";
         }
    }
}
//验证密码是否正确
class Action_checkpass extends XAction
{
    public function _run($request, $xcontext)
    {
        $uid=$_POST['userid'];
        $password=sha1(md5($_POST['password']));
        $row=XDao::query("Etc_userrQuery")->checkpass($password,$uid);
        if($row){
          echo 1;
        }else{
          echo 0;
        }
    }
}
//角色查看
class Action_showrole extends XAction
{
    public function _run($request, $xcontext)
    {
        $list=XDao::query("Etc_roleQuery")->showrole();
        $xcontext->list=$list;
        return XNext::useTpl("showrole.html");
    }
}
//角色添加
class Action_addrole extends XAction
{
    public function _run($request, $xcontext)
    {
        $rolename=htmlspecialchars($request->rolename);
        $row=Etc_roleSvc::ins()->addrole($rolename);
        if($row){
            echo $row;
        }else{
            echo "no";
        }
    }
}
//角色删除
class Action_delrole extends XAction
{
    public function _run($request, $xcontext)
    {
        $rid=intval($request->rid);
        $row=XDao::dwriter("Etc_soleWriter")->delrole($rid);
        if($row){
            echo "删除成功";
        }else{
            echo "no";
        }
    }
}
//编辑角色权限
class Action_showauth extends XAction
{
    public function _run($request, $xcontext)
    {
        $rid=intval($request->rid);
        $rolename=htmlspecialchars($request->rname);

            $list=XDao::query("Etc_roleQuery")->checkauth($rid);
                $auth=$list['auth'];
                    $permission = new Permission($auth);
                    $pers = PermissionEnum::$PERMISSIONS[PermissionEnum::TYPE_HR];
  
                         $array['P_VIEW_BASIC']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_BASIC);
                         $array['P_EDIT_BASIC']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);

                         $array['P_VIEW_PAY']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAY);
                         $array['P_EDIT_PAY']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);

                         $array['P_VIEW_KAOQIN']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_KAOQIN);
                         $array['P_EDIT_KAOQIN']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_KAOQIN);

                         $array['P_VIEW_PAPER']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAPER);
                         $array['P_EDIT_PAPER']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);

                         $array['P_VIEW_CAPITAL']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_CAPITAL);
                         $array['P_EDIT_CAPITAL']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_CAPITAL);
                         $array['P_SEND_CAPITAL']=$permission->allow(PermissionEnum::TYPE_HR, PermissionEnum::P_SEND_CAPITAL);
                    $pers_keys = array_keys($pers);
                    $pers_values = array_values($pers);

                    $new_arr = array();
                    $index = 0;
                    foreach ($array as $k => $v) {                        
                        $new_arr[$k] = array("auth"=>$pers_keys[$index], "info"=>$pers_values[$index], "haveauth"=>$v);

                        $index++;
                    }
            $xcontext->new_arr=$new_arr;
            $xcontext->rid=$rid;
            $xcontext->rname=$rolename;
            $xcontext->auth=$array;
            return XNext::useTpl("showauth.html");
    }
}

// 添加角色权限
class Action_addauth extends XAction
{
    public function _run($request, $xcontext)
    {
        $auth=$_POST;
        $rid = intval($_GET['rid']);
        $permission = new Permission("0");

             foreach($auth as $key =>$value){
                 $auth1 = $permission->add(1,$value)->toString();
              } 
        $row=Etc_roleSvc::ins()->updaterole($auth1,$rid);

        if($row){
           return XNext::gotourl($_SERVER['DOMAIN'].'/showrole.php');
        }  
    }
}
//角色权限分配
class Action_roleassign extends XAction
{
    public function _run($request, $xcontext)
    {
        $uid=intval($request->uid);
        $list=XDao::query("Etc_roleQuery")->showrole();
        $list1=XDao::query("Etc_roleQuery")->havaauth($uid);
            foreach($list as $k=>$v){
               foreach($list1 as $k1 => $v2){
                    if ($v['id'] == $v2['jid']) {
                        $list[$k]['uid'] = $v2['uid'];
                    }
                }
            }
        $xcontext->list=$list;
        $xcontext->uid=$uid;
        return XNext::useTpl("viewrole.html");
    }
}
//用户权限添加
class Action_userauth extends XAction
{
    public function _run($request, $xcontext)
    {
        $uid=intval($_GET['uid']);
        $info=$_POST;
            XDao::dwriter("Etc_soleWriter")->delauthrole($uid);
                foreach($info as $k=>$v){
                   $row=Etc_associatedSvc::ins()->addroleauth($uid,$v);
                }
                    if($row){
                        return XNext::gotourl($_SERVER['DOMAIN'].'/showadmin.php');
                    }
    }
}