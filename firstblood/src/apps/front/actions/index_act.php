<?php
session_start();
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");
pylon_include_sdk("/home/q/php/bridge_sdk/", "bridge_sdk.php");
class Action_index extends XAction
{
    public function _run($request,$xcontext)
    {
    	if(isset($_SESSION['username'])){
          return XNext::useTpl('index.html');
    	}else{
    	  return XNext::useTpl('login.html');
    	}
    }
}
class Action_dolog extends XAction
{
    public function _run($request,$xcontext)
    {

        if(empty($_SESSION['uid'])){
            return XNext::useTpl("login.html");
        }else{
         //当前时间
         $time = date("Y-m-d", strtotime("+2 month"));  //合同到期
         $month = date("m",time());
         $day = date("d",strtotime("+1 week"));

         $where = " where del=1 and pactover < '{$time}' and pactover <> '0000-00-00' and outtime=''";

         $where1 = " where del=1 and month(birth) = '$month' and day(birth) < '$day' and outtime=''";
         
         $pactlist=XDao::query("Etc_pactbirthQuery")->selpactover($where);

         $birthlist=XDao::query("Etc_pactbirthQuery")->selbirth($where1);

         $xcontext->birthlist=$birthlist;

         $xcontext->pactlist=$pactlist;

         return XNext::useTpl('right.php');
       }
    }
}
class Action_info extends XAction
{
	public function _run($request,$xcontext)
	{
		return XNext::useTpl("index.html");
	}
}
//遍历左边所有的分类
class Action_left extends XAction
{
	public function _run($request,$xcontext)
	{

    if(empty($_SESSION['uid'])){
        return XNext::useTpl("login.html");
    }else{
  		$list=XDao::query("Etc_classQuery")->listclass();
  		$xcontext->list=$list;
  		//遍历题库的分类
  		$rows=XDao::query("question_Query")->categary();
      $xcontext->rows=$rows;
      // $capital_class = XDao::query("CapitalQuery")->all_capital_class();
      // $xcontext->capital_class=$capital_class;
  		return XNext::useTpl('left.php');
   }
	}
}
//部门星级管理
class Action_heart extends XAction
{
	public function _run($request,$xcontext)
	{
		$cid=intval($request->cid);//部门id
		$list=XDao::query("Etc_heartQuery")->listheart($cid);
		$xcontext->list=$list;
		$xcontext->cid=$cid;
		return XNext::useTpl('heart.html');
	}
}
//部门星级修改最低最高工资
class Action_change extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$info=$request->attr;
		$id=intval($info['id']);//薪酬等级的id
		$wage=htmlspecialchars($info['wages']);//薪酬等级的最低工资或者是最高工资字段
		$price=htmlspecialchars($info['price']);//最低工资或者是最高工资
		if($wage=="lowpay"){
          $row=XDao::dwriter("Etc_heartWriter")->uplowpay($price,$id);
		}else if($wage=="highpay"){
		  $row=XDao::dwriter("Etc_heartWriter")->uphighpay($price,$id);
		}  
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//添加星级
class Action_addheart extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       $name = htmlspecialchars($request->heart);
       $cid = intval($request->cid);
       $lowpay = intval($request->lowpay);
       $highpay = intval($request->highpay);
       $row=Etc_heartclassSvc::ins()->addheart($name,$lowpay,$highpay,$cid);
       if($row){
       	   echo $row;
       }else{
       	   echo "no";
       }
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}

//星级重命名
class Action_changeheartname extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       $name = htmlspecialchars($request->heartname);
       $id = intval($request->id);
       $row=XDao::dwriter("Etc_heartWriter")->updatename($name,$id);
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//星级删除
class Action_removeheart extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       $id = intval($request->id);
       $row=XDao::dwriter("Etc_heartWriter")->delheart($id);
       if($row){
       	 echo "删除成功";
       }else{
       	 echo "no";
       }
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}

