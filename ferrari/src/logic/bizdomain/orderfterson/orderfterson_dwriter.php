<?php
class OrderftersonWriter extends DWriter
{
    public function del($id)
    {
        $sql = "delete from orderfterson where orderid = ?";

        return $this->exeByCmd($sql,array($id));
    }
}
