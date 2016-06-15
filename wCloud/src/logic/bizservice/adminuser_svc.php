<?php

class AdminUserSvc 
{
    const DOMAIN = "waimaiw.COM";

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
   
    public function verifyPasswd($name, $passwd)
    {
        try {

            $adminuser = new AdminUser();
            $adminuser->get(array("name" => $name));

            if ($adminuser->getLastOPStatus() == Entity::OP_STATUS_NOTFOUND) {
                $this->logger->error("exception occurs when getUserByName, name: $name");
                return false;
            }

            $passwd_in  = $adminuser->password;
            $passwd_out = $this->hashedOriginPasswd($adminuser->id, $passwd);
            if ($passwd_in && $passwd_in === $passwd_out) {
                return $adminuser; 
            } else {
                $this->logger->error("The passwd certification has failed, name: $name");
                return false; 
            }
        } catch (Exception $ex) {
            $this->logger->error("Server Error when verifyPasswd , name: $name");
            return false; 
        }
    }
 
    public function hashedOriginPasswd($userid, $origin_pwd) 
    {
        if (empty($origin_pwd)) {
            return "";
        }

        $passwd = md5($origin_pwd . "@" . self::DOMAIN);

        return $this->hashedPasswd($userid, $passwd);
    }
 
    public function hashedPasswd($userid, $passwd)
    {
        if (empty($passwd)) {
            return "";
        }

        return md5(sha1($userid . ":" . $passwd));
    }

    public function updateName($userid, $name)
    {
        $adminuser = new AdminUser($userid);
        $adminuser->name = $name;

        try {
            $adminuser->update();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updateName, userid: $userid" . $ex->getMessage());
            return false;
        }
    }

    public function updatePasswd($userid, $passwd)
    {
        $adminuser = new AdminUser($userid);
        $adminuser->password = $this->hashedOriginPasswd($userid, $passwd);

        try {
            $adminuser->update();
            return true;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when updatePasswd, userid: $userid");
            return false;
        }
    }
}   
