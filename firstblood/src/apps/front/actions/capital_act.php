<?php

class Action_capital_class extends XAction
{
    public function _run($request,$xcontext)
    {
    	$capital_class_list=XDao::query("CapitalQuery")->all_capital_class();
    	$xcontext->capital_class_list=$capital_class_list;
        return XNext::useTpl("capital/capital_class.html");
    }
}
//添加分类
class Action_addcapital_class extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
        $capitalclas_name=htmlspecialchars($_POST['cname']);
        $prefix_name=htmlspecialchars($_POST['prefix_name']);
        $capital_class_list=XDao::query("CapitalQuery")->all_capital_class();
        foreach($capital_class_list as $k => $v){
           if(in_array($prefix_name,$v)){
              echo "重复";
              return false;
           }
        }
        $cid = Capital_classSvc::ins()->addcapital_class($capitalclas_name,$prefix_name);
        if ($cid) {
        	echo $cid;
        } else {
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_CAPITAL);
    }
}
//删除分类
class Action_deletecapital_class extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
        $cid=intval($_POST['cid']);
        $row = XDao::dwriter("CapitalWriter")->delete($cid);
        if($row){
        	echo "yes";
        }else{
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_CAPITAL);
    }
}
//所有物品
class Action_allcapital extends XAction
{
    public function _run($request,$xcontext)
    {

    	$where = "where status = 'T'";

    	if(!empty($_GET['capital_class'])){
    		 $where .= " and cid = {$_GET['capital_class']}";
    	}
    	if(!empty($_GET['name'])){
    		 $where .= " and capitalname like '%{$_GET['name']}%'";
    	}
    	if(!empty($_GET['number'])){
    		 $where .= " and number like '%{$_GET['number']}%'";
    	}
    	if(!empty($_GET['pname'])){
    		 $where .= " and pname like '%{$_GET['pname']}%'";
    	}
       $address="";
       $fuhao = "&";
       foreach($_GET as $k=>$v){
  	       	  if($k !="do" && $k != "p" && $v != "allcapital"){
	       		$str = $k."=".$v.$fuhao;
	       	  }
         $address.=$str;
       }

       $address = substr($address,0,strlen($address)-1); 
 	   $count=XDao::query("CapitalQuery")->count_capital($where);  
	   $num=$count['num'];//总条数
	   $pagesize=20; //一页显示多少条
	   $page = new Page($num,$pagesize); //实例化
	   $maxpage=$page->checkMaxPage(); //最大页数
	   $nowpage=$page->page; //当前第几页  
	   $firstpage="<a href='{$url}?p=1&{$address}'>首页</a>";
	   $lastpage="<a href='{$url}?p={$maxpage}&{$address}'>末页</a>";
   	   $prevpage="<a href='{$url}?p=".($nowpage-1)."&{$address}'>前一页</a>";
	   $nextpage="<a href='{$url}?p=".($nowpage+1)."&{$address}'>后一页</a>";
	   $start=intval(explode(",",$page->limit())[0]);
	   $stop=intval(explode(",",$page->limit())[1]);

  	   $peopleclass = XDao::query("Etc_classQuery")->listclass();

       $capital_class_list=XDao::query("CapitalQuery")->all_capital_class();
    	
       $capitallist=XDao::query("CapitalQuery")->all_capital($where,$start,$stop);

       $xcontext->firstpage=$firstpage;
       $xcontext->lastpage=$lastpage;
       $xcontext->prevpage=$prevpage;
       $xcontext->nextpage=$nextpage;
       $xcontext->maxpage=$maxpage;//最大页
       $xcontext->nowpage=$nowpage;//当前页
       $xcontext->num=$num;//条数

		$xcontext->peopleclass=$peopleclass; //部门
    	$xcontext->capital_class_list=$capital_class_list;//分类
    	$xcontext->capitallist=$capitallist;//分类
        return XNext::useTpl("capital/allcapital.html");
    }
}
//添加物品
class Action_addcapital extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$capital_info = $_POST;
        $cid = $_POST['captal_class'];//分类
        //查到分类的前缀
        $prefix=XDao::query("CapitalQuery")->prefix($cid);
        $brand = $_POST['brand'];
        $capital_num = $_POST['capital_num'];
        $argument = $_POST['argument'];

        $time = date("Y-m-d",time());

        //日期编号
        $timefix = date("ymd",time());
        $str ="QWERTYUIOPASDFGHJKLZXCVBNM12345678901234578809876554QWERTYUIOPASDFGHJKLZXCVBNM09876543321123456789009876";
        $max = strlen($str)-1;//最大长度
        for($i = 0 ; $i<$capital_num; $i++){
            for($j = 0; $j<6; $j++){
            	 $number[$i] .= $str[rand(0,$max)];
            }
        }

        for($k = 0 ; $k<count($number) ; $k ++){
        	$fix = $prefix['prefix']."-".substr($timefix,0,2).substr($number[$k],0,2)."-".substr($timefix,2,2).substr($number[$k],2,2)."-".substr($timefix,4,2).substr($number[$k],4,2);
        	$row = CapitalSvc::ins()->addcapital($fix,$argument,$time,$brand,$cid);
        }
        if($row){
        	echo "yes";
        }else{
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_CAPITAL);
    }
}

