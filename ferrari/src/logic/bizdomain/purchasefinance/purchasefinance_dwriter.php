<?php
class PurchasefinanceWriter extends DWriter
{
    public function editforpurchaseid($paymenttotal,$paymentremain,$id)
    {
        $sql = "update purchasefinance set paymenttotal = ?,paymentremain = ? where purchaseid = ?";
        return $this->exeByCmd($sql, array($paymenttotal,$paymentremain,$id));
    }
    //采购单付款
    public function payfor($status,$yifu,$qiankuan,$gysqiankuan,$supplierid)
    {
        $sql = "update purchasefinance set status = ?,paymentalready = ?,paymentremain = ?,paymentreturn=? where purchaseid = ?";
        return $this->exeByCmd($sql, array($status,$yifu,$qiankuan,$gysqiankuan,$supplierid));
    }
}