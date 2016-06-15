<?php

class DemoQuery extends Query
{
    public function listDemo()
    {
        $sql = "select * from demo where id > ?";
        return $this->listByCmd($sql, array(10060));
    }
}