//遍历所有的员工信息
class Action_peopleinfo extends XAction
{
	public function _run($request,$xcontext)
	{

	   //如果有搜索
   	   $info = $request->attr;
       		$name = htmlspecialchars($info['name']);
      			$signtime = htmlspecialchars($info['signtime']);
            $signtimeover = htmlspecialchars($info['signtimeover']);
      					$skill = htmlspecialchars($info['skill']);
      						$section = htmlspecialchars($info['section']);
       $job = htmlspecialchars($info['job']);
   			$education = htmlspecialchars($info['education']);
     			  $wed = htmlspecialchars($info['wed']);
     					$safe = htmlspecialchars($info['safe']);
   							 $try = htmlspecialchars($info['try']);
        $birthstart=htmlspecialchars($info['birthstart']);
        	$pactover=htmlspecialchars($info['pactover']);

        $cpf = htmlspecialchars($info['cpf']);
            $lodge = htmlspecialchars($info['lodge']);
                $pact = htmlspecialchars($info['pact']);
                    $pacttime = htmlspecialchars($info['pacttime']);
        
        $outtime = htmlspecialchars($info['outtime']);
          $outovertime = htmlspecialchars($info['outovertime']);

        $peoplestatus = htmlspecialchars($info['peoplestatus']);
       $where = "where del=1";
       
       //当前时间
       $time = date("Y-m-d", strtotime("+2 month"));  //合同到期
       $month = date("m",time());
       $day = date("d",strtotime("+1 week"));

       $birthtime = $month.$day; //生日
       if(!empty($name)){
	      $names = " and name like '%$name%'";
       }
       if(!empty($signtime) && !empty($signtimeover)){
          $signtimes= " and hiredate > '$signtime' and hiredate < '$signtimeover'";
       }
       if(!empty($section)){
          $sections= " and bid like '%$section%'";
       }
       if(!empty($job)){
          $jobs= " and jid like '%$job%'";
       }
       if(!empty($education)){
          $educations= " and education like '%$education%'";
       }
       if(!empty($wed)){
          $weds= " and wed like '%$wed%'";
       }
       if(!empty($safe)){
          $safes= " and safe like '%$safe%'";
       }
       if(!empty($skill)){
       	  $skills = " and skillcontent like '%$skill%'";
       }
       if(!empty($try)){
          $trys = " and try like '%$try%'";
       }
       if(!empty($pactover)){
       	  $times = " and pactover < '{$time}' and pactover <> '0000-00-00' and outtime=''";
       }
       if(!empty($birthstart)){
       	  $births = " and month(birth) = '$month' and day(birth) < '$day' and outtime=''";
       }

       if(!empty($cpf)){
          $cpfs = " and cpf like '%$cpf%'";
       }
       if(!empty($lodge)){
          $lodges = " and lodge like '%$lodge%'";
       }
       if(!empty($pact)){
          $pacts = " and pact like '%$pact%'";
       }
       if(!empty($pacttime)){
          $pacttimes = " and pacttime like '%$pacttime%'";
       }
       if(!empty($peoplestatus)){
          $peoplestatuss = " and peoplestatus = '$peoplestatus'";
       }
       if(!empty($outtime) && !empty($outovertime)){
          $outtimes= " and outtime > '$outtime' and outtime < '$outovertime'";
       }

       if(!empty($skill)){
          $where = ",etc_skill where del = 1 and etc_peopleinfo.id = etc_skill.pid".$names.$signtimes.$skills.$sections.$jobs.$educations.$weds.$safes.$trys.$times.$births.$cpfs.$lodges.$pacts.$pacttimes.$outtimes.$peoplestatuss;
       }else{
          $where .= $names.$signtimes.$sections.$jobs.$educations.$weds.$safes.$trys.$times.$births.$cpfs.$lodges.$pacts.$pacttimes.$outtimes.$peoplestatuss;
       }
       $address="";
       $fuhao = "&";
       foreach($_GET as $k=>$v){
  	       	  if($k !="do" && $k != "p" && $v != "peopleinfo"){
	       		$str = $k."=".$v.$fuhao;
	       	  }
         $address.=$str;
       }

       $address = substr($address,0,strlen($address)-1); 

      $count=XDao::query("Etc_peopleinfoQuery")->countinfo($where);  
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
     $rows=XDao::query("Etc_peopleinfoQuery")->listinfo($where,$start,$stop);
       $xcontext->list=$rows;
       $xcontext->time=$time;
       $xcontext->birthtime=$birthtime;
       $xcontext->firstpage=$firstpage;
       $xcontext->lastpage=$lastpage;
       $xcontext->prevpage=$prevpage;
       $xcontext->nextpage=$nextpage;
       $xcontext->maxpage=$maxpage;//最大页
       $xcontext->nowpage=$nowpage;//当前页
       $xcontext->num=$num;//条数
       return XNext::useTpl("allinfo.html");
	}
}
//ajax请求部门数据
class Action_selname extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$list=XDao::query("Etc_classQuery")->listclass();
		echo json_encode($list);
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_BASIC);
    }
}
//修改部门
class Action_upsel extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{	
		$bid=intval($request->bid);
		$pid=intval($request->pid);
		//修改部门
		$row=XDao::dwriter("Etc_peopleinfoWriter")->updatesel($bid,$pid);
		if($row){
			echo "修改成功";
		}	
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//管理部门
class Action_changeclass extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
	    $list=XDao::query("Etc_classQuery")->listclass();
		$xcontext->list=$list;
        return XNext::useTpl("changeclass.html");
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_BASIC);
    }
}
//添加部门
class Action_addclass extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       $classname=htmlspecialchars($request->cname);
       $cid=Etc_classSvc::ins()->addc($classname);
         if($cid){
         	 echo $cid;
         }else{
         	 echo "no";
         }
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//删除部门
class Action_delclass extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$cid=intval($request->cid);
		$count=XDao::query(Etc_peopleinfoQuery)->count($cid);
        //几个人
        $num=$count['num'];
        //如果大于0说名此部门下有人
        if($num>0){
        	echo "请先转移此部门下的员工档案";
        }else{
      		$row=XDao::dwriter("Etc_classWriter")->deleteclass($cid);
			if($row){
				echo "删除成功";
			}else{
				echo "删除失败";
			}
        }
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//部门重命名
class Action_changename extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$classname=htmlspecialchars($request->classname);
		$cid=intval($request->cid);
		$row=XDao::dwriter("Etc_classWriter")->updatename($classname,$cid);
		if($row){
			echo "yes";
		}else{
			echo "no";
		}
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}

