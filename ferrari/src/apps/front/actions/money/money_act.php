<?php
//进入账户管理查看账户列表
class Action_money_accountmanage extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("financebankQuery")->allfinancebank();
        $default = XDao::query("financebankQuery")->finddefault();
        $xcontext->list = $list;
        $xcontext->default = $default;
        return XNext::useTpl("money/accountmanage.html");
    }
}
//添加账户
class Action_money_doaddmanage extends XAction
{
    public function _run($request, $xcontext)
    {
        $data['isdefault'] = "N";
        if($request->attr['optionsRadios']=='option1'){
            //先把其他的默认取消掉
            $list = XDao::dwriter("FinancebankWriter")->nodefault();
            $data['isdefault'] = "Y";
        }
        $data['name'] = $request->attr['name'];
        $data['balance'] = $request->attr['balance'];
        $data['number'] = $request->attr['number'];
        $data['type'] = $request->attr['type'];
        $data['comment'] = $request->attr['comment'];

        $result=FinancebankSvc::ins()->addbank($data);
        return XNext::gotourl($_SERVER['DOMAIN'].'/money/accountmanage.php');
    }
}
//删除账户
class Action_money_delfinancebank extends XAction
{
    public function _run($request, $xcontext)
    {
        $id=$request->attr['id'];
        $list = XDao::dwriter("FinancebankWriter")->delbank($id);
        echo $list;
    }
}
//修改账户
class Action_money_editbank extends XAction
{
    public function _run($request, $xcontext)
    {
        
        $id = $request->attr['id'];
        $default = XDao::query("financebankQuery")->findstatus($id);
        $default = $default['isdefault'];
        if($default=='Y'){
            $isdefault = 'Y';
        }else{
            if($request->attr['Radios']=='0'){
                $list = XDao::dwriter("FinancebankWriter")->nodefault();
                $isdefault = 'Y';
            }else{
                $isdefault = 'N';
            }
        }
        $name = $request->attr['name'];
        $balance = $request->attr['balance'];
        $number = $request->attr['number'];
        $type = $request->attr['type'];
        $comment = $request->attr['comment'];
        $list = XDao::dwriter("FinancebankWriter")->editfinancebank($name,$isdefault,$balance,$number,$type,$comment,$id);
        return XNext::gotourl($_SERVER['DOMAIN'].'/money/accountmanage.php');
        

    }
}
class Action_money_accountreport extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/accountreport.html");
    }
}

