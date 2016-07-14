<?php





class Action_app_custom_service extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("PlatformQuery")->platforminfo();
        $platid = isset($_GET['platid'])?$_GET['platid']:$list['0']['id'];
        $question = XDao::query("ProblemanswerQuery")->questioninfo($platid);
        $xcontext->list = $list;
        $xcontext->question = $question;
        $xcontext->flag = $platid;
        return XNext::useTpl("/app/custom_service.html");
    }
}
//进入添加问题页面
class Action_app_add_question extends XAction
{
    public function _run($request, $xcontext)
    {
        $plat = XDao::query("PlatformQuery")->platforminfo();
        $xcontext->plat = $plat;
        return XNext::useTpl("/app/add_question.html");
    }
}
//执行添加
class Action_app_doaddquestion extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['platid'];
        $question = $request->attr['question'];
        $answer = $request->attr['answer'];
        $result=ProblemanswerSvc::ins()->addquestion($id,$question,$answer);
        echo $result;
    }
}
//编辑问题
class Action_app_editquestion extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['selfid'];
        $platformid = $request->attr['platname'];
        $problem = $request->attr['question'];
        $answer = $request->attr['answer'];
        $result=ProblemanswerSvc::ins()->editquestion($id,$platformid,$problem,$answer);
        if($result){
        return XNext::gotourl('/app/custom_service.php');
        }
    }
}
//删除问题
class Action_app_delquestion extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result=ProblemanswerSvc::ins()->delquest($id);
        echo  $result;

    }
}
//进入平台列表
class Action_app_add_platform extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("PlatformQuery")->platforminfo();
        $xcontext->list = $list;
        // var_dump($list);
        return XNext::useTpl("/app/add_platform.html");
    }
}
//执行添加平台
class Action_app_doaddplatform extends XAction
{
    public function _run($request, $xcontext)
    {
        $data['name'] = $request->attr['name'];
        $data['body'] = $request->attr['body'];
        $result=PlatformSvc::ins()->addplatform($data);
        return XNext::gotourl('/app/add_platform.php');

    }
}
//删除平台
class Action_app_delplatform extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $list = XDao::query("ProblemanswerQuery")->countquest($id);
        if($list['count(*)'] ==0){
            $result=PlatformSvc::ins()->delplatform($id);
            echo $result;
        }else{
            echo "请先删除该平台下问题!";
        }
    }
}
//修改平台
class Action_app_editplatform extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $name = $request->attr['name'];
        $body = $request->attr['body'];
        $result = PlatformSvc::ins()->editplatform($id,$name,$body);
        if($result){
        return XNext::gotourl('/app/add_platform.php');
        }
    }
}
class Action_app_findcity extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $list = XDao::query("purchasesupplierQuery")->findpro($id);
        echo json_encode($list);
    }
}



