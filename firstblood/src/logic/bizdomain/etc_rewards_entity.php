<?php
class Etc_rewards extends Entity
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
class Etc_wages extends Entity
{
    public function getKey()
    {
        static $primarykey = 'id';
        return $primarykey;
    }

    public function isAutoKey()
    {
        return false;
    }

    public function whichTimeFields()
    {     
        return 0;
    }
}