//财务科目类别列表
class Action_money_accounttypeclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("accounttypeQuery")->allaccounttype();
        $xcontext->list = $list;
        $list1 = XDao::query("accountcategoryQuery")->allaccountcategory();
        foreach($list1 as $k=>$v){
            $type = XDao::query("accounttypeQuery")->findaccounttype($v['acctypeid']); 
            $list1[$k]['typename'] = $type['typename'];
        }
        $xcontext->list1 = $list1;
        return XNext::useTpl("money/accounttypeclass.html");
    }
}
//执行添加财务科目类别
class Action_money_addaccounttypeclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $data = $_POST;
        $result=AccountcategorySvc::ins()->addaccounttypeclass($data);
        echo $result;
    }
}
//执行删除财务科目类别
class Action_money_delaccounttypeclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result=AccountcategorySvc::ins()->delaccounttypeclass($id);
        echo  $result;
    }
}
// 进入编辑页面
class Action_money_findaccounttypeclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result = XDao::query("accountcategoryQuery")->findaccountcategory($id);
        echo  json_encode($result,JSON_UNESCAPED_UNICODE);
    }
}
//执行修改财务科目类别
class Action_money_editaccounttypeclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $name = $request->attr['name'];
        $comment = $request->attr['comment'];
        $type = $request->attr['types'];
        $result=AccountcategorySvc::ins()->editaccounttypeclass($id,$name,$comment,$type);
        echo $result;
    }
}
//财务科目类型列表
class Action_money_accounttype extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("accounttypeQuery")->allaccounttype();
        $xcontext->list = $list;
        return XNext::useTpl("/money/accounttype.html");
    }
}
//执行添加财务类型
class Action_money_addaccounttype extends XAction
{
    public function _run($request, $xcontext)
    {
        $name = $request->attr['name'];
        $comment = $request->attr['comment'];
        $result=AccounttypeSvc::ins()->addaccounttype($name,$comment);
        // return XNext::useTpl("money/accounttype.html");
        echo $result;
    }
}
//执行删除财务类型
class Action_money_delaccounttype extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result=AccounttypeSvc::ins()->delaccounttype($id);
        echo $result;
    }
}
//进入编辑页面
class Action_money_findaccounttype extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $list = XDao::query("accounttypeQuery")->findaccounttype($id);
        echo json_encode($list,JSON_UNESCAPED_UNICODE);
    }
}
//执行修改财务类型
class Action_money_editaccounttype extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $name = $request->attr['name'];
        $comment = $request->attr['comment'];
        $result=AccounttypeSvc::ins()->editaccounttype($id,$name,$comment);
        echo $result;
    }
}
//根据类型id查它下面的类别
class Action_money_typeforclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $list = XDao::query("accountcategoryQuery")->findforaccid($id);
        echo json_encode($list,JSON_UNESCAPED_UNICODE);
    }
}
//财务科目列表
class Action_money_accountsubject extends XAction
{
    public function _run($request, $xcontext)
    {
        $acctype = XDao::query("accounttypeQuery")->allaccounttype();
        $xcontext->acctype = $acctype;
        $id = $request->attr['id'];
        if($id>0){
            $listinfo = XDao::query("financialaccountQuery")->findfortype($id);
        }else{
           $listinfo = XDao::query("financialaccountQuery")->allfinan(); 
        }
        
        foreach ($listinfo as $key => $value) {
            $res = XDao::query("accounttypeQuery")->findaccounttype( $value['acctypeid']);
            $listinfo[$key]['classname'] = $res['typename'];
        }
        function get_sort_by_array($arr,$parentid=0,$level=1) {
            $subs = array(); // 子孙数组
            foreach($arr as $k=>$v) {
                if($v['parent'] == $parentid) {
                    $v['level'] = $level;
                    $subs[] = $v;
                    $subs = array_merge($subs,get_sort_by_array($arr,$v['id'],$level+1));
                }
            }
            return $subs;
        }
        $catelist = get_sort_by_array($listinfo);
        if (count($catelist)) {
            foreach($catelist as $k=>&$v) {
                $v['name'] = str_repeat("|--", $v['level'] - 1).$v['name'];
            }
        }
        $xcontext->id = $id;
        $xcontext->listinfo = $catelist;
        return XNext::useTpl("/money/accountsubject.html");
    }
}
//添加财务科目
class Action_money_addaccfirst extends XAction
{
    public function _run($request, $xcontext)
    {
        $data = $_POST;
        $result=FinancialaccountSvc::ins()->addfinan($data);
        echo $result;
    }
}
//删除财务科目
class Action_money_delsubject extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $count = XDao::query("financialaccountQuery")->findforparent($id);
        if($count['count(*)']==0){
         $result=FinancialaccountSvc::ins()->delfinan($id);   
        echo $result;
        }else{
            echo "请先删除子科目!";
        }
        
        
    }
}
//打开修改页面默认显示的内容
class Action_money_findsubject extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $list = XDao::query("financialaccountQuery")->findfinan($id);
        $parentname = XDao::query("financialaccountQuery")->findfinan($list['parent']);
        $list['parentname'] = $parentname['name'];
        echo json_encode($list,JSON_UNESCAPED_UNICODE);
    }
}
//修改财务科目
class Action_money_editsubject extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $data = $_POST;
        $result=FinancialaccountSvc::ins()->editfinan($id,$data);
        echo $result;
        
    }
}
//根据科目id查内容
class Action_money_findparent extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result = XDao::query("financialaccountQuery")->findfinan($id);
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }
}

class Action_money_assetmanage extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/assetmanage.html");
    }
}

class Action_money_deliverycost extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/deliverycost.html");
    }
}

class Action_money_moneyallocation extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/moneyallocation.html");
    }
}

class Action_money_moneyincome extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/moneyincome.html");
    }
}

class Action_money_moneyspending extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/moneyspending.html");
    }
}

class Action_money_moneywater extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/moneywater.html");
    }
}

class Action_money_openbill extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/openbill.html");
    }
}


class Action_money_openedbill extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/openedbill.html");
    }
}

class Action_money_orderwater extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/orderwater.html");
    }
}

class Action_money_returnwater extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("money/returnwater.html");
    }
}






