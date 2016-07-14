<?php
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");
pylon_include_sdk("/home/q/php/bridge_sdk/", "bridge_sdk.php");
//快递公司列表
class Action_admin_admin_express extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("ecinfoQuery")->allexp();
        $xcontext->list = $list;
        return XNext::useTpl("/admin/admin_express.html");
    }
}
//进入添加快递页面
class Action_admin_addexpress extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/admin/addexpress.html");
    }
}

//执行添加
class Action_admin_doaddexpress extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $data = $_POST;
        $result = ExpresscompanyinfoSvc::ins()->addecinfo($_POST);
        if($result){
            return XNext::gotourl('/admin_admin_express.php');
        }else{
            return XNext::useTpl("/admin/addexpress.html");
        }

    }
}
//进入快递公司修改页面
class Action_admin_editexpress extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $list = XDao::query("ecinfoQuery")->findexp($id);
        $xcontext->list=$list;
        return XNext::useTpl("/admin/editexpress.html");
    }
}
//执行快递公司修改
class Action_admin_doeditexpress extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $shuju = $_POST;
        $id = $request->attr['id'];
        $result = ExpresscompanyinfoSvc::ins()->editecinfo($id,$shuju);
        return XNext::gotourl('/admin_admin_express.php');
    }
}
//开启快递公司
class Action_admin_startexpress extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result = ExpresscompanyinfoSvc::ins()->startecinfo($id);
        return XNext::gotourl('/admin_admin_express.php');
    }
}
//停用快递公司
class Action_admin_stopexpress extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result = ExpresscompanyinfoSvc::ins()->stopecinfo($id);
        return XNext::gotourl('/admin_admin_express.php');
    }
}
//编辑快递模板
class Action_admin_designexpress extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $mid = $request->id;

        //查出一个快递模版的一些属性
        $model = XDao::query("lodopquery")->onemodel($mid);
        //所有的快递模版
        $allmodel = XDao::query("lodopquery")->allmodel();
        //此模版的一些控件
        $item = XDao::query("lodopquery")->onemodel_item($mid);
        //小控件
        $control = XDao::query("ecinfoQuery")->allcontrol();
        foreach($item as $k => $v){
            foreach($control as $k1 => $v1){
                if($v['itemid'] == $v1['id']){
                      $control[$k1]['iteminfo']=$v;
                }
            }
        }
        if(!empty($_FILES)){
            $tmp_name=$_FILES['uploadFile']['tmp_name'];
            if(is_uploaded_file($tmp_name)){
                $qn = BridgeSvc::ins()->callbridge($tmp_name);
                foreach($qn as $k => $v){
                   $model['image']=$v['hash'];
                }
              }else{
                 echo "上传方式不对!";
              }
        }
        $xcontext->model=$model;
        $xcontext->allmodel=$allmodel;
        $xcontext->mid=$mid;
        $xcontext->control=$control;
        return XNext::useTpl("/admin/designexpress.html");
    }
}
//添加快递模板
class Action_admin_adddesignexpress extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        //快递公司
        $express_company = XDao::query("ecinfoQuery")->allexp();
        //小控件
        $control = XDao::query("ecinfoQuery")->allcontrol();
        if(!empty($_FILES)){
            $tmp_name=$_FILES['uploadFile']['tmp_name'];
            if(is_uploaded_file($tmp_name)){
                $qn = BridgeSvc::ins()->callbridge($tmp_name);
                foreach($qn as $k => $v){
                   $hash=$v['hash'];
                }
              }else{
                 echo "上传方式不对!";
              }
        }

        $xcontext->hash=$hash;
        $xcontext->express_company=$express_company;//所有的快递公司
        $xcontext->control=$control;
        return XNext::useTpl("/admin/adddesignexpress.html");
    }
}
//快递单模版保存
class Action_admin_savemodel extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
       //模版的信息
       $templateinfo=$_POST['templateinfo'];
       //小控件
       $temp_item=$_POST['temp_item'];

       $mid = ExpresstemplateinfoSvc::ins()->add_model($templateinfo);
       if($mid){
          if(!empty($temp_item)){
           foreach($temp_item as $k => $v){
              $row = ExpresstemplatepositionSvc::ins()->add_item($v,$mid);
            }
          }
          echo "yes";
       }else{
          echo "no";
       }
    }
}
//快递单模版修改
class Action_admin_updatemodel extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
       //模版的id
       $mid = $_POST['templateinfo']['mid']; 
       //先删除之前的打印项信息
       XDao::dwriter("LodopWriter")->deleteitem($mid);
       //模版的信息
       $templateinfo=$_POST['templateinfo'];
       // 更改模版信息
       ExpresstemplateinfoSvc::ins()->update_model($templateinfo);
       //小控件
       $temp_item=$_POST['temp_item'];

           foreach($temp_item as $k => $v){
              $row = ExpresstemplatepositionSvc::ins()->add_item($v,$mid);
           }
        if($row){
            echo "yes";
        }else{
            echo "no";
        }
    }
}
//快递单模版删除
class Action_admin_deletelodopmodel extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
       //模版的id
       $mid = $request->id; 
       // 更改模版信息
       $row = ExpresstemplateinfoSvc::ins()->delete_model($mid);
        if($row){
            return XNext::gotourl('/admin/admin_mouldset.php');
        }else{
            echo "删除失败";
        }
    }
}
//设置运费页面列表
class Action_admin_billweight extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $xcontext->id = $id;
        $list = XDao::query("ecpresspriceQuery")->cidforprice($id);
        foreach($list as $k=>$v){
            $areanamestr="";
            $storenamestr="";
            $pricestr="首重部分:";
            $parentarray = array();
            $weightarr = array('0.00');
            $arrayarea = explode(":",$v['arealist']);
            $storearr = explode(":",$v['storeid']);
            foreach($arrayarea as $k1=>$v1){
                $areainfo = XDao::query("AreasQuery")->findall($v1);
                if($areainfo['level']==1){
                    $areanamestr .=$areainfo['name'].";";
                    $parentarray[] = $areainfo['number'];
                }
                if(!in_array($areainfo['parent'],$parentarray) && $areainfo['level'] !=1){
                    $areanamestr .=$areainfo['name'].";";
                }
            }
            foreach($storearr as $k2=>$v2){
                $storename = XDao::query("StoreShowQuery")->findname($v2);
                $storenamestr .= $storename['name'].";";
            }
            $list[$k]['areanamestr'] = substr($areanamestr,0,strlen($areanamestr)-1);
            $list[$k]['storenamestr'] = substr($storenamestr,0,strlen($storenamestr)-1);

            for($i=1;$i<=5;$i++){
                if($v['firstweight'."$i"]){
                    $weightarr[] = $v['firstweight'."$i"];
                }
            }
            for($j=1; $j < count($weightarr);$j++){
                $pricestr .= "从".$weightarr["$j"-1]."kg-".$weightarr["$j"]."kg,运费为:￥".$v['firstprice'."$j"].";";
            }
            $pricestr .="<br />续重部分：重量每增加".$v['weightincrease']."kg,增加费用:￥".$v['priceincrease'];
            $list[$k]['pricestr'] = $pricestr;
        }
        $xcontext->list=$list;
        return XNext::useTpl("/admin/billweight.html");
    }
}
//删除运费
class Action_admin_delbillweight extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result = XDao::dwriter("EcpresspriceWriter")->delprice($id);
        echo $result;
        return XNext::nothing();
    }
}
//进入编辑页面
class Action_admin_editbillweight extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $list = XDao::query("ecpresspriceQuery")->findprice($id);
        $areanamestr="";
        $storenamestr="";
        $pricestr="首重部分:";
        $parentarray = array();
        $weightarr = array('0.00');
        $arrayarea = explode(":",$list['arealist']);
        $storearr = explode(":",$list['storeid']);
        foreach($arrayarea as $k1=>$v1){
            $areainfo = XDao::query("AreasQuery")->findall($v1);
            if($areainfo['level']==1){
                $areanamestr .=$areainfo['name'].";";
                $parentarray[] = $areainfo['number'];
            }
            if(!in_array($areainfo['parent'],$parentarray) && $areainfo['level'] !=1){
                $areanamestr .=$areainfo['name'].";";
            }
        }
        foreach($storearr as $k2=>$v2){
            $storename = XDao::query("StoreShowQuery")->findname($v2);
            $storenamestr .= $storename['name'].";";
        }
        $list['areanamestr'] = substr($areanamestr,0,strlen($areanamestr)-1);
        $list['storenamestr'] = substr($storenamestr,0,strlen($storenamestr)-1);

        for($i=1;$i<=5;$i++){
            if($list['firstweight'."$i"]){
                $weightarr[] = $list['firstweight'."$i"];
                $arrayweight[] = $list['firstweight'."$i"];
                $pricearr[] = $list['firstprice'."$i"];
            }
        }
        for($j=1; $j < count($weightarr);$j++){
            $pricestr .= "从".$weightarr["$j"-1]."kg-".$weightarr["$j"]."kg,运费为:￥".$list['firstprice'."$j"].";";
        }
        $pricestr .="\r\n续重部分：重量每增加".$list['weightincrease']."kg,增加费用:￥".$list['priceincrease'];
        $list['pricestr'] = $pricestr;
        $xcontext->list = $list;
        $pro = XDao::query("AreasQuery")->getProByLevel();
        foreach($pro as $k=>$v){
            if(in_array($v['number'],$arrayarea)){
                $proflag = 1;
            }else{
                $proflag = 0;
            }
            $pro[$k]['proflag'] = $proflag;
            $city = XDao::query("AreasQuery")->getCityByLevel($v['number']);
            foreach ($city as $key => $value) {
                if(in_array($value['number'],$arrayarea)){
                    $cityflag = 1;
                }else{
                    $cityflag = 0;
                }
                $city[$key]['cityflag'] = $cityflag;
            }
            $pro[$k]['city'] = $city;
        }
        $store = XDao::query("StoreShowQuery")->listStoreInfo();
        foreach($store as $ks => $vs){
            if(in_array($vs['id'],$storearr)){
                $storeflag = 1;
            }else{
                $storeflag = 0;
            }
            $store[$ks]['storeflag'] = $storeflag;
        }
        $xcontext->pro = $pro;
        $xcontext->store = $store;
        $xcontext->areaid = $list['arealist'];
        $xcontext->storeid = $list['storeid'];
        $xcontext->firstweight = implode(":",$arrayweight);
        $xcontext->firstprice = implode(":",$pricearr);
        $xcontext->weightincrease = $list['weightincrease'];
        $xcontext->priceincrease = $list['priceincrease'];
        return XNext::useTpl("/admin/editbillweight.html");
    }
}
//执行编辑
class Action_admin_doeditbillweight extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $data=$_POST;
        $id = $request->attr['id'];
        $result = ExpresspriceSvc::ins()->editprice($id,$data);
        echo $result;
        return XNext::nothing();
    }
}
//进入快递按重量运费规则添加页面
class Action_admin_addbillweight extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $xcontext->id = $id;
        $list = XDao::query("AreasQuery")->getProByLevel();
        foreach($list as $k=>$v){
            $city = XDao::query("AreasQuery")->getCityByLevel($v['number']);
            $list[$k]['city'] = $city;
        }
        $xcontext->list = $list;
        return XNext::useTpl("/admin/addbillweight.html");
    }
}
// 执行快递按重量运费规则添加
class Action_admin_doaddbillweight extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $data=$_POST;
        $result = ExpresspriceSvc::ins()->addprice($data);
        echo $result;
        return XNext::nothing();
    }
}
class Action_admin_billvolume extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id =$request->attr['id'];
        $xcontext->id=$id;
        return XNext::useTpl("/admin/billvolume.html");
    }
}
//所有的模版
class Action_admin_admin_mouldset extends XBaseLoginAction
{
    public function _run($request, $xcontext)
    {
        //所有的快递模版
        $model = XDao::query("lodopquery")->allmodel();
        // echo "<pre>";
        // var_dump($model);
        $xcontext->model=$model;
        return XNext::useTpl("/admin/admin_mouldset.html");
    }
    public function getPermission()
    {
        return new Permission(1,1);
    }
}
class Action_admin_shipsetlist extends XLoginAction
{
    public function _run($request, $xcontext)
    {

        return XNext::useTpl("/admin/shipsetlist.html");
    }
}

