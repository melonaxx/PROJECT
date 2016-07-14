<?php






class Action_order_orderreview extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/order/orderreview.html");
    }
}





class Action_order_paymentorder extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/order/paymentorder.html");
    }
}

class Action_order_paymentorder_offline extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/order/paymentorder_offline.html");
    }
}

class Action_order_paymentorder_closed extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/order/paymentorder_closed.html");
    }
}

/*售后服务*/


/*退款记录*/
class Action_order_returnrecord extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/order/returnrecord.html");
    }
}


//异常列表
class Action_order_abnormalset extends XAction
{
    public function _run($request, $xcontext)
    {
        $ql = SundialQL::create()
          ->select()
          ->from("orderunusual")
          ->where("$0.isdelete", "=", "N");
        $list = $ql->querys();
        $xcontext->list = $list;
        return XNext::useTpl("/order/abnormalset.html");
    }
}
//新增异常
class Action_order_addabnormal extends XAction
{
    public function _run($request, $xcontext)
    {
        $name = $request->attr['name'];
        $result=OrderunusualSvc::ins()->add($name);
        return XNext::gotourl('/order/abnormalset.php');
    }
}
//修改异常
class Action_order_editabnormal extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $name = $request->attr['name'];
        $result=OrderunusualSvc::ins()->edit($id,$name);
        echo $result;
    }
}
//删除异常
class Action_order_delabnormal extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result=OrderunusualSvc::ins()->del($id);
        echo $result;
    }
}

//分类列表
class Action_order_orderclassify extends XAction
{
    public function _run($request, $xcontext)
    {
        $ql = SundialQL::create()
          ->select()
          ->from("ordercategory")
          ->where("$0.isdelete", "=", "N");
        $list = $ql->querys();
        $xcontext->list = $list;
        return XNext::useTpl("/order/orderclassify.html");
    }
}
//新增分类
class Action_order_addorderclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $name = $request->attr['name'];
        $comment = $request->attr['comment'];
        $result=OrdercategorySvc::ins()->add($name,$comment);
        return XNext::gotourl('/order/orderclassify.php');
    }
}
//修改分类
class Action_order_editorderclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $name = $request->attr['name'];
        $comment = $request->attr['comment'];
        $result=OrdercategorySvc::ins()->edit($id,$name,$comment);
        echo $result;
    }
}
//删除分类
class Action_order_delorderclass extends XAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->attr['id'];
        $result=OrdercategorySvc::ins()->del($id);
        echo $result;
    }
}
class Action_order_order extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/order/order.html");
    }
}



