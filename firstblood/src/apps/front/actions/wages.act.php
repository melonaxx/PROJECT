<?php
session_start();
//工资板块
class Action_show extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
     $bname=htmlspecialchars($request->bname);
	   $cid=intval($request->cid);
       //如果有搜索
 	   $info=$request->attr;
     $name=htmlspecialchars($info['name']);
     $where = "where del=1";
    if(!empty($name)){
       $count=XDao::query("Etc_wagesQuery")->countinfo1($name,$cid);
    }else{
	     $count=XDao::query("Etc_wagesQuery")->countinfo($cid);
    }
	   $num=$count['num'];//总条数
	   $pagesize=8; //一页显示多少条
	   $page = new Page($num,$pagesize); //实例化
	   $maxpage=$page->checkMaxPage(); //最大页数
	   $nowpage=$page->page; //当前第几页   
	   $firstpage="<a href='{$url}?cid={$cid}&p=1{$where}'>首页</a>";
	   $lastpage="<a href='{$url}?cid={$cid}&p={$maxpage}{$where}'>末页</a>";
	   $prevpage="<a href='{$url}?cid={$cid}&p=".($nowpage-1)."{$where}'>前一页</a>";
	   $nextpage="<a href='{$url}?cid={$cid}&p=".($nowpage+1)."{$where}'>后一页</a>";
	   $start=intval(explode(",",$page->limit())[0]);
	   $stop=intval(explode(",",$page->limit())[1]);
   if(!empty($name)){
       $rows=XDao::query("Etc_wagesQuery")->listinfo1($cid,$name,$start,$stop);
   }else{
       $rows=XDao::query("Etc_wagesQuery")->listinfo($cid,$start,$stop);
   }

    // //日志记录开始
        $date=date("Y-m-d H:i:s",time());
        //用户名
        $userid = intval($_SESSION['uid']);
        
        $logcontent="查看了".$bname."部门的工资信息";
        
        $type = 2;

        $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);



       $xcontext->firstpage=$firstpage;
       $xcontext->lastpage=$lastpage;
       $xcontext->prevpage=$prevpage;
       $xcontext->nextpage=$nextpage;
       $xcontext->maxpage=$maxpage;//最大页
       $xcontext->nowpage=$nowpage;//当前页
       $xcontext->num=$num;//条数
       $xcontext->cid=$cid;
	     $xcontext->list=$rows;
     return XNext::useTpl("wages.html");
	}
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAY);
  }
}
//修改基本工资或者职位奖金
class Action_changewage extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$info=$request->attr;
		$uid=intval($info['uid']);//用户id
		$wage=htmlspecialchars($info['wages']);//用户的基本工资或者是职位奖金字段
		$price=htmlspecialchars($info['price']);//基本工资或者是职位奖金

    //查出这个人以前的工资信息
    $list = XDao::query("Etc_wagesQuery")->searchwage($uid);

    $old_jwages = $list['jwages'];

    $old_zwages = $list['zwages'];

    $peoplename = "<b>".$list['name']."</b>";

		if($wage=="jwages"){
          $row=XDao::dwriter("Etc_wageWriter")->upjwage($price,$uid);
          
                //日志记录开始
                $date=date("Y-m-d H:i:s",time());
                //用户名
                $userid = $_SESSION['uid'];
                $logcontent="将".$peoplename."的基本工资从".$old_jwages."(元)修改为".$price."(元)";

                $type = 2;

                $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);

		}else if($wage=="zwages"){
		      $row=XDao::dwriter("Etc_wageWriter")->upzwage($price,$uid);

                //日志记录开始
                $date=date("Y-m-d H:i:s",time());
                //用户名
                $userid = $_SESSION['uid'];
                $logcontent="将".$peoplename."的职位奖金从".$old_zwages."(元)修改为".$price."(元)";

                $type = 2;

                $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);
		}
  }

  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}
