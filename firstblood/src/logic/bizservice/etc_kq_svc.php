<?php

class Etc_kqSvc
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

    public function inkq($shuju)
    {
       $add= new Etc_kq();
       $add->userid=$shuju['userid'];
       $add->date=$shuju['date'];
       $add->firsttime=$shuju['firsttime'];
       $add->lasttime=$shuju['lasttime'];
       $add->status=$shuju['sta'];
       try {
            $add->insert();
            return $add;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when inkq:".$ex->getMessage());
            return false;
        }
    }
    //修改时间
    public function changetime($time,$start_or_stop,$id)
    {
       $kq= new Etc_kq($id);

       $kq[$start_or_stop]=$time;

       try {
            $kq->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
