<?php

class User extends Entity
{
    /** 
     * @brief 状态常量：被删除
     */
    const STATUS_DELETED = -1;
    /** 
     * @brief 状态常量：正常
     */
    const STATUS_NORMAL = 0;
    /** 
     * @brief 状态常量：第一次登录，这时要求用户修改密码
     */
    const STATUS_FIRST_LOGIN = 1;

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

class Security extends Entity
{
    public function getKey()
    {
        static $primarykey = 'userid';
        return $primarykey;
    }

    public function isAutoKey()
    {
        return false;
    }

    public function whichTimeFields()
    {     
        return Entity::FIELD_CREATETIME | Entity::FIELD_UPDATETIME;
    }
}

class LoginLog extends Entity
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
        return Entity::FIELD_CREATETIME;
    }
}
