<?php
class Capital_class extends Entity
{
    public function getKey()
    {
        static $primarykey = 'id';
        return $primarykey;
    }

    public function isAutoKey()
    {
        return true;
    }

    public function whichTimeFields()
    {     
        return 0;
    }
}
class Capital extends Entity
{
    public function getKey()
    {
        static $primarykey = 'id';
        return $primarykey;
    }

    public function isAutoKey()
    {
        return true;
    }

    public function whichTimeFields()
    {     
        return 0;
    }
}