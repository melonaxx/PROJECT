<?php
class PurchaseproductWriter extends DWriter
{
    public function delproduct($purchaseid)
    {
        $sql = "delete from purchaseproduct where purchaseid = ?";
        return $this->exeByCmd($sql, array($purchaseid));
    }

    public function productruku($num,$num,$productid,$purchaseid)
    {
    	$sql = "update purchaseproduct set totalfinish = totalfinish + ?,totalway = totalway - ? where productid = ? and purchaseid = ?";
    	return $this->exeByCmd($sql,array($num,$num,$productid,$purchaseid));
    }
    public function productchuku($num,$num,$productid,$purchaseid)
    {
    	$sql = "update purchaseproduct set totalfinish = totalfinish - ?,totalrefund = totalrefund + ? where productid = ? and purchaseid = ?";
    	return $this->exeByCmd($sql,array($num,$num,$productid,$purchaseid));
    }
}