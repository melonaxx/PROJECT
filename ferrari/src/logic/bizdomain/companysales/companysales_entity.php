<?php
/**
 * @brief 添加公司渠道 - 公司表
 *
 * @return
 * */
class Companysales extends Entity
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

