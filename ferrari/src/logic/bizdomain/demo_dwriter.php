<?php

class DemoWriter extends DWriter
{
    public function updateDemo()
    {
        $sql = "update demo set name='imposible' where id > ?";
        return $this->exeByCmd($sql, array(10060));
    }
}
