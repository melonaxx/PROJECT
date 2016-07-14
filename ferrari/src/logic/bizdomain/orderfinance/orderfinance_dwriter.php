<?php
class OrderfinanceWriter extends DWriter
{
    public function editfororderid($pays,$pays,$id)
    {
        $sql = "update orderfinance set updatetime = now(),ypayment = ?,qpayment = ? where orderid = ?";

        return $this->exeByCmd($sql,array($pays,$pays,$id));
    }
    //收款时候修改
    public function dopayin($orderid,$shishou,$qiankuan,$zhanghao,$ruzhang,$kemu)
    {
        $sql = "update orderfinance set updatetime = now(),financesubid=$kemu,bankid=$zhanghao,spayment=spayment+$shishou,qpayment=$qiankuan,status='".$ruzhang."' where orderid = $orderid";

        return $this->exeByCmd($sql);
    }
    //拆分订单时按订单id修改应付金额
    public function chaiedit($orderid,$pays)
    {
        $sql = "update orderfinance set updatetime = now(),ypayment = $pays,qpayment = $pays where orderid = $orderid";
        return $this->exeByCmd($sql);
    }
}
