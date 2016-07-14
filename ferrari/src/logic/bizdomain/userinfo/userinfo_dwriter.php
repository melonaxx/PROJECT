<?php
class UserinfoWriter extends DWriter
{
	//修改信息
    public function updateuserinfo($data)
    {
        $sql = "update role set authority=? where id = ?";
        return $this->exeByCmd($sql, array($auth,$id));
    }
    //删除信息
    public function deluserinfo($uid)
    {
        $sql = "update userinfo set status = 1 where userid = ?";
        return $this->exeByCmd($sql, array($uid));
    }
}	