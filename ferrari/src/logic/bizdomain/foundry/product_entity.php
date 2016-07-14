<?php
class processfactory extends Entity
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

//生产单表
class Manufactory extends Entity
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

// -- 生产 - 代工户明细关联表---
class Processmanufactory extends Entity
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

//生产 - 代工户商品出入库单据
class Fprobill extends Entity
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
class Fprochange extends Entity
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

// 生产 - 手动调拨原料
// 
class Allocateraw extends Entity
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
// 代工库实时原料明细数量
class Fstoresync extends Entity
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

// 代工库手动减库-
class Fstoredes extends Entity
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

// 生产 - 代工户结算记录-
class Fprosettle extends Entity
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

// 生产公用关联财务科目表-

class Fprofinance extends Entity
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

// 生产 - 日常开票记录编号
class Makinvoicelog extends Entity
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