//添加工资
class Action_addwages extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
      $uid=intval($request->uid);
      $cid=intval($request->cid);
	    $list=XDao::query("Etc_wagesQuery")->oneinfo($uid);
      //拿到一个用户的奖惩金额
      $lastmonth=date("Y-m",strtotime("last month"));
      $ryear = explode("-",$lastmonth)[0];
      $rmonth = explode("-",$lastmonth)[1];
      $result=XDao::query("Etc_wagesQuery")->listreward($ryear,$rmonth,$uid);


        $arr=array();
      for($i=1;$i<=10;$i++){
          $j=(string)$i;
          $day_row=XDao::query("Etc_wagesQuery")->selday_status($j,$uid,$ryear,$rmonth);
          foreach($day_row as $k => $v){
              $arr['day'.$i]=$v;
          }
      }

      // 旷工次数
      $absent=$arr['day6'];
      //迟到次数
      $be_leat=$arr['day7'];
      //早退次数
      $early_leave=$arr['day8'];
      //加班次数
      $addwork=$arr['day9'];
      //半天计算
      //上午请假次数
      $sday = $arr['day4'];
      //下午请假次数
      $xday = $arr['day5'];
      //上了多少次半天班
      $longday = ($sday+$xday)*0.5;
      //上了多少次一天班
      $zday=$arr['day1'];
      //上了多少天班
      $day = $longday+$zday;
      //计算工龄 
      $date1=$list['hiredate'];//入职日期
	    $date2=date("Y-m"); //当前年月

      $year1=explode("-",$date1)[0];//入职年
      $month1=explode("-",$date1)[1];//入职月
      $year2=explode("-",$date2)[0];//现在年
      $month2=explode("-",$date2)[1];//现在月
      // 工作了多少个月
      if($date != ""){
        $workmonth=abs(($year2-$year1)*12+($month2-$month1));
      }
      $array=array(
      	     array("uid"=>$list['id'],
      	     	   "name"=>$list['name'],
      	     	   "classname"=>$list['class_name'],
      	     	   "jwages"=>$list['jwages'],
      	     	   "zwages"=>$list['zwages'],
                 "addwork_price"=>$list['addwork'],
                 "late_price"=>$list['late'],
                 "early_leave_price"=>$list['earlyleave'],
                 "absent_price"=>$list['absent'],
                 "sale_price"=>$list['sale'],
                 "stick_price"=>$list['stick'],
                 "workagenum_price"=>$list['workagenum'],
                 "house_price"=>$list['house'],
                 "subsidy_price"=>$list['subsidy'],
                 "fund_price"=>$list['fund'],
                 "safe_price"=>$list['safe'],

      	     	   "workmonth"=>$workmonth,
                 "reward"=>$result['reward'],
                 "day"=>$day,
                 "absent"=>$absent,
                 "be_leat"=>$be_leat,
                 "early_leave"=>$early_leave,
                 "addwork"=>$addwork
                 )
      	     );
      //查出上月奖惩的原因和金额
      $list6 = XDao::query("Etc_wagesQuery")->because($uid,$ryear,$rmonth);
      $xcontext->rows=$array;
      $xcontext->rows2=$list6;
      return XNext::useTpl("addwages.html");
	}
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}
//对工资数据进行处理
class Action_attend extends XAction
{
	public function _run($request,$xcontext)
	{
	  $info = $request->attr;
	  $jiben = $info['jiben'];//基本工资
	  $zhiwei = $info['zhiwei'];//职位奖金
	  $yingdao = $info['yingdao'];//应到天数
	  $shidao = $info['shidao'];//实到天数
	  //谈的工资=(基本工资+职位奖金)/应到天数x实到天数
	  $price=round(($jiben+$zhiwei)/$yingdao*$shidao,2); //price
      //销售提成 销售额乘以提成
	  $xiaoshou = $info['xiaoshou'];//销售额
	  $ticeng = $info['ticeng'];//销售提成
	  $price1=round($xiaoshou*$ticeng/100,2);  //销售提成

      //作图的提成 图数量x单价
	  $pnum=$info['pnum'];//作图数量
	  $danjia=$info['danjia'];//图单价
      $price2 = round($pnum*$danjia,2);       //作图提成
      //评分
      $score=$info['score'];               
      if($score < 90 && $score != ""){
      	 $price3=100;
  	  }else if($score >= 90 && $score <= 95){
      	 $price3=200;
      }else if($score > 95){
  	     $price3=300;
      }

      $zongjixiao=$price1+$price2+$price3;  //总绩效

      //工龄工资=工龄x工龄一月的工资
      $jobage = $info['jobage'];          
      $agemoney = $info['agemoney'];

      $price4=round($jobage*$agemoney,2);    //工龄工资总和

      
      $price5=$info["gongjijin"];         //公积金
      
      $price6=$info['safe'];              //保险
     
      //加班费
      $jiabanfei = $info['jiabanfei']; 
      //加班次数
      $worknum = $info['worknum'];
      
      $overtimepay = $jiabanfei*$worknum;
      //迟到
      $late_price = $info['late']*$info['lateprice'];
      //早退
      $early_leave_price = $info['early_leave']*$info['early_leave_price'];
      //旷工
      $absent_price = $info['absent']*$info['absent_price'];
      //房补
      $house_price =$info['fangbu'];
      //补贴
      $subsidy_price =$info['butie'];
      //请假
      $qingjia=$info['qingjia'];

      //微调
      $weitiao=$info['weitiao'];
      
      //评分
      $score = $info['score'];

      //奖惩
      $jiangcheng=$info['jiangcheng'];
      //薪酬合计
      $price8=$price+$zongjixiao+$price4-$price5-$price6+$jiangcheng+$overtimepay-$late_price-$early_leave_price-$absent_price+$qingjia+$weitiao+$house_price+$subsidy_price;
      //预付薪资
      $price9=$info['yufu'];

      //应付薪资
      $yingfu=$price8-$price9;
      $time = date("Y-m-d",strtotime("last month"));
      $wages = array(
      	       "price"=>$price,
      	       "zongjixiao"=>$zongjixiao,
      	       "agemoney"=>$price4,
      	       "gongjijin"=>-$price5,
      	       "safe"=>-$price6,
      	       "jiangcheng"=>$jiangcheng,
      	       "countwages"=>$price8,
      	       "yufu"=>$price9,
      	       "yingfu"=>$yingfu,
      	       "time"=>$time,
               "overtimepay"=>$overtimepay,
               "late_price"=>-$late_price,
               "early_leave_price"=>-$early_leave_price,
               "absent_price"=>-$absent_price,
               "toleave"=>$qingjia,
               "little"=>$weitiao,
               "score"=>$score,
               "house_price"=>$house_price,
               "subsidy_price"=>$subsidy_price
      	       );
	  echo json_encode($wages);
	}
}
//保存工资条
class Action_keepwages extends XAction
{
	public function _run($request,$xcontext)
	{
         $wageinfo =$request->attr;
         $price = htmlspecialchars($wageinfo['price']);
         $zongjixiao = htmlspecialchars($wageinfo['zongjixiao']);
         $agemoney = htmlspecialchars($wageinfo['agemoney']);
         $gongjijin = htmlspecialchars($wageinfo['gongjijin']);
         $safe = htmlspecialchars($wageinfo['safe']);
         $jiangcheng = htmlspecialchars($wageinfo['jiangcheng']);
         $countwages = htmlspecialchars($wageinfo['countwages']);
         $yufu = htmlspecialchars($wageinfo['yufu']);
         $yingfu = htmlspecialchars($wageinfo['yingfu']);
         $time = htmlspecialchars($wageinfo['time']);
         $uid = htmlspecialchars($wageinfo['uid']);
         $overtimepay = htmlspecialchars($wageinfo['overtimepay']);
         $chidao = htmlspecialchars($wageinfo['chidao']);
         $zaotui = htmlspecialchars($wageinfo['zaotui']);
         $kuang_gong = htmlspecialchars($wageinfo['kuang_gong']);
         $little = htmlspecialchars($wageinfo['little']);
         $score = htmlspecialchars($wageinfo['now_score']);
         $free = htmlspecialchars($wageinfo['free']);
         $house = htmlspecialchars($wageinfo['house']);
         $subsidy = htmlspecialchars($wageinfo['subsidy']);
         $lastmonth=date("Y-m",strtotime("last month"));
       $year = explode("-",$lastmonth)[0];
       $month = explode("-",$lastmonth)[1];
       //查出是否已经有一条工资
       $list = XDao::query("Etc_wagesQuery")->selectonce($uid,$year,$month);
       if($list==null){
          $row=XDao::dwriter("Etc_wageWriter")->savewage($price,$zongjixiao,$agemoney,$gongjijin,$safe,$jiangcheng,$countwages,$yufu,$yingfu,$time,$overtimepay,$chidao,$zaotui,$kuang_gong,$little,$score,$free,$house,$subsidy,$uid);
       }else{
          $row=XDao::dwriter("Etc_wageWriter")->updatewage($price,$zongjixiao,$agemoney,$gongjijin,$safe,$jiangcheng,$countwages,$yufu,$yingfu,$overtimepay,$chidao,$zaotui,$kuang_gong,$little,$score,$free,$house,$subsidy,$uid,$year,$month);
       }
       if($row){
       	  echo "yes";
       }else{
       	  echo "no";
       }
	}
}
//加载工资条
class Action_showwages extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
       //员工的id号
    $uid = intval($request->uid);
    $count=XDao::query("Etc_wagesQuery")->onecount($uid);
      //总条数
    $num=$count['num'];
      //一页显示多少条
    $pagesize=15; 
      //实例化
    $page = new Page($num,$pagesize); 
      //最大页数
    $maxpage=$page->checkMaxPage();
      //当前第几页 
    $nowpage=$page->page;
      //第几条开始
    $start=intval(explode(",",$page->limit())[0]);

    $stop=intval(explode(",",$page->limit())[1]);

     $firstpage="<a href='{$url}?uid={$uid}&p=1{$where}'>首页</a>";
     $lastpage="<a href='{$url}?uid={$uid}&p={$maxpage}{$where}'>末页</a>";
     $prevpage="<a href='{$url}?uid={$uid}&p=".($nowpage-1)."{$where}'>前一页</a>";
     $nextpage="<a href='{$url}?uid={$uid}&p=".($nowpage+1)."{$where}'>后一页</a>";
     
     $list=XDao::query("Etc_wagesQuery")->rowsone($uid,$start,$stop);

       $xcontext->firstpage=$firstpage;
       $xcontext->lastpage=$lastpage;
       $xcontext->prevpage=$prevpage;
       $xcontext->nextpage=$nextpage;
       $xcontext->maxpage=$maxpage;//最大页
       $xcontext->nowpage=$nowpage;//当前页
       $xcontext->num=$num;//条数
		   $xcontext->list=$list;
		return XNext::useTpl("showslip.html");
	}
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}
//删除一个工资条
class Action_delslip extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$id=intval($request->id);//工资条的id

      $list = XDao::query("Etc_wagesQuery")->searchslip($id);

      $time = $list['ctime'];

      $pid = $list['pid'];

      $namerow = XDao::query("Etc_peopleinfoQuery")->rowname($pid);

      //日志记录开始
      $peoplename = htmlspecialchars($namerow['name']); 
      $date=date("Y-m-d H:i:s",time());
      //用户名
      $userid = $_SESSION['uid'];

      $logcontent="删除了".$peoplename.$time."的工资条";

      $type = 2;

      $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);

		$row=XDao::dwriter("Etc_wageWriter")->delslip($id);
        if($row){
        	echo "yes";
        }else{
        	echo "no";
        }
	}
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}
//按月份查看所有的薪资
class Action_lookslip extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
    $info=$request->attr;
    $year=htmlspecialchars($info['syear']);
    $month=htmlspecialchars($info['smonth']);
    $name=htmlspecialchars($info['name']);
    $bid=intval($info['bid']);
    
    if(!empty($year)){
       $years = " and year(ctime)='$year'";
    }
    if(!empty($month)){
       $months = " and month(ctime)='$month'";
    }
    if(!empty($name)){
       $names = " and name like '%$name%'";
    }
    if(!empty($bid)){
       $bids = " and bid = $bid";
    }
   if(!empty($year) || !empty($month) || !empty($name) || !empty($bid)){
      $where = " where del = 1 and etc_peopleinfo.id=etc_payslip.pid";
      $where .= $years.$months.$names.$bids;  
   
    $count=XDao::query("Etc_wagesQuery")->everyonecount($where);
      // 总条数
    $num=$count['num'];
      //一页显示多少条
    $pagesize=30; 
      //实例化
    $page = new Page($num,$pagesize); 
      //最大页数
    $maxpage=$page->checkMaxPage();
      //当前第几页 
    $nowpage=$page->page;
      //第几条开始
    $start=intval(explode(",",$page->limit())[0]);

    $stop=intval(explode(",",$page->limit())[1]);
     $address="";
     $fuhao = "&";
     foreach($_GET as $k=>$v){
              if($k !="do" && $k != "p" && $v != "lookslip"){
            $str = $k."=".$v.$fuhao;
            }
         $address.=$str;
       }

    $address = substr($address,0,strlen($address)-1); 
    $prevpage="<a href='{$url}?p=".($nowpage-1)."&{$address}'>前一页</a>";
    $nextpage="<a href='{$url}?p=".($nowpage+1)."&{$address}'>后一页</a>";
    $firstpage="<a href='{$url}?p=1&{$address}'>首页</a>";
    $lastpage="<a href='{$url}?p={$maxpage}&{$address}'>末页</a>";
    $list=XDao::query("Etc_wagesQuery")->everyone($where,$start,$stop);
    $wagesrow=XDao::query("Etc_wagesQuery")->countwage($where);
    }
       $xcontext->sumwages=$wagesrow;
       $xcontext->firstpage=$firstpage;
       $xcontext->lastpage=$lastpage;
       $xcontext->prevpage=$prevpage;
       $xcontext->nextpage=$nextpage;
       $xcontext->maxpage=$maxpage;//最大页
       $xcontext->nowpage=$nowpage;//当前页
       $xcontext->num=$num;//条数

    $xcontext->list=$list;
    $xcontext->year=$year;
    $xcontext->month=$month;
		return XNext::useTpl("lookallslip.html");
	}
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAY);
  }
}
//星级修改
class Action_selheart extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $cid=intval($request->cid);//部门的id
    $list=XDao::query("Etc_wagesQuery")->listheart($cid);
    echo json_encode($list);
  }
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}
class Action_upheart extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $pid=intval($request->pid);//人的id
    $xid=intval($request->xid);//星级id
    $row=XDao::dwriter("Etc_wageWriter")->changeheart($xid,$pid);
  }
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}