//岗位管理
class Action_showjob extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		//部门id
		$cid=intval($request->cid);
		$bname=htmlspecialchars($request->bname);
        $list = XDao::query("Etc_jobQuery")->listjob($cid);
        $xcontext->list=$list;
        $xcontext->bname=$bname;
        $xcontext->cid=$cid;
        return XNext::useTpl("showjob.html");
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//添加岗位
class Action_addjob extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       $jobname=htmlspecialchars($request->jname);
       $cid = intval($request->cid);
       $jid=Etc_jobSvc::ins()->addjob($jobname,$cid);
         if($jid){
         	 echo $jid;
         }else{
         	 echo "no";
         }
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//删除岗位
class Action_deljob extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       $jid = intval($request->jid);
       $row=XDao::dwriter("Etc_jobWriter")->deletejob($jid);
         if($row){
         	 echo "删除成功";
         }else{
         	 echo "no";
         }
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//修改岗位名称
class Action_changejob extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       $jid = intval($request->jid);
       $jobname=htmlspecialchars($request->jobname);
       $jid=Etc_jobSvc::ins()->changejob($jobname,$jid);
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//ajax遍历岗位
class Action_setjob extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
	   $pid = intval($request->pid);
	   $row=XDao::query("Etc_stationQuery")->selclass($pid);
       $bid = $row['bid'];

       if($bid){
       	  $list=XDao::query("Etc_stationQuery")->listjob($bid);
       }else{
       	  //拿到所有的岗位
       	  $list1 = XDao::query("Etc_stationQuery")->listjob1();
       	   echo json_encode($list1);
       }
       
       if($list){
   	       echo json_encode($list);
       }

	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_BASIC);
    }
}
//更改岗位
class Action_upjob extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       $jid = intval($request->jid);
       $pid = intval($request->pid);
       $row=XDao::dwriter("Etc_workWriter")->updatejob($jid,$pid);
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//查看详细信息
class Action_oneinfo extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{   
		$id=intval($request->id);//人员id
		//基本信息
        $row=XDao::query("Etc_peopleinfoQuery")->rowinfo($id);
        $array[0]['info']=$row;

        //技能
        $skill=XDao::query("Etc_peopleinfoQuery")->listskill($id);
        $array[0]['skill']=$skill;

        //工作经验
        $exper=XDao::query("Etc_peopleinfoQuery")->listexper($id);
        $array[0]['exper']=$exper;

