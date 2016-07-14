<?php
//渠道列表
class Action_crm_channelset extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("companysalesQuery")->allcompanysalesinfo();
        $xcontext->list=$list;
        return XNext::useTpl("crm/channelset.html");
    }
}
//添加渠道
class Action_crm_doaddsales extends XAction
{
    public function _run($request, $xcontext)
    {
        $name = $request->attr['name'];
        $comment = $request->attr['comment'];
        $result=CompanysalesSvc::ins()->addsales($name,$comment);
        return XNext::gotourl($_SERVER['DOMAIN'].'/crm/channelset.php');
    }
}
//删除渠道
class Action_crm_delsales extends XAction
{
    public function _run($request, $xcontext)
    {
       $id = $request->attr['id'];
       $result=CompanysalesSvc::ins()->delsales($id);
       echo $result;
    }
}
//修改渠道
class Action_crm_editsales extends XAction
{
    public function _run($request, $xcontext)
    {
       $id = $request->attr['id'];
       $name = $request->attr['name'];
       $comment = $request->attr['comment'];
       $result=CompanysalesSvc::ins()->editsales($id,$name,$comment);
       echo $result;
    }
}






class Action_crm_messageset extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("crm/messageset.html");
    }
}
class Action_crm_messageconfig extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("crm/messageconfig.html");
    }
}
class Action_crm_messagemould extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("crm/messagemould.html");
    }
}
class Action_crm_messageorder extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("crm/messageorder.html");
    }
}
class Action_crm_orderhistory extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("crm/orderhistory.html");
    }
}
class Action_crm_sendhistory extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("crm/sendhistory.html");
    }
}
//供应商列表
class Action_crm_supplier extends XAction
{
    public function _run($request, $xcontext)
    {
        $seach = strval($request->attr['seach']);
        if(strlen($seach)!=0){
            $where = "where name like '%$seach%' and isdelete = 'N'";
        }else{
            $where ="where isdelete = 'N'";
        }
        $lists = XDao::query("purchasesupplierQuery")->allsuppliers($where);
        $arr['total_rows'] = count($lists);
        $arr['list_rows'] = isset($_GET['num'])?$_GET['num']:5;
        $aaa = new Core_Lib_Page($arr);
        if(strlen($seach)!=0){
            $aaa->seach = "&seach=$seach";
        }
        $list = XDao::query("purchasesupplierQuery")->pagesuppliers($where,$aaa->first_row,$arr['list_rows']);
        $pages = $aaa->show(3);

        $xcontext->list = $list;
        $xcontext->pages = $pages;
    $xcontext->seach=$seach;
        return XNext::useTpl("/crm/supplier.html");
    }
}
//进入添加页面
class Action_crm_addsupplier extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("crm/addsupplier.html");
    }
}
//执行添加供应商
class Action_crm_doaddsupplier extends XAction
{
    public function _run($request, $xcontext)
    {
        $data=$_POST;
        $result=PurchasesupplierSvc::ins()->addsuppliers($data);
        return XNext::gotourl($_SERVER['DOMAIN'].'/crm/supplier.php');
    }
}
//删除供应商
class Action_crm_delsupplier extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result=PurchasesupplierSvc::ins()->delsuppliers($id);
        echo $result;

    }
}
//进入编辑页面
class Action_crm_supplierinfor extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        // echo $id;
        $list = XDao::query("purchasesupplierQuery")->findsuppliers($id);
        $xcontext->list = $list;
        return XNext::useTpl("/crm/supplierinfor.html");
    }
}
//修改供应商信息
class Action_crm_editsupplier extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $data=$_POST;
        $result=PurchasesupplierSvc::ins()->updatesuppliers($data,$id);
        return XNext::gotourl($_SERVER['DOMAIN'].'/crm/supplier.php');
    }
}
class Action_crm_suppliersearch extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("purchasesupplierQuery")->findallname();
        foreach ($list as $key => $value) {
        $p = XDao::query("purchaseQuery")->findybysupp($value['id']);
        $p['woca'] = $p['total']+$value['balance']-$p['reday'];
        $list[$key]['p'] = $p;
        }
        $xcontext->list = $list;
        return XNext::useTpl("crm/suppliersearch.html");
    }
}
/*供应商款项记录*/
class Action_crm_supplierpayment extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("crm/supplierpayment.html");
    }
}





