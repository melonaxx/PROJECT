<?php
class OrderproductWriter extends DWriter
{
    public function del($id)
    {
        $sql = "update orderproduct set isdelete = 'Y' where orderid = ?";

        return $this->exeByCmd($sql,array($id));
    }
}
