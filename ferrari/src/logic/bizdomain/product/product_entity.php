<?php
/**
 * @brief 商品主表
 *
 * @return
 * */
class Product extends Entity
{
    //设置主键
    public function getKey()
    {
        static $primarykey = 'productid';
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

