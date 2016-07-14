<?php
class PurchaseWriter extends DWriter
{
    public function purchaseruku($num,$num,$status,$id)
    {
        $sql = "update purchase set totalfinish = totalfinish+?,totalway=totalway-?,statusreceipt=? where id = ?";
        return $this->exeByCmd($sql, array($num,$num,$status,$id));
    }
    public function purchasechuku($num,$num,$status,$id)
    {
        $sql = "update purchase set totalfinish = totalfinish-?,totalrefund=totalrefund+?,statusrefund=? where id = ?";
        return $this->exeByCmd($sql, array($num,$num,$status,$id));
    }
    public function editreturnmoney($money,$number)
    {
        $sql = "update purchase,purchasefinance set purchasefinance.paymentreturn = ? where purchase.number = ? and purchase.id = purchasefinance.purchaseid";
        return $this->exeByCmd($sql, array($money,$number));
    }
}