//工资详情
class Action_wagesinfo extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $uid=intval($request->uid);//人的id
    $name=htmlspecialchars($request->name);
    $row=XDao::query("Etc_wagesQuery")->wageinfo($uid);
    $xcontext->uid=$uid;
    $xcontext->list=$row;
    $xcontext->name=$name;
    return XNext::useTpl("wagesdetails.html");
  }
  
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}
//执行修改
class Action_updatewageinfo extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $wagedetail =$request->attr; 
    $id=intval($wagedetail['pid']);

    $row=Etc_wagesSvc::ins()->updatewage($wagedetail,$id);
  
    if($row){
        return XNext::gotourl($_SERVER['DOMAIN'].'/wagesinfo.php?uid='.$id."&name=".$wagedetail['name']);
    }
  }
  
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}

//奖惩
class Action_rewards extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $uid=intval($request->uid);//人的id
    $name=htmlspecialchars($request->name);
    $row=XDao::query("Etc_wagesQuery")->selwages($uid);
    $xcontext->uid=$uid;
    $xcontext->list=$row;
    $xcontext->name=$name;
    return XNext::useTpl("lookrewards.html");
  }

  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}
//添加奖惩
class Action_addreward extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $rows=$request->attr;//
    $id=Etc_rewardsSvc::ins()->insertinfo($rows);
      if($id){
        echo $id;
      }else{
        echo "no";
      }
  }
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}
//删除单条奖惩
class Action_delreward extends XBaseLoginAction
{
  public function _run($request,$xcontext)
  {
    $id=intval($request->id);//
    $row=XDao::dwriter("Etc_wageWriter")->delre($id);
      if($row){
        echo "yes";
      }else{
        echo "no";
      }
  }
  public function getPermission()
  {
      // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
      return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAY);
  }
}

