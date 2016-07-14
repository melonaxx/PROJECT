<?php

/** 
* @brief 登录失败时的错误码。当小于0时，表示不用将错误原因显示在页面上
 */
class LoginErrno
{
    /** 
     * @brief cookie不存在
     */
    const NOCOOKIE = -1;

    /** 
     * @brief 被踢下线
     */
    const KICKED = 1;

    /** 
     * @brief 30分钟无操作，session过期
     */
    const EXPIRED = 2;

    /** 
     * @brief 登录超过24小时，强制下线
     */
    const FORCED = 3;

    /** 
     * @brief 解密失败
     */
    const BADCOOKIE = 4;

    /** 
     * @brief 用户不存在或被禁
     */
    const NOTFOUND = 5;
}
