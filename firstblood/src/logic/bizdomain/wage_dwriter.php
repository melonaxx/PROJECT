<?php
class Etc_wageWriter extends DWriter
{
	//修改基本工资
    public function upjwage($price,$uid)
    {
        $sql = "update etc_wages set jwages = ? where id = ?";
        return $this->exeByCmd($sql, array($price,$uid));
    }
    //修改职位奖金
    public function upzwage($price,$uid){
        $sql = "update etc_wages set zwages = ? where id = ?";
        return $this->exeByCmd($sql, array($price,$uid));
    }
    //保存一个工资条
    public function savewage($price,$zongjixiao,$agemoney,$gongjijin,$safe,$jiangcheng,$countwages,$yufu,$yingfu,$time,$overtimepay,$chidao,$zaotui,$kuang_gong,$little,$score,$free,$house,$subsidy,$uid)
    {
        $sql = "insert into etc_payslip(price,kpi,agemoney,fund,safe,reward,countwages,ypay,rpay,ctime,overtimepay,late,earlyleave,absent,little,score,toleave,houseprice,subsidyprice,pid) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
         return $this->exeByCmd($sql, array($price,$zongjixiao,$agemoney,$gongjijin,$safe,$jiangcheng,$countwages,$yufu,$yingfu,$time,$overtimepay,$chidao,$zaotui,$kuang_gong,$little,$score,$free,$uid));
    }
    //修改工资条
    public function updatewage($price,$zongjixiao,$agemoney,$gongjijin,$safe,$jiangcheng,$countwages,$yufu,$yingfu,$overtimepay,$chidao,$zaotui,$kuang_gong,$little,$score,$free,$house,$subsidy,$uid,$year,$month)
    {
        $sql = "update etc_payslip set price=?,kpi=?,agemoney=?,fund=?,safe=?,reward=?,countwages=?,ypay=?,rpay=?,overtimepay=?,late=?,earlyleave=?,absent=?,little=?,score=?,toleave=?,houseprice=?,subsidyprice=? where pid=? and year(ctime)=? and month(ctime)=?";
         return $this->exeByCmd($sql, array($price,$zongjixiao,$agemoney,$gongjijin,$safe,$jiangcheng,$countwages,$yufu,$yingfu,$overtimepay,$chidao,$zaotui,$kuang_gong,$little,$score,$free,$house,$subsidy,$uid,$year,$month));
    }
    //删除一个工资条
    public function delslip($id)
    {
    	$sql="delete from etc_payslip where id=?";
    	return $this->exeByCmd($sql,array($id));
    }
    //修改星级
    public function changeheart($xid,$pid)
    {
        $sql="update etc_peopleinfo set xid=? where id = ?";
        return $this->exeByCmd($sql,array($xid,$pid));
    }
    //删除奖惩
    public function delre($id)
    {
        $sql="delete from etc_rewards where id = ?";
        return $this->exeByCmd($sql,array($id));

    }
}