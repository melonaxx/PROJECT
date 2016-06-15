<?php

class User extends Entity
{
    /**
     * @brief 状态常量：注册完毕，首次登录 
     */
    const STATUS_FIRST_LOGIN   = 1;
    /** 
     * @brief 状态常量：完善信息 
     */
    const STATUS_COMPLETE_INFO = 2;
    /** 
     * @brief 状态常量：企业认证 
     */
    const STATUS_AUTH          = 3;
    /** 
     * @brief 状态常量：等待认证中...
     */
    const STATUS_WAIT_AUTH     = 4;
    /**
     * @brief 状态常量：认证失败
     */
    const STATUS_AUTH_FAIL     = 5;
    /**
     * @breif 状态常量：黑名单
     */
    const STATUS_BLACK_LIST    = 6;
    /**
     * @brief 状态常量：正常
     */
    const STATUS_NORMAL        = 0;
    /**
     * @brief 状态常量：被删除
     */
    const STATUS_DELETED       = -1;
    /**
     * @brief 状态常量：未注册
     */    
    const STATUS_NOT_REGISTER  = -2;
    /**
     * @brief 类型常量：员工
     */
    const USERTYPE_EMPLOYEE    = 0;
    /**
     * @brief 类型常量：平台
     */
    const USERTYPE_PLATFORM    = 1;
    /**
     * @brief 类型常量：劳务方
     */
    const USERTYPE_LABOR       = 2;
    /**
     * @brief 类型常量：骑士
     */
    const USERTYPE_KNIGHT      = 4;
    /**
     * @brief 类型常量：外卖店
     */
    const USERTYPE_TAKEAWAY    = 3;
    
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

    /**
     * @brief 用户状态是否合法
     *
     * @return
     */
    public function isValid()
    {
        return $user['status'] >= 0;
    }
}

class UserInfo extends Entity
{
    /**
     * getkey方法
     * @return 主键的名称
     */
    public function getkey()
    {
        static $primarykey = 'userid';
        return $primarykey;
    }

    /**
     * 主键字段是否自增长，默认值是true
     * @return true: 增长，false: 不增长
     */
    public function isAutoKey()
    {
        return false;
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

class Security extends Entity
{
    /**
     * getkey方法
     * @return 主键的名称
     */
    public function getkey()
    {
        static $primarykey = 'userid';
        return $primarykey;
    }

    /**
     * 主键字段是否自增长，默认值是true
     * @return true: 增长，false: 不增长
     */
    public function isAutoKey()
    {
        return false;
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

