<?php

class Capital_classSvc
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
    public function addcapital_class($capitalclas_name,$prefix_name)
    {
        $capital_class = new Capital_class();
        $capital_class['classname']=htmlspecialchars($capitalclas_name);
        $capital_class['prefix']=htmlspecialchars($prefix_name);
        try {
            $capital_class->insert();
            return $capital_class->id;
        } catch (Exception $ex) {
            return false;
        }
    }
}

class CapitalSvc
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
    public function addcapital($fix,$argument,$time,$brand,$cid)
    {
        $capital = new Capital();
        $capital['number']=htmlspecialchars($fix);
        $capital['capitalname']=htmlspecialchars($brand);
        $capital['argument']=htmlspecialchars($argument);
        $capital['inputtime']=htmlspecialchars($time);
        $capital['cid']=intval($cid);
        try {
            $capital->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    //修改
    public function updatecapital($capital_info)
    {
        $capital = new Capital($capital_info['matter_id']);
        $capital['number']=htmlspecialchars($capital_info['matter_num']);
        $capital['capitalname']=htmlspecialchars($capital_info['matter_brand']);
        $capital['argument']=htmlspecialchars($capital_info['argument']);
        $capital['cid']=intval($capital_info['captal_class']);
        try {
            $capital->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    //删除
    public function delete_capital($id)
    {
        $capital = new Capital($id);
        $capital['status']="F";
        try {
            $capital->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    //分配物品
    public function add_belong($capital_id,$pname)
    {
        $capital = new Capital($capital_id);
        $capital['pname']=$pname;
        $capital['giveout']="T";
        try {
            $capital->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    //收回
    public function tackback($capital_id)
    {
        $capital = new Capital($capital_id);
        $capital['giveout']="F";
        $capital['pname']="";
        try {
            $capital->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
