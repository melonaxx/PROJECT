<?php
/**
 * @brief 仓库盘点单
 *
 * @return
 * */
class StrCheck extends Entity
{
    //设置主键
    public function getKey()
    {
        static $primarykey = 'id';
        return $primarykey;
    }

    //设置是否自增
    public function isAutoKey()
    {
        return true;
    }

    //设置创建时间与更新时间
    public function whichTimeFields()
    {
        return Entity::FIELD_CREATETIME | Entity::FIELD_UPDATETIME;
    }

}

