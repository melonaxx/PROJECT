<?php
class Etc_peopleinfo extends Entity
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

class Etc_class extends Entity
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
class Etc_heartclass extends Entity
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
class Etc_job extends Entity
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
class Etc_role extends Entity
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
class Etc_associated extends Entity
{
    public function getKey()
    {
        static $primarykey = 'uid';
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

//技能表
class Etc_skill extends Entity
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
class Etc_experience extends Entity
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
// 备注
class Remarks extends Entity
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
//日志记录
class Log extends Entity
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
//附件
class Adnexa extends Entity
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