//进去分配权限页面
class Action_admin_admin_power extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
        $id=$_GET['id'];
        $xcontext->id=$id;
        $auth = XDao::query("roleQuery")->findauth($id);
        $auth = $auth['authority'];
        $p = new Permission($auth);   
        $list = XDao::query("moduleQuery")->allmodule();
        foreach($list as $k=>$v){
            $list[$k]['qx']=XDao::query("authorityQuery")->auth($list[$k]['id']);
            foreach($list[$k]['qx'] as $kk=>$val){
                if($p->allow($v['value'],$val['value'])){
                    $list[$k]['qx'][$kk]['status']=1;
                }else{
                    $list[$k]['qx'][$kk]['status']=0;
                }
            }
        }
        $xcontext->list=$list;
        return XNext::useTpl("/admin/admin_power.html");
    }

    public function getPermission()
    {
        return new Permission(1, 1);
    }
}

class Action_admin_admin_shopset extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $ql = SundialQL::create()
          ->select()
          ->from("systemshop")
          ->where("$0.isdelete", "=", "N");
        $list = $ql->querys();  
        $xcontext->list = $list;
        return XNext::useTpl("/admin/admin_shopset.html");
    }
}
//新增店铺
class Action_admin_addshop extends XAction
{
    public function _run($request, $xcontext)
    {
        $name = $request->attr['name'];
        $comment = $request->attr['comment'];
        $result=SystemshopSvc::ins()->add($name,$comment);
        return XNext::gotourl('/admin/admin_shopset.php');
    }
}
//修改店铺
class Action_admin_editshop extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $name = $request->attr['name'];
        $comment = $request->attr['comment'];
        $result=SystemshopSvc::ins()->edit($id,$name,$comment);
        echo $result;
    }
}
//删除店铺
class Action_admin_delshop extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result=SystemshopSvc::ins()->del($id);
        echo $result;
    }
}
//人员列表
class Action_admin_admin_staff extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
        $seach = strval($request->attr['seach']);
        if(strlen($seach)!=0){
            $where = "where name like '%$seach%' and status <> 'S'";
        }else{
            $where ="where status <> 'S'";
        }
        $lists = XDao::query("userQuery")->userlists($where);
        $arr['total_rows'] = count($lists);
        $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
        $aaa = new Core_Lib_Page($arr);
        if(strlen($seach)!=0){
            $aaa->seach = "&seach=$seach";
        }
        $pages = $aaa->show(3);
        $list = XDao::query("userQuery")->userlist($where,$aaa->first_row,$arr['list_rows']);
        foreach($list as $k=>$v){
            $list[$k]['info'] = XDao::query("userinfoQuery")->userinfolist($list[$k]['id']);
            if($list[$k]['info']['departmentid']){
               $dename= XDao::query("departmentQuery")->findname($list[$k]['info']['departmentid']);
               $list[$k]['info']['department'] = $dename['name'];
           }
           $roleid = XDao::query("userroleQuery")->findrole($list[$k]['id']);
           foreach ($roleid as $key => $value) {
            $rolename = XDao::query("roleQuery")->findrolename($value['roleid']);
            $list[$k]['info']['rolenames'][] = $rolename['name'];
        }
    }
    $xcontext->list = $list;
    $xcontext->pages = $pages;
    $xcontext->seach=$seach;
    return XNext::useTpl("/admin/admin_staff.html");
}
}
//进入添加人员页面
class Action_admin_admin_addstaff extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $partment = XDao::query("departmentQuery")->partmentinfo();
        $company = XDao::query("companyQuery")->allcompanyinfo();
        $companysales = XDao::query("companysalesQuery")->allcompanysalesinfo();
        $role = XDao::query("roleQuery")->roleinfo();
        
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent_id'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catelist = get_sort_by_array($partment);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['name'] = str_repeat("　", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->catelist = $catelist;
        $xcontext->company=$company;
        $xcontext->companysales=$companysales;
        $xcontext->role=$role;
        return XNext::useTpl("/admin/admin_addstaff.html");
    }
}
//执行添加人员
class Action_admin_admin_doaddstaff extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $role = $request->attr['role'];
        var_dump($role);
        $strrole = implode(":",$role);
        $username = $request->attr['username'];
        $password = $request->attr['password'];
        $repassword = $request->attr['repassword'];
        $data['realname'] = $request->attr['realname'];
        $data['number'] = $request->attr['number'];
        $data['tel'] = $request->attr['tel'];
        $data['partment'] = $request->attr['parment'];
        $data['company'] = $request->attr['company'];
        $data['companysales'] = $request->attr['sales'];
        $adduser = UserSvc::ins()->addUser($username,$password);
        $data['userid'] = $adduser['id'];
        $result=UserinfoSvc::ins()->adduserinfo($data);
        $userid = $adduser['id'];
        foreach($role as $k=>$v){
            $userrole = UserroleSvc::ins()->addUserrole($userid,$v);
        }
        return XNext::gotourl('/admin_admin_addstaff.php');
    }
}
// 进入修改人员页面
class Action_admin_admin_editstaff extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
        $id = $request->attr['id'];
        $userone = XDao::query("UserQuery")->userone($id);
        $userinfoone = XDao::query("userinfoQuery")->userinfolist($id);
        $partment = XDao::query("departmentQuery")->partmentinfo();
        $company = XDao::query("companyQuery")->allcompanyinfo();
        $companysales = XDao::query("companysalesQuery")->allcompanysalesinfo();
        $role = XDao::query("roleQuery")->roleinfo();
        $roleid = XDao::query("userroleQuery")->findrole($id);
        foreach($role as $k=>$v){
         foreach($roleid as $k1 => $v2){
            if ($v['id'] == $v2['roleid']) {
                $role[$k]['uid'] = $v2['userid'];
            }
        }
    }
    function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent_id'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catelist = get_sort_by_array($partment);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['name'] = str_repeat("　", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->partment=$catelist;
        $xcontext->company=$company;
        $xcontext->companysales=$companysales;
        $xcontext->role=$role;
        $xcontext->userone=$userone;
        $xcontext->userinfoone=$userinfoone;
        $xcontext->rolearray=$rolearray;
        return XNext::useTpl("/admin/admin_editstaff.html");
    }
}

