<?php
class Financialaccount extends Entity
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
        return Entity::FIELD_CREATETIME | Entity::FIELD_UPDATETIME;
    }
}

