<?php

class AddPointWriter extends DWriter
{
    public function addPoint($createtime,$updatetime,$latitude,$longitude)
    {
        $sql = "INSERT INTO cloudPoint SET createtime=?,updatetime=?,latitude=?,longitude=?";
        return $this->exeByCmd($sql, array($createtime,$updatetime,$latitude,$longitude));
    }
}
