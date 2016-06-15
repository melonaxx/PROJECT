<?php

class DemoWriter extends DWriter
{
    public function updateDemo()
    {
        $sql = "update demo set name='imposible' where id > ?";
        return $this->exeByCmd($sql, array(10060));
    }
}
class Etc_peopleinfoWriter extends DWriter
{
    //删除
    public function delone($id)
    {
        $sql = "update etc_peopleinfo set del=0 where id=?";
        return $this->exeByCmd($sql, array($id));
    }
    //更改部门
    public function updatesel($bid,$pid)
    {
        $sql = "update etc_peopleinfo set bid=? where id=?";
        return $this->exeByCmd($sql, array($bid,$pid));
    }
    public function changephoto($hash,$uid)
    {
        $sql = "update etc_peopleinfo set photo=? where id=?";
        return $this->exeByCmd($sql, array($hash,$uid));
    }
    //删除附件
    public function deladnexa($aid)
    {
        $sql = "delete from adnexa where id=?";
        return $this->exeByCmd($sql, array($aid));
    }
}
class Etc_classWriter extends DWriter
{
    //修改部门名称
    public function updatename($classname,$cid)
    {
        $sql = "update etc_class set class_name=? where id = ?";
        return $this->exeByCmd($sql, array($classname,$cid));
    }
    //删除部门
    public function deleteclass($cid)
    {
        $sql = "delete from etc_class where id = ?";
        return $this->exeBycmd($sql,array($cid));
    }
}
//星级
class Etc_heartWriter extends DWriter
{
    //星级重命名
    public function updatename($name,$id)
    {
        $sql = "update etc_heartclass set heartname=? where id=?";
        return $this->exeBycmd($sql,array($name,$id));
    }
    //星级删除
    public function delheart($id)
    {
        $sql = "delete from etc_heartclass where id = ?";
        return $this->exeBycmd($sql,array($id));
    }
    //修改最低薪资
    public function uplowpay($price,$id)
    {
        $sql = "update etc_heartclass set lowpay=? where id=?";
        return $this->exeBycmd($sql,array($price,$id));
    }
    //修改最高薪资
    public function uphighpay($price,$id)
    {
        $sql = "update etc_heartclass set highpay=? where id=?";
        return $this->exeBycmd($sql,array($price,$id));
    }
}

class Etc_jobWriter extends DWriter
{
    //删除岗位
    public function deletejob($jid)
    {
        $sql = "delete from etc_job where id = ?";
        return $this->exeByCmd($sql, array($jid));
    }
}

class Etc_wagesWriter extends DWriter
{
    //关联工资表的id
    public function insertwage($pid)
    {
        $sql = "insert into etc_wages(id) values(?);";
        return $this->exeBycmd($sql,array($pid));
    }
    //删除人的同时删除这个人的工资信息
    public function delwage($id){
        $sql = "delete from etc_wages where id = ?";
        return $this->exeBycmd($sql,array($id));
    }
}
//更改密码
class Etc_userrWriter extends DWriter
{
    //更改admin密码
    public function uppass($password,$id)
    {
        $sql = "update etc_userr set password=? where id=?";
        return $this->exeByCmd($sql, array($password,$id));
    }
    //删除admoin
    public function del($id)
    {
        $sql = "delete from etc_userr where id = ?";
        return $this->exeBycmd($sql,array($id));
    }
}
//kq
class Etc_kqWriter extends DWriter
{
    //修改状态
    public function upstu($status,$id,$date)
    {
        $sql = "update etc_kq set status = ? where id= ? and date = ?";
        return $this->exeBycmd($sql,array($status,$id,$date));
    }
   //修改假期
    public function setholiday($holiday,$id)
    {
        $sql = "update etc_kq set holiday = ? where id= ?";
        return $this->exeBycmd($sql,array($holiday,$id));
    }
}
//删除角色
class Etc_soleWriter extends DWriter
{
    //修改状态
    public function delrole($rid)
    {
        $sql = "delete from etc_role where id = ?";
        return $this->exeBycmd($sql,array($rid));
    }
    //删除角色权限
    public function delauthrole($uid)
    {
        $sql = "delete from etc_associated where uid = ?";
        return $this->exeBycmd($sql,array($uid));
    }
}
//岗位修改
class Etc_workWriter extends DWriter
{
    //修改
    public function updatejob($jid,$pid)
    {
        $sql = "update etc_peopleinfo set jid = ? where id= ?";
        return $this->exeBycmd($sql,array($jid,$pid));
    }
}

// 删除修改技能 备注 经验
class Etc_mixWriter extends DWriter
{
    //删除技能
    public function delskill($pid)
    {
        $sql = "delete from etc_skill where pid = ?";
        return $this->exeBycmd($sql,array($pid));
    }
    //删除工作经验
    public function delexper($pid)
    {
        $sql = "delete from etc_experience where pid = ?";
        return $this->exeBycmd($sql,array($pid));
    }
    //删除备注
    public function delrewark($pid)
    {
        $sql = "delete from remarks where pid = ?";
        return $this->exeBycmd($sql,array($pid));
    }
}