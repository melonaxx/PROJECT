<?php

class Etc_peopleinfoSvc
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

    public function updateinfo($info,$id)   
    {
        $people=new Etc_peopleinfo($id); 
        $people['name']=htmlspecialchars($info['name']);
        $people['sex']=htmlspecialchars($info['sex']);
        $people['wed']=htmlspecialchars($info['wed']);
        $people['education']=htmlspecialchars($info['education']);
        $people['birth']=htmlspecialchars($info['birth']);
        $people['phone']=htmlspecialchars($info['phone']);
        $people['actphone']=htmlspecialchars($info['actphone']);
        $people['safe']=htmlspecialchars($info['safe']);
        $people['drive']=htmlspecialchars($info['drive']);
        $people['graduate']=htmlspecialchars($info['graduate']);
        $people['specialty']=htmlspecialchars($info['specialty']);
        $people['pact']=htmlspecialchars($info['pact']);
        $people['pacttime']=htmlspecialchars($info['pacttime']);
        $people['try']=htmlspecialchars($info['try']);
        $people['pactover']=htmlspecialchars($info['pactover']);
        $people['hiredate']=htmlspecialchars($info['hiredate']);
        $people['outtime']=htmlspecialchars($info['outtime']);
        $people['idnumber']=htmlspecialchars($info['idnumber']);
        $people['banknumber']=htmlspecialchars($info['banknumber']);
        $people['email']=htmlspecialchars($info['email']);
        $people['pactsigntime']=htmlspecialchars($info['pactsigntime']);
        $people['lodge']=htmlspecialchars($info['lodge']);
        $people['cpf']=htmlspecialchars($info['cpf']);
        $people['nowaddress']=htmlspecialchars($info['nowaddress']);
        $people['peoplestatus']=htmlspecialchars($info['peoplestatus']);
        try {
            $people->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    public function addinfo($info)
    {
        $people=new Etc_peopleinfo(); 
        $people['name']=htmlspecialchars($info['name']);
        $people['sex']=htmlspecialchars($info['sex']);
        $people['wed']=htmlspecialchars($info['wed']);
        $people['education']=htmlspecialchars($info['education']);
        $people['birth']=htmlspecialchars($info['birth']);
        $people['phone']=htmlspecialchars($info['phone']);
        $people['actphone']=htmlspecialchars($info['actphone']);
        $people['safe']=htmlspecialchars($info['safe']);
        $people['drive']=htmlspecialchars($info['drive']);
        $people['graduate']=htmlspecialchars($info['graduate']);
        $people['specialty']=htmlspecialchars($info['specialty']);
        $people['pact']=htmlspecialchars($info['pact']);
        $people['pacttime']=htmlspecialchars($info['pacttime']);
        $people['try']=htmlspecialchars($info['try']);
        $people['pactover']=htmlspecialchars($info['pactover']);
        $people['hiredate']=htmlspecialchars($info['hiredate']);
        $people['idnumber']=htmlspecialchars($info['idnumber']);
        $people['banknumber']=htmlspecialchars($info['banknumber']);
        $people['bid']=htmlspecialchars($info['bid']);
        $people['email']=htmlspecialchars($info['email']);
        $people['pactsigntime']=htmlspecialchars($info['pactsigntime']);
        $people['lodge']=htmlspecialchars($info['lodge']);
        $people['cpf']=htmlspecialchars($info['cpf']);
        $people['nowaddress']=htmlspecialchars($info['nowaddress']);
        try {
            $people->insert();
            $pid = $people->id;
            return $pid;
        } catch (Exception $ex) {
            return false;
        }
    }
}
class AdnexaSvc
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
    //插入附件
    public function insert_adnexa($adnexname,$tmp_name,$pid)
    {
        $adnexa = new Adnexa();
        $adnexa['adnexaname']=$adnexname;
        $adnexa['adnexahash']=$tmp_name;
        $adnexa['pid']=$pid;

        try {
            $adnexa->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
//添加部门
class Etc_classSvc
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

    //添加部门
    public function addc($classname)
    {
      $class = new Etc_class();
      $class['class_name']=$classname;
        try {
            $class->insert();
            $cid = $class->id;
            return $cid;
        } catch (Exception $ex) {
            return false;
        }
    }
}
//添加星级
class Etc_heartclassSvc
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


    public function addheart($name,$lowpay,$highpay,$cid)
    {
      $heartclass = new Etc_heartclass();
      $heartclass['heartname']=$name;
      $heartclass['lowpay']=$lowpay;
      $heartclass['highpay']=$highpay;
      $heartclass['cid']=$cid;
        try {
            $heartclass->insert();
            $id = $heartclass->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
}
//添加岗位
class Etc_jobSvc
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


    public function addjob($jobname,$cid)
    {
      $job = new Etc_job();
      $job['jobname']=$jobname;
      $job['bid']=$cid;
        try {
            $job->insert();
            $id = $job->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
    public function changejob($jobname,$jid)
    {
      $job = new Etc_job($jid);
      $job['jobname']=$jobname;
        try {
            $job->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}

class Etc_roleSvc
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


    public function addrole($rolename)
    {
      $role = new Etc_role();
      $role['rolename']=$rolename;
        try {
            $role->insert();
            $id = $role->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
    public function updaterole($auth1,$rid)
    {
      $role = new Etc_role($rid);
      $role['auth']=$auth1;
        try {
            $role->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}

class Etc_associatedSvc
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


    public function addroleauth($uid,$jid)
    {
      $auth = new Etc_associated();
      $auth['uid']=$uid;
      $auth['jid']=$jid;
        try {
            $auth->insert();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
//添加技能
class Etc_skillSvc
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


    public function addskill($v,$pid)
    {
       $skill = new Etc_skill();
       $skill['skillcontent']=htmlspecialchars($v);
       $skill['pid']=$pid;
       try {
              $skill->insert();
              return true;
            } catch (Exception $ex) {
              return false;
            }
    }
}
//工作经验
class Etc_experienceSvc
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


    public function addexper($pid,$sotime,$expercontent)
    {
       $exper = new Etc_experience();
       $exper['pid']=$pid;
       $exper['sotime']=$sotime;
       $exper['expercontent']=$expercontent;
       try {
              $exper->insert();
              return true;
            } catch (Exception $ex) {
              return false;
            }
    }
}
//备注
class RemarksSvc
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


    public function addremark($v,$pid)
    {
       $remark = new Remarks();
       $remark['remarkcontent']=$v;
       $remark['pid']=$pid;
       try {
              $remark->insert();
              return true;
            } catch (Exception $ex) {
              return false;
            }
    }
}
//日志
class LogSvc
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


    public function addlog($date,$userid,$logcontent,$type)
    {
       $log = new Log();
       $log['happentime']=$date;
       $log['uid']=$userid;
       $log['logcontent']=$logcontent;
       $log['type']=$type;
       try {
              $log->insert();
              return true;
            } catch (Exception $ex) {
              return false;
            }
    }
}