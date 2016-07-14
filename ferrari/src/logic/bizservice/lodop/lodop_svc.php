<?php

class ExpresstemplateinfoSvc
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


    //添加模版
    public function add_model($templateinfo)
    {
        $model = new Expresstemplateinfo();
        $model['name']=htmlspecialchars($templateinfo['template_name']);//模版名称
        $model['image']=htmlspecialchars($templateinfo['image']);//模版背景图
        $model['paperwidth']=htmlspecialchars($templateinfo['page_width']);//
        $model['paperheight']=htmlspecialchars($templateinfo['page_height']);
        $model['paperleft']=htmlspecialchars($templateinfo['page_left']);
        $model['papertop']=htmlspecialchars($templateinfo['page_top']);
        $model['pressid']=intval($templateinfo['company_express']);
        try {
            $model->insert();
            return $model->id;
        } catch (Exception $ex) {
             Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //修改模版
    public function update_model($templateinfo)
    {
        $model = new Expresstemplateinfo($templateinfo['mid']);
        $model['name']=htmlspecialchars($templateinfo['template_name']);//模版名称
        $model['image']=htmlspecialchars($templateinfo['image']);//模版背景图
        $model['paperwidth']=htmlspecialchars($templateinfo['page_width']);//
        $model['paperheight']=htmlspecialchars($templateinfo['page_height']);
        $model['paperleft']=htmlspecialchars($templateinfo['page_left']);
        $model['papertop']=htmlspecialchars($templateinfo['page_top']);
        try {
            $model->update();
            return true;
        } catch (Exception $ex) {
             Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
    //删除模版
    public function delete_model($mid){
        $model = new Expresstemplateinfo($mid);
        $model['status']="D";
        try {
            $model->update();
            return true;
        } catch (Exception $ex) {
             Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
}
class ExpresstemplatepositionSvc
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


    //添加小控件
    public function add_item($temp_item,$mid)
    {
        $item = new Expresstemplateposition();
        $item['templateid']=intval($mid);//模版id
        $item['itemid']=intval($temp_item['Itemid']);//栏目
        $item['itemwidth']=intval($temp_item['width']);//
        $item['itemheight']=intval($temp_item['height']);
        $item['itemleft']=intval($temp_item['left']);
        $item['itemtop']=intval($temp_item['top']);
        $item['itemfontsize']=intval($temp_item['fontsize']);
        try {
            $item->insert();
            return true;
        } catch (Exception $ex) {
             Debug::watch(__FILE__,__LINE__,$ex,'$ex');
            $this->logger->error("exception occurs when addprosaleinfo".$ex->getMessage());
            return false;
        }
    }
}