        //备注
        $remark=XDao::query("Etc_peopleinfoQuery")->listremark($id);
        $array[0]['remark']=$remark;
        $xcontext->row=$array;

        // //日志记录开始
        $date=date("Y-m-d H:i:s",time());
        //用户名
        $userid = intval($_SESSION['uid']);
        //被查看的人名      
        $peoplename =$row['name'];
        
        $logcontent="查看了".$peoplename."的信息";
        
        $type = 1;

        $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);
        
        return XNext::useTpl("oneinfo.html");
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_BASIC);
    }
}
//修改员工信息
class Action_updateinfo extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$info = $request->attr;	
		$pid=intval($info['id']);
		XDao::dwriter("Etc_mixWriter")->delskill($pid);
		XDao::dwriter("Etc_mixWriter")->delexper($pid);
		XDao::dwriter("Etc_mixWriter")->delrewark($pid);

			//更改一个人的技能
		    if(!empty($info['skillname'])){
			    foreach($info['skillname'] as $v){
			    	 $row=Etc_skillSvc::ins()->addskill(htmlspecialchars($v),intval($pid));
			    }
			}
		    //更改一个人的工作经验
		    if(!empty($info['expercontent']) && !empty($info['sotime'])){
		    	$list=$info['expercontent'];
		    	$sotime=$info['sotime'];
		    	$array=array();
			    foreach($list as $k => $v){
                     foreach($sotime as $k1 => $v1){
                     	if($k == $k1){
                     		$array['sotime']=htmlspecialchars($v1);
                     		$array['expercontent']=htmlspecialchars($v);
                     	}
                     }
                     $row=Etc_experienceSvc::ins()->addexper($pid,$array['sotime'],$array['expercontent']);
		    	}
		    }

		     //添加一个人的备注
 		     if(!empty($info['remark'])){
			    foreach($info['remark'] as $v2){
			    	 $row=RemarksSvc::ins()->addremark(htmlspecialchars($v2),intval($pid));
			    }
			 }
		$result=Etc_peopleinfoSvc::ins()->updateinfo($info,$pid);

  		if($result){
  			echo "yes";
          //日志记录开始
          $date=date("Y-m-d H:i:s",time());
          //用户名
          $userid = intval($_SESSION['uid']);
          //被查看的人名      
          $peoplename=htmlspecialchars($info['name']);
          
          $logcontent="修改了".$peoplename."的资料";
          
          $type = 1;

          $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);
  		}
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//删除一个人
class Action_delone extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$id=intval($request->id);
		$row=XDao::dwriter("Etc_peopleinfoWriter")->delone($id);
		if($row){
      echo "yes";
      //日志记录开始
      $namerow = XDao::query("Etc_peopleinfoQuery")->rowname($id);
      $peoplename=$namerow['name']; 
      $date=date("Y-m-d H:i:s",time());
      //用户名
      $userid = intval($_SESSION['uid']);
      $logcontent="删除了".$peoplename."的资料";

      $type = 1;

      $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);
		}else{
      echo "no";
    }
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//添加一个人的模版
class Action_addp extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$list=XDao::query("Etc_classQuery")->listclass();
		$xcontext->list=$list;
		return XNext::useTpl("addpeople.html");
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//执行添加
class Action_doadd extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$info=$request->attr;
		$pid=Etc_peopleinfoSvc::ins()->addinfo($info);//人的id
		if($pid){
			//添加一个人的工资
			XDao::dwriter("Etc_wagesWriter")->insertwage($pid);
			//添加一个人的技能
		    if(!empty($info['skillname'])){
			    foreach($info['skillname'] as $v){
			    	 $row=Etc_skillSvc::ins()->addskill(htmlspecialchars($v),intval($pid));
			    }
			}
		    //添加一个人的工作经验
		    if(!empty($info['expercontent']) && !empty($info['sotime'])){
		    	$list=$info['expercontent'];
		    	$sotime=$info['sotime'];
		    	$array=array();
			    foreach($list as $k => $v){
                     foreach($sotime as $k1 => $v1){
                     	if($k == $k1){
                     		$array['sotime']=$v1;
                     		$array['expercontent']=$v;
                     	}
                     }
                     $row=Etc_experienceSvc::ins()->addexper(intval($pid),htmlspecialchars($array['sotime']),htmlspecialchars($array['expercontent']));
		    	}
		    }

		     //添加一个人的备注
 		     if(!empty($info['remark'])){
			    foreach($info['remark'] as $v2){
			    	 $row=RemarksSvc::ins()->addremark(htmlspecialchars($v2),intval($pid));
			    }
			 }
        }else{
        	echo "no";
        }
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}


