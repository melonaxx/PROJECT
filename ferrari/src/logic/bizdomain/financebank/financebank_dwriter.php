<?php
class FinancebankWriter extends DWriter
{
	//修改所有账户为不默认
    public function nodefault()
    {
        $sql = "update financebank set isdefault='N'";
        return $this->exeByCmd($sql);
    }
    //设置状态为删除
    public function delbank($id)
    {
        $sql = "update financebank set isdelete='Y' where id = ?";
        return $this->exeByCmd($sql,array($id));
    }
    //修改账户信息
    public function editfinancebank($name,$isdefault,$balance,$number,$type,$comment,$id)
    {
        $sql = "update financebank set name = ?,isdefault = ?,balance = ?,number = ?,type = ?,comment = ? where id = ?";
        return $this->exeByCmd($sql,array($name,$isdefault,$balance,$number,$type,$comment,$id));
    }
}
