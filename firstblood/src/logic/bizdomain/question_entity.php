<?php
class Etc_categary extends Entity
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
class Etc_topic extends Entity
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
class Etc_selection extends Entity
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
class Etc_judge extends Entity
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
class Etc_ask extends Entity
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
//答卷人
class Respondents extends Entity
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

//答卷
class Showpaper extends Entity
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