class Action_kaoqin extends XAction
{
	public function _run($request,$xcontext)
	{
		$cid=intval($request->cid);

        $info=$request->attr;
	    $name=$info['name'];
    if(!empty($name)){
       $count=XDao::query("Etc_peopleinfoQuery")->countinfo3($cid,$name);
    }else{
       $count=XDao::query("Etc_peopleinfoQuery")->countinfo2($cid);
    }   	
        $num=$count['num'];//总条数
	    $pagesize=8; //一页显示多少条
	    $page = new Page($num,$pagesize); //实例化
	    $maxpage=$page->checkMaxPage(); //最大页数
	    $nowpage=$page->page; //当前第几页 
	    $start=intval(explode(",",$page->limit())[0]);
	    $stop=intval(explode(",",$page->limit())[1]);

   if(!empty($name)){
       $list=XDao::query("Etc_peopleinfoQuery")->classinfo1($cid,$name,$start,$stop);
   }else{
       $list=XDao::query("Etc_peopleinfoQuery")->classinfo($cid,$start,$stop);
   }
	    $firstpage="<a href='{$url}?cid={$cid}&p=1{$where}'>首页</a>";
	    $lastpage="<a href='{$url}?cid={$cid}&p={$maxpage}{$where}'>末页</a>";
	    $prevpage="<a href='{$url}?cid={$cid}&p=".($nowpage-1)."{$where}'>前一页</a>";
	    $nextpage="<a href='{$url}?cid={$cid}&p=".($nowpage+1)."{$where}'>后一页</a>";
		
        $xcontext->firstpage=$firstpage;
        $xcontext->lastpage=$lastpage;
        $xcontext->prevpage=$prevpage;
        $xcontext->nextpage=$nextpage;
        $xcontext->maxpage=$maxpage;//最大页
        $xcontext->nowpage=$nowpage;//当前页
        $xcontext->num=$num;//条数
        $xcontext->cid=$cid;

		$xcontext->list=$list;
		return XNext::useTpl("allkaoqin.html");
	}
}
//日志查看
class Action_showlog extends XAction
{
    public function _run($request,$xcontext)
    {
      $type = intval($request->type);
      $actionname = htmlspecialchars($_GET['actionname']);

      $where = "where etc_userr.id = log.uid and type = $type";

      if(!empty($actionname)){
          $actionnames = " and logcontent like '%$actionname%'";
      }
      $where=$where.$actionnames;
      $count = XDao::query("LogQuery")->countlog($where);

      $num=$count['num'];//总条数
      $pagesize=15; //一页显示多少条

      $page = new Page($num,$pagesize); //实例化
      $maxpage=$page->checkMaxPage(); //最大页数
      $nowpage=$page->page; //当前第几页 
      $start=intval(explode(",",$page->limit())[0]);
      $stop=intval(explode(",",$page->limit())[1]);
      $fenye=$page->show();
      $loglist = XDao::query("LogQuery")->looklog($where,$start,$stop);

      $firstpage="<a href='{$url}?p=1}&type={$type}&actionname={$actionname}'>首页</a>";
      $lastpage="<a href='{$url}?p={$maxpage}&type={$type}&actionname={$actionname}'>末页</a>";
      $prevpage="<a href='{$url}?p=".($nowpage-1)."&type={$type}&actionname={$actionname}'>前一页</a>";
      $nextpage="<a href='{$url}?p=".($nowpage+1)."&type={$type}&actionname={$actionname}'>后一页</a>";

      $xcontext->firstpage=$firstpage;
      $xcontext->prevpage=$prevpage;
      $xcontext->nextpage=$nextpage;
      $xcontext->lastpage=$lastpage;
      
      $xcontext->type=$type;
      $xcontext->maxpage=$maxpage;//最大页
      $xcontext->nowpage=$nowpage;//当前页
      $xcontext->num=$num;//条数
      $xcontext->loglist=$loglist;
      return XNext::useTpl("showlog.html");
    }
}

