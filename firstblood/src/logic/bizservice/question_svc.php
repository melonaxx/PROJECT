<?php

class Etc_categarySvc
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
    //添加分类
    public function addc($cname)
    {
        $categary = new Etc_categary();
        $categary['categary']=$cname;
        try {
            $categary->insert();
            $id = $categary->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
    //修改分类名称
    public function changename($id,$cname)
    {
        $categary = new Etc_categary($id);
        $categary['categary']=$cname;
        try {
            $categary->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
//题目表
class Etc_topicSvc
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
    //添加题
    public function addspro($cid,$proname,$type)
    {
        $topic = new Etc_topic();
        $topic['cid']=$cid;
        $topic['topic']=$proname;
        $topic['type']=$type;
        try {
            $topic->insert();
            $id = $topic->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
}
//选择题答案表
class Etc_selectionSvc
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
    //添加题
    public function addanswer($tid,$proname)
    {
        $select = new Etc_selection();
        $select['tid']=$tid;
        $select['content']=$proname;
        try {
            $select->insert();
            $id = $select->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
    //设置答案错对
    public function setanswer($id,$correct)
    {
        $select = new Etc_selection($id);
        $select['correct']=$correct;
        try {
            $select->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
//判断题表
class Etc_judgeSvc
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
    //添加题
    public function addpro($cid,$proname)
    {
        $judge = new Etc_judge();
        $judge['cid']=$cid;
        $judge['content']=$proname;
        try {
            $judge->insert();
            $id = $judge->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
    //设置判断题答案错对
    public function setanswer($id,$correct)
    {
        $judge = new Etc_judge($id);
        $judge['id']=$id;
        $judge['correct']=$correct;
        try {
            $judge->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
class Etc_askSvc
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
    //添加答案
    public function addanswer($tid,$proname)
    {
        $ask = new Etc_ask();
        $ask['tid']=$tid;
        $ask['content']=$proname;
        try {
            $ask->insert();
            $id = $ask->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
}

class ShowpaperSvc
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
    //添加一道题
    public function addpro($tid,$asid,$pid,$type,$score)
    {
        $showpaper = new Showpaper();
        $showpaper['tid']=$tid;
        $showpaper['asid']=$asid;
        $showpaper['pid']=$pid;
        $showpaper['type']=$type;
        $showpaper['score']=$score;
        try {
            $showpaper->insert();
            $id = $showpaper->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
    //打分
    public function playscore($tid,$score)
    {
        $showpaper = new Showpaper($tid);
        $showpaper['score'] = $score;
        try {
            $showpaper->update();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
//答卷
class RespondentsSvc
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
    public function addp($uname,$phone,$cid)
    {
        $res = new Respondents();
        $res['uname']=$uname;
        $res['phone']=$phone;
        $res['cid']=$cid;
        try {
            $res->insert();
            $id = $res->id;
            return $id;
        } catch (Exception $ex) {
            return false;
        }
    }
}