//导出excel
class Action_outwage extends XAction
{
  public function _run($request,$xcontext)
  {
      $info=$request->attr;
      $year=$info['syear'];
      $month=$info['smonth'];
      $name=$info['name'];
      $bid=$info['bid'];
      if(!empty($year)){
         $years = " and year(ctime)='$year'";
      }
      if(!empty($month)){
         $months = " and month(ctime)='$month'";
      }
      if(!empty($name)){
         $names = " and name like '%$name%'";
      }
      if(!empty($bid)){
         $bids = " and bid = $bid";
      }

   if(!empty($year) || !empty($month) || !empty($name) || !empty($bid)){
      $where = " where del = 1 and etc_peopleinfo.id=etc_payslip.pid";
      $where .= $years.$months.$names.$bids;  

     $list=XDao::query("Etc_wagesQuery")->outevery($where);
     $wagesrow=XDao::query("Etc_wagesQuery")->countwage($where);

      $str = "<table border='1px' style='padding:5px'>";
      $str .= "<tr>";
      $str .= "<th>姓名</th>";
      $str .= "<th>部门</th>";
      $str .= "<th>时间</th>";
      $str .= "<th>工资</th>";                                
      $str .= "<th>加班费</th>";
      $str .= "<th>迟到</th>";
      $str .= "<th>早退</th>";
      $str .= "<th>旷工</th>";
      $str .= "<th>总绩效</th>";
      $str .= "<th>工龄总工资</th>";
      $str .= "<th>公积金</th>";
      $str .= "<th>保险</th>";
      $str .= "<th>房补</th>";
      $str .= "<th>补贴</th>";
      $str .= "<th>奖惩</th>";
      $str .= "<th>薪酬合计</th>";
      $str .= "<th>预付</th>";
      $str .= "<th>应付</th>";
      $str .= "<th>银行卡(支付宝)</th>";
      $str .= "<th>电话号</th>";
      $str .= "</tr>";
       foreach($list as $v){
           $str .= "<tr>";
           $str .= '<td>'.$v['name'].'</td>';
           $str .= '<td>'.$v['classname'].'</td>';
           $str .= '<td>'.$v['ctime'].'</td>';
           $str .= '<td>'.$v['price'].'</td>';
           $str .= '<td>'.$v['overtimepay'].'</td>';
           $str .= '<td>'.$v['late'].'</td>';
           $str .= '<td>'.$v['earlyleave'].'</td>';
           $str .= '<td>'.$v['absent'].'</td>';
           $str .= '<td>'.$v['kpi'].'</td>';
           $str .= '<td>'.$v['agemoney'].'</td>';
           $str .= '<td>'.$v['fund'].'</td>';
           $str .= '<td>'.$v['safe'].'</td>';
           $str .= '<td>'.$v['houseprice'].'</td>';
           $str .= '<td>'.$v['subsidyprice'].'</td>';
           $str .= '<td>'.$v['reward'].'</td>';
           $str .= '<td>'.$v['countwages'].'</td>';
           $str .= '<td>'.$v['ypay'].'</td>';
           $str .= '<td>'.$v['rpay'].'</td>';
           $str .= '<td>'.$v['banknumber'].'</td>';
           $str .= '<td>'.$v['phone'].'</td>';
           $str .= "</tr>";
       }
           $str .="<tr>";
           $str .="<td>薪酬合计:".$wagesrow['countwagenum']."</td>";
           $str .="<td>预付合计:".$wagesrow['ypaynum']."</td>";
           $str .="<td>应付合计:".$wagesrow['rpaynum']."</td>";
           $str .= "</tr>";
       $str .= "</table>";
      $str = iconv('utf-8','gb2312',$str);
      $filename ='工资信息'.date('Ymdhis').'.xls';
      $this->exportExcel($filename,$str);
    }
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
