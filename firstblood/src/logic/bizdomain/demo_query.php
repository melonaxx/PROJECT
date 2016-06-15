<?php

class DemoQuery extends Query
{
    public function listDemo()
    {
        $sql = "select * from demo where id > ?";
        return $this->listByCmd($sql, array(10060));
    }
}
//人员信息表
class Etc_peopleinfoQuery extends Query
{
    public function listinfo($where,$start,$stop)
    {
	//所有员工
        $sql = "select *,etc_peopleinfo.id as peopleid,(select class_name from etc_class where etc_peopleinfo.bid=etc_class.id)as cname,(select jobname from etc_job where etc_job.id=etc_peopleinfo.jid)as jname,(select etc_job.bid from etc_job where etc_job.id=etc_peopleinfo.jid)as bid from etc_peopleinfo $where order by etc_peopleinfo.id desc limit ?,?";
        return $this->listByCmd($sql,array($start,$stop));
    }
    //单个员工
    public function rowinfo($id)
    {
        $sql = "select *,(select class_name from etc_class where etc_peopleinfo.bid=etc_class.id)as cname,(select jobname from etc_job where etc_job.id=etc_peopleinfo.jid)as jname,(select etc_job.bid from etc_job where etc_job.id=etc_peopleinfo.jid)as bid from etc_peopleinfo where id = ?";
        return $this->getByCmd($sql, array($id));
    }
    //查询当前部门下是否有员工
    public function count($cid)
    {
    	$sql = "select count(*)as num from etc_peopleinfo where bid = ? and del = 1";
    	return $this->getByCmd($sql, array($cid));
    }
    //总的员工数
    public function countinfo($where)
    {
        $sql = "select count(*)as num from etc_peopleinfo $where";
        return $this->getByCmd($sql);
    }
    public function listin()
    {
        //所有员工
        $sql = "select *,(select class_name from etc_class where etc_peopleinfo.bid=etc_class.id)as cname from etc_peopleinfo order by id desc";
        return $this->listByCmd($sql);
    }
        //分类
    public function classin($cid)
    {
        $sql = "select etc_peopleinfo.id,name,sex,birth,education,class_name as cname from etc_peopleinfo,etc_class where etc_peopleinfo.bid = etc_class.id and etc_peopleinfo.bid= ? ";
        return $this->listByCmd($sql, array($cid));
    }
         //一个人的技能
    public function listskill($pid)
    {
        $sql = "select * from etc_skill where pid = ?";
        return $this->listByCmd($sql, array($pid));
    }
         //一个人的工作经验
    public function listexper($pid)
    {
        $sql = "select * from etc_experience where pid = ?";
        return $this->listByCmd($sql, array($pid));
    }
         //一个人的备注
    public function listremark($pid)
    {
        $sql = "select * from remarks where pid = ?";
        return $this->listByCmd($sql, array($pid));
    }
    //要删的人的名字
    public function rowname($id)
    {
        $sql = "select name from etc_peopleinfo where id = ?";
        return $this->getByCmd($sql, array($id));
    }


        //按部门来的员工数
    public function countinfo2($cid)
    {
        $sql = "select count(*)as num from etc_peopleinfo where del = 1 and bid=?";
         return $this->getByCmd($sql,array($cid));
    }
        //员工分类遍历员工
    public function classinfo($cid,$start,$stop)
    {
        $sql = "select etc_peopleinfo.id,name,sex,birth,education,class_name as cname from etc_peopleinfo,etc_class where del = 1 and etc_peopleinfo.bid = etc_class.id and etc_peopleinfo.bid= ? limit ?,?";
        return $this->listByCmd($sql, array($cid,$start,$stop));
    }
       //按部门搜索来的员工数
    public function countinfo3($cid,$name)
    {
        $sql = "select count(*)as num from etc_peopleinfo where del = 1 and bid=? and name=?";
         return $this->getByCmd($sql,array($cid,$name));
    }
        //员工按部门搜索出来的员工
    public function classinfo1($cid,$name,$start,$stop)
    {
        $sql = "select etc_peopleinfo.id,name,sex,birth,education,class_name as cname from etc_peopleinfo,etc_class where del = 1 and etc_peopleinfo.bid = etc_class.id and etc_peopleinfo.bid= ? and name=? limit ?,?";
        return $this->listByCmd($sql, array($cid,$name,$start,$stop));
    }
    //查看附件
    public function show_adnexa($pid)
    {
        $sql = "select * from adnexa where pid = ? order by id desc";
        return $this->listByCmd($sql, array($pid));
    }
}

