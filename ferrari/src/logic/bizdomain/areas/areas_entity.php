<?php
/**
 * @brief 省市县转换
 *
 * @return
 * */
class Areas extends Entity
{
    //设置主键
    public function getKey()
    {
        static $primarykey = 'number';
        return $primarykey;
    }

    //设置是否自增
    public function isAutoKey()
    {
        return false;
    }

    //设置创建时间与更新时间
    public function whichTimeFields()
    {
        return Entity::FIELD_CREATETIME | Entity::FIELD_UPDATETIME;
    }

}