//进入修改密码页面
class Action_admin_changepassword extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->attr['id'];
        $xcontext->uid = $userid;
        return XNext::useTpl("/admin/changepassword.html");
    }
}
//执行修改密码
class Action_admin_dochangepassword extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->attr['userid'];
        $password = $request->attr['password'];
        $result=SecuritySvc::ins()->updateSecurity($userid,$password,0);
        if($result){
            $status=1;
        }else{
            $status=2;
        }
        $xcontext->success = $status;
        $xcontext->uid = $userid;
        return XNext::useTpl("/admin/changepassword.html");
    }
}
//进入个人设置页面
class Action_admin_userset extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/admin/userset.html");
    }
}
// 执行修改自己的密码
class Action_admin_douserset extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $userinfo = explode("=",$_COOKIE['U']);
        $userid = $userinfo['2'];
        $password = $request->attr['password'];
        $result=SecuritySvc::ins()->updateSecurity($userid,$password,0);
        var_dump($result);
        if($result){
            $status=1;
        }else{
            $status=2;
        }
        $xcontext->success = $status;
        return XNext::useTpl("/admin/userset.html");
    }
}
//判断原密码是否正确
class Action_admin_checkoldpass extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $userinfo = explode("=",$_COOKIE['U']);
        $userid = $userinfo['2'];
        $userSecurity = new Security($userid);
        $userSecurity->get();
        $passwd_in = $userSecurity->password;
        $passwd_out = $request->attr['oldpass'];
        $newpass=SecuritySvc::ins()->hashedOriginPasswd($userid, $passwd_out);
        if($passwd_in==$newpass){
            echo "1";
        return XNext::nothing();
        }else{
            echo "0";
        return XNext::nothing();
        }
    }
}
//执行修改人员
class Action_admin_admin_updatestaff extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->attr['userid'];
        $data['realname'] = $request->attr['realname'];
        $data['tel'] = $request->attr['tel'];
        $data['number'] = $request->attr['number'];
        $data['departmentid'] = $request->attr['parment'];
        $data['purchasecompid'] = $request->attr['company'];
        $data['salesid'] = $request->attr['sales'];
        $role = $request->attr['role'];
        $res = XDao::dwriter("UserroleWriter")->deluserrole($userid);
        foreach($role as $k=>$v){
            $userrole = UserroleSvc::ins()->addUserrole($userid,$v);
        }
        $id = XDao::query("UserinfoQuery")->findid($userid);
        $id = $id['id'];
        $resu=UserinfoSvc::ins()->updateuserinfo($data,$id);
        return XNext::gotourl('/admin_admin_staff.php');
    }
}
//删除人员
class Action_admin_admin_delstaff extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result = XDao::dwriter("UserWriter")->delstaff($id);
        if($result){
        $res = XDao::dwriter("UserinfoWriter")->deluserinfo($id);
        }
        echo $result;
        return XNext::nothing();
    }
}
//停用人员
class Action_admin_admin_stopstaff extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result = XDao::dwriter("UserWriter")->stopstaff($id);
        echo $result;
        return XNext::nothing();
    }
}
//启用人员
class Action_admin_admin_startstaff extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result = XDao::dwriter("UserWriter")->startstaff($id);
        echo $result;
        return XNext::nothing();
    }
}
//查看部门列表
class Action_admin_admin_department extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("departmentQuery")->allpartmentinfo();

        foreach ($list as $key => $value) {
            $value['bbb']= $value['parent_id'];
            $aaa = $this->findpar($value['parent_id']);
            $value['bbb'].= ":".$aaa;
            $arrvalue = explode(":",$value['bbb']);
            array_pop($arrvalue);
            foreach($arrvalue as $kk=>$v){

                $list11 = XDao::query("departmentQuery")->findname($v);
                $list[$key]['funame'][] = $list11['name'];
            }
        }
          function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent_id'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catelist = get_sort_by_array($list);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['name'] = str_repeat("　｜", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->list=$catelist;
        // var_dump($list);
        return XNext::useTpl("/admin/admin_department.html");
    }
    public function findpar($id)
    {
        $row = XDao::query("departmentQuery")->findfuid($id);
        $row = $row['parent_id'];
        $ccc =  $row;
        if($row != 0){
         $ccc.= ":".$this->findpar($row);
     }

     return $ccc;

 }
}

