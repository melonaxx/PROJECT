<?php

//要打印的所有订单
class Action_delivery_deliver_orderprite extends XAction
{
    public function _run($request, $xcontext)
    {

        // $list = array(
        //             array("itemid"=>1,"name"=>'李彦宏'),
        //             array("itemid"=>3,"name"=>'江苏省常州市'),
        //             array("itemid"=>4,'name'=>'3231384988'),
        //             array("itemid"=>5,'name'=>'李大嘴'),
        //             array("itemid"=>6,'name'=>'北京市大嘴区')
        //         );

        // $mid = 66390;
        // //查出一个快递模版的一些属性
        // $model = XDao::query("lodopquery")->onemodel($mid);
        // //此模版的一些控件
        // $item = XDao::query("lodopquery")->onemodel_item($mid);

        // foreach($list as $k => $v){
        //      foreach($item as $k1 => $v2){
        //           if($v['itemid'] == $v2['itemid']){
        //               $item[$k1]['name']=$v['name'];
        //           }
        //      }
        // }
        // $model['item']=$item;
        // echo json_encode($model);
    }
}

//请求各种类型的模版
class Action_delivery_request_model extends XAction
{
    public function _run($request, $xcontext)
    {
         $classval = $_POST['classval'];

         if($classval == "a"){

                $model = XDao::query("lodopquery")->allmodel();

                echo json_encode($model);

         }else if($classval == "b"){

                $model = XDao::query("lodopquery")->all_invoice_model($where="where status = 'Y'");

                echo json_encode($model);

         }else if($classval == "c"){

                $model = XDao::query("lodopquery")->all_picking_model($where="where status = 'Y'");

                echo json_encode($model);

         }
    }
}

class Action_delivery_deliverabnormal extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("delivery/deliver_abnormal.html");
    }
}
class Action_delivery_deliverscanlist extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("delivery/deliver_scanlist.html");
    }
}
class Action_delivery_deliver_scanlist_handle extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("delivery/deliver_scanlist_handle.html");
    }
}

class Action_delivery_delivershipment extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("delivery/deliver_shipment.html");
    }
}
class Action_delivery_delivershipped extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("delivery/deliver_shipped.html");
    }
}
/*已关闭订单*/
class Action_delivery_closeorder extends XAction
{
    public function _run($request, $xcontext)
    {
        $list = XDao::query("orderinfoQuery")->finddel();
        $xcontext->list = $list;
        return XNext::useTpl("/delivery/closeorder.html");
    }
}
/*编辑扫单发货订单*/
class Action_delivery_editdeliver extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("delivery/editdeliver.html");
    }
}
/*编辑异常订单*/
class Action_delivery_editabnormal extends XAction
{
    public function _run($request, $xcontext)
    {
         return XNext::useTpl("delivery/editabnormal.html");
    }
}