//修改物品信息
class Action_updatecapital extends XAction
{
    public function _run($request,$xcontext)
    {
    	$id = $request->id;
    	$one_capital=XDao::query("CapitalQuery")->one_capital($id);
    	echo json_encode($one_capital);
    }
}
//执行修改
class Action_doupdatecapital extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
       $capital_info = $_POST;
       $row = CapitalSvc::ins()->updatecapital($capital_info);
       if($row){
       	  echo "yes";

        // //日志记录开始
        $date=date("Y-m-d H:i:s",time());
        //用户名
        $userid = intval($_SESSION['uid']);
        //被编辑的编号      
        $matter_num =$capital_info['matter_num'];
        
        $logcontent="修改了".$matter_num."的信息";
        
        $type = 4;

        $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);
       }else{
       	  echo "no";
       }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_CAPITAL);
    }
}
// 请求分类
class Action_ajaxcapital_class extends XAction
{
    public function _run($request,$xcontext)
    {
    	$capital_class_list=XDao::query("CapitalQuery")->all_capital_class();
        echo json_encode($capital_class_list);
    }
}
//请求人员
class Action_showpeople extends XAction
{
    public function _run($request,$xcontext)
    {
      $classid = $request->classid;
      $pname = $request->pname;
      $where = " where del = 1";
      if(isset($classid)){
          $where .= " and bid = {$classid}";
      }
      if(isset($pname)){
          $where .= " and name like '%".$pname."%'";
      }
    	$people_list=XDao::query("CapitalQuery")->all_people($where);
      echo json_encode($people_list);
    }
}
//执行删除
class Action_delete_capital extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
       $id = intval($request->id);
       $row = CapitalSvc::ins()->delete_capital($id);
       if($row){
       	  echo "yes";

        // //日志记录开始
        $date=date("Y-m-d H:i:s",time());
        $capital=XDao::query("CapitalQuery")->one_capital($id);
        //用户名
        $userid = intval($_SESSION['uid']);
        //被编辑的编号      
        $number =$capital['number'];
        
        $logcontent="删除了".$number;
        
        $type = 4;

        $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);

       }else{
       	  echo "no";
       }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_CAPITAL);
    }
}
//发配物品
class Action_belong extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
       $capital_id = intval($_POST['cap_id']);
       $pname = htmlspecialchars($_POST['pname']);
       $row = CapitalSvc::ins()->add_belong($capital_id,$pname);

       if($row){
       	  echo "yes";

        // //日志记录开始
        $date=date("Y-m-d H:i:s",time());
        $capital=XDao::query("CapitalQuery")->one_capital($capital_id);
        //用户名
        $userid = intval($_SESSION['uid']);
        //被编辑的编号      
        $number =$capital['number'];
        
        $logcontent="将".$number."分配给了".$pname;
        
        $type = 4;

        $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);

       }else{
       	  echo "no";
       }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_SEND_CAPITAL);
    }
}
//收回
class Action_capital_back extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
       $capital_id = $_POST[id];
       $capitalname = $_POST['capitalname'];
       $row = CapitalSvc::ins()->tackback($capital_id);
       if($row){
       	  echo "yes";

        // //日志记录开始
        $date=date("Y-m-d H:i:s",time());
        $capital=XDao::query("CapitalQuery")->one_capital($capital_id);
        //用户名
        $userid = intval($_SESSION['uid']);
        //被编辑的编号      
        $number =$capital['number'];
        
        $logcontent="从".$capitalname."收回了".$capital['number'];
        
        $type = 4;

        $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);

       }else{
       	  echo "no";
       }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_SEND_CAPITAL);
    }
}