//到添加部门
class Action_admin_admin_adddepartment extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("departmentQuery")->partmentinfo();
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {

                if($v['parent_id'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }

        $catelist = get_sort_by_array($list);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['name'] = str_repeat("　", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->catelist = $catelist;
        return XNext::useTpl("/admin/admin_adddepartment.html");
    }
}
//执行添加部门
class Action_admin_admin_doadddepartment extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $data=$_POST;
        $result=DepartmentSvc::ins()->addperment($data);
        echo $result;
        return XNext::nothing();
    }
}
//删除部门
class Action_admin_admin_delpart extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $count = XDao::query("departmentQuery")->findforparent($id);
        if($count['count(*)']==0){
         $result = XDao::dwriter("DepartmentWriter")->deldepartment($id);
        echo $result;
        }else{
            echo "请先删除下级部门!";
        return XNext::nothing();
        }
    }
}
//进入编辑部门页面
class Action_admin_admin_editdepartment extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $_GET['id'];
        $list = XDao::query("departmentQuery")->partmentinfo();
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent_id'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catelist = get_sort_by_array($list);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['name'] = str_repeat("　", $v['level'] - 1).$v['name'];
            }
        }
        $result = XDao::query("departmentQuery")->findcomname($id);
        $xcontext->catelist = $catelist;
        $xcontext->res = $result;
        $xcontext->id=$id;
        return XNext::useTpl("/admin/admin_editdepartment.html");
    }
}
//执行修改部门
class Action_admin_admin_doeditdepart extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $partment = $request->attr['partment'];
        $beizhu = $request->attr['beizhu'];
        $parent_id = $request->attr['parent_id'];
        $result = XDao::dwriter("DepartmentWriter")->editdepartment($partment,$beizhu,$parent_id,$id);
        return XNext::gotourl('/admin_admin_department.php');
    }
}
class Action_admin_admin_department_staff extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("departmentQuery")->allpartmentinfo();

        foreach ($list as $key => $value) {
            $username=array();
            $res = XDao::query("userinfoQuery")->findforpartment($value['id']);
            foreach($res as $k1=>$v1){
                $user = XDao::query("UserQuery")->userone($v1['userid']);
                $username[] = $user['name'];
            }
            $username = implode(", ",$username);
            $list[$key]['username'] = $username;
        }
          function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent_id'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catelist = get_sort_by_array($list);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['name'] = str_repeat("　｜", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->list=$catelist;
        return XNext::useTpl("/admin/admin_department_staff.html");
    }
}
//查看角色列表
class Action_admin_admin_role extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("roleQuery")->roleinfo();
        $xcontext->list=$list;
        return XNext::useTpl("/admin/admin_role.html");
    }
}
//进入添加角色页面
class Action_admin_admin_addrole extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/admin/admin_addrole.html");
    }
}
//执行添加角色
class Action_admin_admin_doaddrole extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $data=$_POST;
        $result=RoleSvc::ins()->addrole($data);
        echo $result;
        return XNext::nothing();
    }
}
//修改角色
class Action_admin_admin_editrole extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
        $id=$_GET['id'];
        $list = XDao::query("roleQuery")->findrole($id);
        $xcontext->list=$list;
        return XNext::useTpl("/admin/admin_editrole.html");
    }
}
//修改角色
class Action_admin_admin_doupdaterole extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
        $name=$_POST['name'];
        $comment=$_POST['comment'];
        $id=$_POST['id'];
        $list = XDao::dwriter("RoleWriter")->updaterole($name,$comment,$id);
        echo $list;
        return XNext::nothing();
    }
}
//删除角色
class Action_admin_admin_delrole extends XLoginAction
{
    public function _run($request, $xcontext)
    {   
        $id=$_POST['id'];
        $list = XDao::dwriter("RoleWriter")->delrole($id);
        echo $list;
        return XNext::nothing();
    }
}
//给角色分配权限
class Action_admin_admin_doaddauth extends XLoginAction
{
    public function _run($request, $xcontext)

