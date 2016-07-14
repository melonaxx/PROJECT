<?php

class FinancialaccountSvc
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


    //添加财务科目
    public function addfinan($data)
    {
        $add = new Financialaccount();
        $add->code = $data['code'];
        $add->name = $data['name'];
        $add->remark = $data['comment'];
        $add->parent = isset($data['parent'])?$data['parent']:0;
        $add->acctypeid = $data['type'];
        $add->accgoryid = $data['class'];
        $add->balance = $data['radio'];
        try {
            $pdata = $add->insert();
            return $pdata;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsbrand".$ex->getMessage());
            return false;
        }
    }
    //修改平台状态为删除
    public function delfinan($id)
    {
        $del = new Financialaccount($id);
        $del['status'] = 'D';
        try {
            $pdel = $del->update();
            return $pdel;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsbrand".$ex->getMessage());
            return false;
        }
    }
    public function editfinan($id,$data)
    {
        $edit = new Financialaccount($id);
        $edit['code'] = $data['code'];
        $edit['name'] = $data['name'];
        $edit['acctypeid'] = $data['type'];
        $edit['accgoryid'] = $data['class'];
        $edit['balance'] = $data['radio'];
        $edit['remark'] = $data['comment'];
        try {
            $pdel = $edit->update();
            return $pdel;
        } catch (Exception $ex) {
            $this->logger->error("exception occurs when addgoodsbrand".$ex->getMessage());
            return false;
        }
    }
}