<?php
class Action_showkq extends XBaseLoginAction
{
    public function _run($request, $xcontext)
    {
        $name=htmlspecialchars($_GET['name']);
    	$id=intval($_GET['id']);
        // 入职日期
        $hiredate=XDao::query("Etc_kqQuery")->search_hiredate($id);
        //入职年
        $hire_year = date('Y', strtotime($hiredate['hiredate']));
        //入职月
        $hire_month = date('m', strtotime($hiredate['hiredate']));
        //入职日
        $hire_day = date('d', strtotime($hiredate['hiredate']));

		$lastmonth=strtotime("last month");//上月时间戳
    	$year=date('Y',$lastmonth);//上月的年份
    	$month=date('m',$lastmonth);//上月月份
        $prev_day=date('t',$lastmonth);//上月天数

    // if($hire_month < $month)

        if($hire_year == $year && $hire_month == $month){
            $t = $hire_day;
        }else{
            $t=1;
        }

    	$xcontext->year=$year;
    	$xcontext->month=$month;
    	$lmonth=date('Ymd',$lastmonth);
    	$list=XDao::query("Etc_kqQuery")->chakq($id,$lmonth,$lmonth);

    	if($list['count(*)']==0){
    		$shuju['userid']=$id;
    		$shuju['firsttime']="09:00:00";
    		$shuju['lasttime']="18:00:00";
			$writer = XDao::dwriter("DWriter");
	  
			// 开启事务
			$writer->beginTrans();                       
                         
    		for($i=$t;$i<=$prev_day;$i++){
    		    $shuju['date']=$year."-".$month."-".$i;

    		    $shuju['sta']=1;//正常
 			    if (date("w",strtotime($shuju['date']))==0) {
 				    $shuju['sta']=2;//周末
 				}

 			    $a=microtime(true);
      		    Etc_kqSvc::ins()->inkq($shuju);
 			    $b=microtime(true);
      		}

      		$writer->commit();

	    }

    	$result=XDao::query("Etc_kqQuery")->gaoselmon($id,$lmonth,$lmonth);
        
        $array=array();
        for($i=1;$i<=10;$i++){
            $j=(string)$i;
            $day_row=XDao::query("Etc_kqQuery")->sel_status($j,$id,$lmonth,$lmonth);
            foreach($day_row as $k => $v){
                $array['day'.$i]=$v;
            }
        }
        
      //日志记录开始   
      $date=date("Y-m-d H:i:s",time());
      //用户名
      $userid = $_SESSION['uid'];

      $logcontent="查看了".$name."的考勤";

      $type = 3;

      $log=LogSvc::ins()->addlog($date,$userid,$logcontent,$type);

        $xcontext->array=$array;
    	$xcontext->result=$result;
        $xcontext->uid=$id;
        $xcontext->name=$name;
		return XNext::useTpl('kqxq.html');
    }

    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_KAOQIN);
    }
}


class Action_upstatu extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{
		$status=$_POST['status'];
		$date=$_POST['date'];
		$id=intval($_POST['id']);
    	$list=XDao::dwriter("Etc_kqWriter")->upstu($status,$id,$date);
    	echo $list;
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_KAOQIN);
    }
}
class Action_changeholiday extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
        $holiday=$_POST['holiday'];
        $id=intval($_POST['id']);
        $row=XDao::dwriter("Etc_kqWriter")->setholiday($holiday,$id);
        echo $row;
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_KAOQIN);
    }
}
class Action_selmonth extends XBaseLoginAction
{
	public function _run($request,$xcontext)
	{

		$uid=intval($_GET['uid']);
        $name=htmlspecialchars($_GET['name']);
		$month=$_POST['year'].'-'.$_POST['month'].'-'."01";
    	$result=XDao::query("Etc_kqQuery")->gaoselmon($uid,$month,$month);
       
    	$xcontext->result=$result;
        $xcontext->uid=$uid;
        $xcontext->name=$name;
    	$xcontext->year=$_POST['year'];
    	$xcontext->month=$_POST['month'];
		return XNext::useTpl('kqxq.html');
        
	}
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_KAOQIN);
    }
}
class Action_updatetime extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
        $time=$_POST['time'];

        $id=intval($_POST['id']);

        $start_or_stop=$_POST['start_or_stop'];

           
        $row=Etc_kqSvc::ins()->changetime($time,$start_or_stop,$id);
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_KAOQIN);
    }
}