    {    
        $aaa=new Permission();
        foreach($_POST as $k=>$v){
            foreach($v as $val){
                $bbb=$aaa->add($k,$val);
                $ccc=$bbb->toString();
            }        
        }
        $auth=$ccc;
        $id=$_GET['id'];
        $list = XDao::dwriter("RoleWriter")->updateauth($auth,$id);
        return XNext::gotourl('/admin_admin_role.php');
    }
}
//查询所有仓库
class Action_admin_findallstore extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("StoreShowQuery")->listStoreInfo();  
        echo json_encode($list,JSON_UNESCAPED_UNICODE);
        return XNext::nothing();
    }
}
//物流公司列表
class Action_admin_wuliucompany extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $ql = SundialQL::create()
          ->select()
          ->from("transportinfo")
          ->where("$0.isdelete","=","N");
        $result = $ql->querys();
        $xcontext->list = $result;  
        return XNext::useTpl("/admin/wuliucompany.html");
    }
}
//添加物流
class Action_admin_addwuliu extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/admin/addwuliu.html");
    }
}
//执行添加物流
class Action_admin_doaddwuliu extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $data = $_POST;
        $result=TransportinfoSvc::ins()->add($data);
        return XNext::gotourl('/admin/wuliucompany.php');
    }
}
//编辑物流
class Action_admin_editwuliu extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $ql = SundialQL::create()
          ->select()
          ->from("transportinfo")
          ->where("$0.id","=",$id);
        $result = $ql->query();
        $xcontext->list = $result;  
        return XNext::useTpl("/admin/editwuliu.html");
    }
}
//执行编辑物流
class Action_admin_doeditwuliu extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $data = $_POST;
        $result=TransportinfoSvc::ins()->edit($data);
        return XNext::gotourl('/admin/wuliucompany.php');
    }
}
//执行删除物流公司
class Action_admin_delwuliu extends XLoginAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result=TransportinfoSvc::ins()->del($id);
        echo $result;
    }
}