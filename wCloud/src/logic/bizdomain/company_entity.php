<?php

class Company extends Entity
{
    /**
     * @brief 状态常量：提交认证
     */
    const STATUS_COMMIT_AUTH   = 4;
    /**
     * @brief 状态常量：认证失败
     */
    const STATUS_AUTH_FAIL     = 5;
    /**
     * @brief 状态常量：认证成功
     */
    const STATUS_AUTH_SUCCEESS = 0;
    /**
     * @brief 状态常量：黑名单
     */
    const STATUS_BLACKLIST     = 6;
    /**
     * @brief 类型常量：平台
     */
    const COMPANYTYPE_PLATFORM = 1;
    /**
     * @brief 类型常量：劳务方
     */
    const COMPANYTYPE_LABOR    = 2;
    /**
     * @brief 类型常量：外卖店
     */
    const COMPANYTYPE_RESTAURANT = 3;

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

class CityCard extends Entity
{
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
        return 0;
    }
}
