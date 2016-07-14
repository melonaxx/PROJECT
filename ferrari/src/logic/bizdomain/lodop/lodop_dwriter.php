<?php
class LodopWriter extends DWriter
{
    public function deleteitem($mid)
    {
        $sql = "delete from expresstemplateposition where templateid = ?";

        return $this->exeByCmd($sql,array($mid));
    }
}
