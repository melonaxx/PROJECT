<?php

class UserSvc 
{
    private static $_ins = null;
    private $logger      = null;

    private function __construct($logger)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new logger("biz");
        }
    }

    public static function ins($logger=null)
    {
        if (self::$_ins == null) {
            $cls = __CLASS__;
            self::$_ins = new $cls($logger);
        }
        return self::$_ins;
    }
    
    /**
     * @brief 用户注册
     *
     * @param $mobileno 手机号
     * @param $passwd   注册密码
     *
     * @return $uesr 成功生成的用户信息
     */
    public function addUser($mobileno, $passwd=null) 
    {
        $writer = XDao::dwriter("DWriter");

        $writer->beginTrans();

        try {
            if ($passwd) {
                $user = $this->createUser($status=User::STATUS_FIRST_LOGIN);
                $security = SecuritySvc::ins()->createSecurity($user->id, $passwd);
            } else {
                $user = $this->createUser($status=User::STATUS_NOT_REGISTER);
            }

            $userinfo = UserInfoSvc::ins()->createUserInfo($user->id, $mobileno);

            $writer->commit();

            return $user;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addUser[$mobileno]:".$ex->getMessage());
            $writer->rollback();
            return false;
        }
    }

    /**
     * @brief 修改用户信息
     *
     * @param $userid   用户id
     * @param $name     用户名
     *
     * @return
     */
    public function updateUserById($userid, $name) 
    {
        $user = new User($userid);
        $user->name = $name;

        try {
            $user->update();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateUser[$userid]：".$ex->getMessage());
            return false;
        }
    }

    /**
     * @brief  根据用户id获取用户信息
     *
     * @param  [int] $userid [用户id]
     *
     * @return 
     */
    public function getUserById($userid)
    {
        $user = new User($userid);
        $user->get();

        if ($user->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUserById, userid: $userid");
            return null;
        }
        
        return $user;
    }

    /**
     * @brief  验证登陆
     *
     * @param $name 用户名 
     *
     * @return
     */
    public function getUserByName($name)
    {
        $user = new User();
        $user->get(array("name"=>$name));

        if ($user->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUserByName, name: $name");
            return null;
        }

        return $user;
    }

    /**
     * @brief 获取用户类型
     *
     * @param $userid
     *
     * @return 
     */
    public function getUserTypeById($userid)
    {
        $user = new User($userid);
        $user->get();

        if ($user->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
            $this->logger->error("exception occurs when getUserTypeById, userid: $userid");
            return false;
        }

        return $user->usertype;
    }

    /**
     * @brief 生成一条用户信息
     *
     * @param $status 登陆状态
     *
     * @return 
     */
    public function createUser($status) 
    {
        $user = new User();
        $user->status = $status;

        $user->insert();

        return $user;
    }

    /**
     * @brief 修改用户状态常量
     *
     * @param $userid
     * @param $status
     *
     * @return
     */
    public function updateUserStauts($userid, $status, $usertype=null)
    {
        $user = new User($userid);
        $user->status = $status;

        if (!is_null($usertype)) {
            $user->usertype = $usertype;
        }

        try {
            $user->update();
            return $user;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateUserStauts[$userid]: ".$ex->getMessage());
            return false;
        }    
    }

    public function updatePermissonById($id, $authority)
    {
        $user = new User($id);
        $user->authority = $authority;

        try {
            $user->update();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateUser[$id]: ".$ex->getMessage());
            return false;
        }    
    }
}

