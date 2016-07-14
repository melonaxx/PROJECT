<?php
/**
 * crm添加客户
 */
class Action_crm_addcustomer extends XAction
{
    public function _run($request, $xcontext)
    {
        return XNext::useTpl("/crm/addcustomer.html");
    }
}


class Action_crm_addcustomertotable extends XAction
{
    public function _run($request, $xcontext)
    {
        $customerdata = $request->customerobj;

        $name        =$customerdata['customername'];
        $nick        =$customerdata['customernick'];
        $type        =$customerdata['customertype'];
        $payment     =$customerdata['payment'];
        $mobile      =$customerdata['customerphone'];
        $telphone    =$customerdata['clienttel'];
        $companyname =$customerdata['customercom'];
        $postcode    =$customerdata['cuspostcode'];
        $mailbox     =$customerdata['cusemail'];
        $qq          =$customerdata['cusQQ'];
        $stateid     =$customerdata['province'];
        $cityid      =$customerdata['city'];
        $districtid  =$customerdata['town'];
        $address     =$customerdata['cusaddress'];
        $comment     =$customerdata['cuscomment'];

        $cusflag = CustomerInfoSvc::ins()->addcustomerdata($name,$nick,$type,$payment,$mobile,$telphone,$companyname,$postcode,$mailbox,$qq,$stateid,$cityid,$districtid,$address,$comment);

        if (!$cusflag) {
            echo ResultSet::jfail(500, "Server Error：getallocatedata Fail");
            return XNext::nothing();
        }
        echo ResultSet::jsuccess($cusflag);
        return XNext::nothing();
    }
}