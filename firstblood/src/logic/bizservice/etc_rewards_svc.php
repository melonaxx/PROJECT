<?php

class Etc_rewardsSvc
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
    public function insertinfo($rows)
    {
        $rewards = new Etc_rewards();
        $rewards['reason'] = htmlspecialchars($rows['reason']);//理由

            if($rows['rewards']=="chufa"){
                 $rewards['flag']='-1';
                 $rewards['acount']="-".$rows['money'];
            }else if($rows['rewards']=='jiangli'){
                 $rewards['flag']='1';
                 $rewards['acount']=$rows['money'];
            }

        $rewards['pid'] = htmlspecialchars($rows['pid']);//员工的id
        $rewards['time'] = htmlspecialchars($rows['time']);//时间
        try {
            $rewards->insert();
            $id = $rewards->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
}

//工资的一些信息
class Etc_wagesSvc
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
    public function updatewage($wagedetail,$id)
    {
        $wages = new Etc_wages($id);
        $wages['addwork'] = htmlspecialchars($wagedetail['addwork']);//加班费
        $wages['late'] = htmlspecialchars($wagedetail['late']);//迟到
        $wages['earlyleave'] = htmlspecialchars($wagedetail['earlyleave']);//早退
        $wages['absent'] = htmlspecialchars($wagedetail['absent']);//旷工
        $wages['sale'] = htmlspecialchars($wagedetail['sale']);//销售提成
        $wages['stick'] = htmlspecialchars($wagedetail['stick']);//计件加个
        $wages['workagenum'] = htmlspecialchars($wagedetail['workagenum']);//工龄
        $wages['fund'] = htmlspecialchars($wagedetail['fund']);//公积金
        $wages['safe'] = htmlspecialchars($wagedetail['safe']);//保险
        $wages['house'] = htmlspecialchars($wagedetail['house']);//房补
        $wages['subsidy'] = htmlspecialchars($wagedetail['subsidy']);//补贴
        try {
            $wages->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}