<?php

class CapitalWriter extends DWriter
{
    public function delete($cid)
    {
        $sql = "delete from capital_class where id = ?";
        return $this->exeByCmd($sql, array($cid));
    }
}