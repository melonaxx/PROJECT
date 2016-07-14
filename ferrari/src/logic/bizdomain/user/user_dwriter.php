<?php
class UserWriter extends DWriter
{
	//修改用户状态为删除
    public function delstaff($id)
    {
        $sql = "update user set status = 'S' where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
    //修改用户状态为停用
    public function stopstaff($id)
    {
        $sql = "update user set status = 'T' where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
    //修改用户状态为正常
    public function startstaff($id)
    {
        $sql = "update user set status = 'Z' where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
}