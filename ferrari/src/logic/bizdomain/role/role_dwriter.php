<?php
class RoleWriter extends DWriter
{
	//修改权限
    public function updateauth($auth,$id)
    {
        $sql = "update role set authority=? where id = ?";
        return $this->exeByCmd($sql, array($auth,$id));
    }
    //修改角色名和备注
    public function updaterole($name,$comment,$id)
    {
        $sql = "update role set name=?,comment = ? where id = ?";
        return $this->exeByCmd($sql, array($name,$comment,$id));
    }
    //删除角色
    public function delrole($id)
    {
        $sql = "update role set isdelete = 'N' where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
}	