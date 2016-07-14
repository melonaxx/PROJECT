<?php

class DepartmentWriter extends DWriter
{
    public function deldepartment($id)
    {
        $sql = "update department set isdelete='D' where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
    public function editdepartment($partment,$beizhu,$parent_id,$id)
    {
        $sql = "update department set name = ?,comment = ?,parent_id = ? where id = ?";
        return $this->exeByCmd($sql, array($partment,$beizhu,$parent_id,$id));
    }
}
