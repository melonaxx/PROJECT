<?php
class UserroleWriter extends DWriter
{
    public function deluserrole($uid)
    {
        $sql = "delete from userrole where userid = ?";
        return $this->exeByCmd($sql, array($uid));
    }
}	