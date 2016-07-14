<?php

class CompanyWriter extends DWriter
{
	//修改公司为删除状态
    public function delcompany($id)
    {
        $sql = "update company set isdelete='N' where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
    //修改公司信息
    public function upcompany($name,$comment,$id)
    {
    	$sql = "update company set name = ?,comment = ? where id = ?";
        return $this->exeByCmd($sql, array($name,$comment,$id));
    }
}
