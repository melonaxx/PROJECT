<?php
class Etc_wagesQuery extends Query
{
	//查出部门下所有人的工资
    public function listinfo($cid,$start,$stop)
    {
        $sql = "select etc_peopleinfo.id,jwages,zwages,name,(select heartname from etc_heartclass where etc_heartclass.id = etc_peopleinfo.xid)as heartname from etc_wages,etc_peopleinfo where etc_peopleinfo.id=etc_wages.id and del=1 and etc_peopleinfo.bid = ? order by id asc limit ?,?";
        return $this->listByCmd($sql,array($cid,$start,$stop));
    }
    //有搜索的情况下查工资
    public function listinfo1($cid,$name,$start,$stop)
    {
        $sql = "select etc_peopleinfo.id,jwages,zwages,name,(select heartname from etc_heartclass where etc_heartclass.id = etc_peopleinfo.xid)as heartname from etc_wages,etc_peopleinfo where etc_peopleinfo.id=etc_wages.id and del=1 and etc_peopleinfo.bid = ? and name like ? order by id asc limit ?,?";
        return $this->listByCmd($sql,array($cid,$name,$start,$stop));
    }
    //总的员工数
    public function countinfo($cid)
    {
        $sql = "select count(*)as num from etc_wages,etc_peopleinfo where etc_wages.id=etc_peopleinfo.id and del=1 and etc_peopleinfo.bid=?";
        return $this->getByCmd($sql,array($cid));
    }
    //搜索出来的员工数
    public function countinfo1($name,$cid)
    {
        $sql = "select count(*)as num from etc_wages,etc_peopleinfo where etc_wages.id=etc_peopleinfo.id and del=1 and name like ? and etc_peopleinfo.bid=?";
        return $this->getByCmd($sql,array($name,$cid));
    }
    //查出一个人的工资等相关信息
    public function oneinfo($uid)
    {
    	$sql = "select etc_peopleinfo.id,hiredate,name,class_name,jwages,zwages,addwork,late,earlyleave,absent,sale,stick,workagenum,fund,etc_wages.safe,house,subsidy from etc_peopleinfo,etc_class,etc_wages where etc_peopleinfo.id= ? and etc_peopleinfo.id=etc_wages.id and etc_peopleinfo.bid=etc_class.id";
       return $this->getByCmd($sql,array($uid));
    }
    //工资详细信息
    public function wageinfo($uid)
    {
        $sql = "select * from etc_wages where id =?";
       return $this->getByCmd($sql,array($uid));
    }
    //查出多少天
    public function selday_status($j,$uid,$ryear,$rmonth)
    {
        $sql = "select count(*)as sum from etc_kq where status=? and userid=? and year(date)=? and month(date)=?";
        return $this->getByCmd($sql,array($j,$uid,$ryear,$rmonth));
    }
    //查出一个人的工资条的数量
    public function onecount($uid)
    {
    	$sql = "select count(*)as num from etc_payslip where pid = ?";
       return $this->getByCmd($sql,array($uid));
    }
    //查出一个人的所有工资条
    public function rowsone($uid,$start,$stop)
    {
    	$sql = "select *,(select class_name from etc_class,etc_peopleinfo where etc_class.id=etc_peopleinfo.bid and etc_peopleinfo.id=etc_payslip.pid)as classname,(select name from etc_peopleinfo where etc_peopleinfo.id=etc_payslip.pid)as name,(select phone from etc_peopleinfo where etc_peopleinfo.id=etc_payslip.pid)as phone,(select banknumber from etc_peopleinfo where etc_peopleinfo.id=etc_payslip.pid)as banknumber from etc_payslip where pid=? order by ctime desc limit ?,?";
    	return $this->listByCmd($sql,array($uid,$start,$stop));
    }
    //查出一个月份所有人的工资条
    public function everyone($where,$start,$stop)
    {
        $sql = "select *,etc_payslip.id as payid,(select class_name from etc_class,etc_peopleinfo where etc_class.id=etc_peopleinfo.bid and etc_peopleinfo.id=etc_payslip.pid)as classname from etc_payslip,etc_peopleinfo $where order by ctime desc limit ?,?";
    	return $this->listByCmd($sql,array($start,$stop));
    }
    //导出工资条
    public function outevery($where)
    {
        $sql = "select *,etc_payslip.id as payid,(select class_name from etc_class,etc_peopleinfo where etc_class.id=etc_peopleinfo.bid and etc_peopleinfo.id=etc_payslip.pid)as classname from etc_payslip,etc_peopleinfo $where order by ctime";
        return $this->listByCmd($sql);
    }
    //查出工资条数量
    public function everyonecount($where)
    {
    	$sql = "select count(*)as num from etc_payslip,etc_peopleinfo $where";
    	return $this->getByCmd($sql);
    }
    //查出薪酬总额
    public function countwage($where)
    {
        $sql = "select sum(countwages)as countwagenum,sum(ypay)as ypaynum,sum(rpay)as rpaynum from etc_payslip,etc_peopleinfo $where";
        return $this->getByCmd($sql);
    }
    //查出部门下所有的星级
    public function listheart($cid)
    {
        $sql = "select * from etc_heartclass where cid = ?";
        return $this->listByCmd($sql,array($cid));
    }
    //查出一个人的所有奖惩
    public function selwages($uid)
    {
        $sql = "select * from etc_rewards where pid= ? order by time desc";
        return $this->listByCmd($sql,array($uid));
    }
    //查出一个人罚的钱与奖励的钱
    public function listreward($ryear,$rmonth,$uid)
    {
        $sql = "select sum(acount)as reward from etc_rewards where year(time)=? and month(time)=? and pid=?";
        return $this->getByCmd($sql,array($ryear,$rmonth,$uid));
    }
    //查一个人这个月是否已经计算过上月的工资
    public function selectonce($uid,$year,$month)
    {
        $sql = "select * from etc_payslip where pid=? and year(ctime)=? and month(ctime)=?";
        return $this->getByCmd($sql,array($uid,$year,$month));
    }
    //查出奖惩的原因和金额
    public function because($uid,$ryear,$rmonth)
    {
        $sql = "select * from etc_rewards where pid = ? and year(time)=? and month(time)=?";
        return $this->listByCmd($sql,array($uid,$ryear,$rmonth));
    }
    //记录日志  查出这个人以前的工资
    public function searchwage($uid)
    {
        $sql = "select name,jwages,zwages from etc_wages,etc_peopleinfo where etc_peopleinfo.id=etc_wages.id and etc_wages.id = ?";
        return $this->getByCmd($sql,array($uid));
    }
    //查出时间 和pid
    public function searchslip($id)
    {
        $sql = "select pid,ctime from etc_payslip where id = ?";
        return $this->getByCmd($sql,array($id));
    }
}