//员工分类表
class Etc_classQuery extends Query
{
	public function listclass()
	{
	   //所有部门
	   $sql = "select * from etc_class order by id asc";
	   return $this->listByCmd($sql);
	}
}
class Etc_userrQuery extends Query
{
    public function denglu($username,$password)
    {
        $sql = "select * from etc_userr where username = ? and password = ?";
        return $this->getByCmd($sql,array($username,$password));
    }
    public function showadmin()
    {
        $sql ="select * from etc_userr";
        return $this->listByCmd($sql);
    }
    //检查密码
    public function checkpass($password,$uid)
    {
        $sql ="select * from etc_userr where password=? and id = ?";
        return $this->getByCmd($sql,array($password,$uid));
    }
}
//岗位
class Etc_jobQuery extends Query
{
    public function listjob($cid)
    {
       //所有部门
       $sql = "select * from etc_job where bid = ? order by id asc";
       return $this->listByCmd($sql,array($cid));
    }
}
//员工星级
class Etc_heartQuery extends Query
{
    public function listheart($cid)
    {
       //所有部门
       $sql = "select * from etc_heartclass where cid=? order by etc_heartclass.id asc";
       return $this->listByCmd($sql,array($cid));
    }
}

//kq
class Etc_kqQuery extends Query
{
    public function chakq($id,$lmonth,$lmonth)
    {
        $sql = "select count(*) from etc_kq where userid=? and year(date)=year(?) and month(date)=month(?)";
        return $this->getByCmd($sql,array($id,$lmonth,$lmonth));
    }
    public function selectkq($id,$monfirst,$monlast)
    {
        $sql = "select * from etc_kq where id=? and date > ? and date < ?";
        return $this->listByCmd($sql,array($id,$monfirst,$monlast));
    }
    public function gaoselmon($id,$month,$month)
    {
        $sql = "select * from etc_kq where userid = ? and year(date)=year(?) and month(date)=month(?)";
        return $this->listByCmd($sql,array($id,$month,$month));
    }
    public function sel_status($i,$id,$lmonth,$lmonth)
    {
        $sql = "select count(*)as sum from etc_kq where status = ? and userid = ? and year(date)=year(?) and month(date)=month(?)";
        return $this->getByCmd($sql,array($i,$id,$lmonth,$lmonth));
    }
    //查出入职日期
    public function search_hiredate($id)
    {
        $sql = "select hiredate from etc_peopleinfo where id = ?";
        return $this->getByCmd($sql,array($id));
    }
}

//角色查看
class Etc_roleQuery extends Query
{
    public function showrole()
    {
       //所有部门
       $sql = "select * from etc_role";
       return $this->listByCmd($sql);
    }
    //角色已分配查看
    public function havaauth($uid)
    {
       $sql = "select * from etc_associated where uid = ?";
       return $this->listByCmd($sql,array($uid));
    }
    //角色权限已分配查看
    public function checkauth($rid)
    {
       $sql = "select auth from etc_role where id = ?";
       return $this->getByCmd($sql,array($rid));
    }
    //一个人有的角色
    public function onerole($uid)
    {
       $sql = "select jid from etc_associated where uid = ?";
       return $this->listByCmd($sql,array($uid));
    }
}

//部门下所有的岗位
class Etc_stationQuery extends Query
{
    //查出此人所在的部门
    public function selclass($pid)
    {
       $sql = "select bid from etc_peopleinfo where id = ?";
       return $this->getByCmd($sql,array($pid));
    }
    public function listjob($bid)
    {
       //所有部门
       $sql = "select * from etc_job where bid = ?";
       return $this->listByCmd($sql,array($bid));
    }
    public function listjob1()
    {
       //所有部门
       $sql = "select * from etc_job order by bid";
       return $this->listByCmd($sql);
    }
}

//要生日的和合同要到期的
class Etc_pactbirthQuery extends Query
{
    public function selpactover($where1)
    {
       $sql = "select name,pactover from etc_peopleinfo $where1";
       return $this->listByCmd($sql);
    }
    public function selbirth($where)
    {

       $sql = "select name,birth from etc_peopleinfo $where";
       return $this->listByCmd($sql);
    }
}

//遍历日志
class LogQuery extends Query
{
    //人员日志条数
    public function countlog($where)
    {
       $sql = "select count(*)as num from log,etc_userr $where";
       return $this->getByCmd($sql);
    }
    //查看人员日志
    public function looklog($where,$start,$stop)
    {
       $sql = "select * from log,etc_userr $where order by happentime desc limit ?,?";
       return $this->listByCmd($sql,array($start,$stop));
    }
}

//导出excel
class OutexcelQuery extends Query
{
    //工资
    public function outwage($where)
    {
       $sql = "select jwages,zwages,name,class_name from etc_wages,etc_peopleinfo,etc_class $where";
       return $this->listByCmd($sql);
    }
}