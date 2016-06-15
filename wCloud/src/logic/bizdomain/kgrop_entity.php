<?php

class KGrop extends Entity
{
    /**
     * @brief 删除常量：不删除
     */
    const IS_DELETE_FALSE = 0;
    /**
     * @brief 删除常量：删除
     */
    const IS_DELETE_TRUE  = 1;

    /**
     * getkey方法
     * @return 主键的名称
     */
    public function getkey()
    {
        static $primarykey = 'id';
        return $primarykey;
    }

    /**
     * 主键字段是否自增长，默认值是true
     * @return true: 增长，false: 不增长
     */
    public function isAutoKey()
    {
        return true;
    }

    /**
     * 需要哪些关于时间的字段
     * @return
     * 0：没有创建/更新时间的相关字段
     * Entity::FIELD_CREATETIME：createtime datetime
     * Entity::FIELD_UPDATETIME：updatetime datetime
     * 以上数字采用 "|" 操作表示同时存在
     */
    public function whichTimeFields()
    {
        return Entity::FIELD_CREATETIME | Entity::FIELD_UPDATETIME;
    }
}
