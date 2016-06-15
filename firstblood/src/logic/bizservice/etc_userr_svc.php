<?php

class etc_userrSvc
{
    private static $_ins = null;
    private $logger = null;

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
    public function addadmin($info)
    {
       $add= new etc_userr();
       $add->username=htmlspecialchars($info['username']);
       $add->password=sha1(md5($info['password']));

       try {
            $add->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public function changepass($password,$userid)
    {
       $user = new etc_userr($userid);
       $user->password=sha1(md5($password));

       try {
            $user->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
