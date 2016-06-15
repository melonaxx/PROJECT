<?php

class Ebike extends Entity
{
    /**
     * @brief 异常常量：正常
     */
    const EXCEPTION_NORMAL = 0;
    /**
     * @brief 异常常量：报警
     */
    const EXCEPTION_ARAM   = 1;
    /**
     * @brief 异常常量：电量告警
     */
    const EXCEPTION_ELECT  = 2;
    /**
     * @brief 异常常量：失联
     */
    const EXCEPTION_LOST   = 3;
    /**
     * @brief 状态常量：绑定
     */ 
    const ALLOT_BIND       = 1;
    /**
     * @brief 状态常量：未绑定
     */
    const ALLOT_UNBIND     = -1;
    /**
     * @brief 运行状态：正常
     */
    const STATUS_NOMAL     = 1;
    /**
     * @brief 运行状态：休息
     */
    const STATUS_REST      = 2;

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

class StatusDictionary
{
    const EXCEPTION_ARAM  = 1101;
    const EXCEPTION_ELECT = 1102;
    const EXCEPTION_LOST  = 1103;
    const ALLOT_BIND      = 1201;
    const ALLOT_UNBIND    = 1202;
    const STATUS_RUN      = 1301;
    const STATUS_REST     = 1302;

    public static $exception = array(
        self::EXCEPTION_ARAM,
        self::EXCEPTION_ELECT,
        self::EXCEPTION_LOST
    );

    public static $allot = array(
        self::ALLOT_BIND,
        self::ALLOT_UNBIND
    );  

    public static $status = array(
        self::STATUS_RUN,
        self::STATUS_REST    
    );    
}