//上传头像
class Action_uploadphoto extends XAction
{
    public function _run($request,$xcontext)
    {
        $uid = intval($_POST['uid']);
        $tmp_file = $_FILES['upload']['tmp_name'];
        $tmp_name = "hr/photo/".md5(time().rand(1,250));
        if(is_uploaded_file($tmp_file)){
            $qn = BridgeSvc::ins()->callbridge($tmp_file,$tmp_name);
            $row=XDao::dwriter("Etc_peopleinfoWriter")->changephoto($tmp_name,$uid);

            if($row){
                return XNext::gotourl($_SERVER['DOMAIN'].'/peopleinfo.php');
            }
          }else{
             echo "上传方式不对!";
          }
    }
}
//查看附件
class Action_showadnexa extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $pname=htmlspecialchars($request->name);
    $pid=intval($request->id);
    $list = XDao::query("etc_peopleinfoQuery")->show_adnexa($pid);
    $arra=array();
    foreach($list as $k => $v){
       $list[$k]['fix']=pathinfo($v['adnexaname'])['extension'];
    }
    $xcontext->list=$list;
    $xcontext->pid=$pid;
    $xcontext->pname=$pname;
    return XNext::useTpl("showadnexa.html");
  }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//执行上传附件
class Action_upadnexa extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $pid=intval($request->pid);
    //临时文件名
    $tmp_file = $_FILES['annex']['tmp_name'];
    //上传文件的文件名
    $name = $_FILES['annex']['name'];
    $array=array();
    foreach($name as $k => $v){
        $array[$k]['name']=$v;
        // 后缀
        $array[$k]['fix']=pathinfo($v)[extension];    
    }
    foreach($tmp_file as $key => $value){
        foreach($array as $k1 => $v1){
              if($k1 == $key && is_uploaded_file($value)){
                 $tmp_name = "hr/adnexa/".md5_file($value).time().'.'.$v1[fix];
                 $adnexaname = $v1['name'];
                 BridgeSvc::ins()->callbridge($value,$tmp_name);
                 $row=AdnexaSvc::ins()->insert_adnexa($adnexaname,$tmp_name,$pid);
              }else{
                echo "上传方式不对";
              }
        }
    }
    if($row){
       return XNext::gotourl($_SERVER['DOMAIN'].'/showadnexa.php?id='.$pid);
    }

  }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//删除附件
class Action_deladnexa extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $aid=intval($request->aid);
    $pid=intval($request->pid);
    $row=XDao::dwriter(Etc_peopleinfoWriter)->deladnexa($aid);
    if($row){
      return XNext::gotourl($_SERVER['DOMAIN'].'/showadnexa.php?id='.$pid);
    }
  }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_BASIC);
    }
}
//导出excel
class Action_doexcel extends XAction
{
    public function _run($request,$xcontext)
    {
       $cid = intval($_GET['wageexcel']);
       
       $where = " where del = 1 and etc_wages.id = etc_peopleinfo.id and etc_peopleinfo.bid = etc_class.id";

       if($cid != "0" && $cid != ""){
          $cids = " and etc_peopleinfo.bid = $cid";
       }
       $where = $where.$cids;
       
       $wagelist = XDao::query("OutexcelQuery")->outwage($where);
       $str = "<table border='1px'>";
       $str .= "<tr>";
       $str .= "<th>部门</th>";
       $str .= "<th>姓名</th>";
       $str .= "<th>基本工资</th>";
       $str .= "<th>职位奖金</th>";
       $str .= "</tr>";
       foreach($wagelist as $v){
           $str .= "<tr><td>{$v['class_name']}</td><td>{$v['name']}</td><td>{$v['jwages']}(元)</td><td>{$v['zwages']}(元)</td></tr>";
       }
       $str .= "</table>";
    $str = iconv('utf-8','gb2312',$str);
    if($cid != "0" && $cid != ""){
       $filename = $wagelist[0]['class_name'].'部门工资信息'.date('Ymdhis').'.xls';
    }else{
       $filename = '工资信息'.date('Ymdhis').'.xls';
    }
    $this->exportExcel($filename,$str);
   }
   public function exportExcel($filename,$content){
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/vnd.ms-execl");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Transfer-Encoding: binary");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo $content;
   }
}