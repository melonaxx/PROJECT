<?php
class OneIns
{
    static $insCnt = array() ;
    public function __construct()
    {
        $cls = get_class($this);
        self::$insCnt[$cls] ++ ;
        if(self::$insCnt[$cls] > 1)
            DBC::requireTrue(false,  "$cls have more one instance ");
    }
}

