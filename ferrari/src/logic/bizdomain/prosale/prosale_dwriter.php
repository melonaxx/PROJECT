<?php
/**
 * @brief 修改商品在售状态
 *
 * @param 商品要修改的信息
 *
 * @return bool
 **/
class ProSaleWriter extends DWriter
{
    public function updateProSale($productid,$prosale)
    {

        $sql = "update prosale set salesstatus=? where productid = ?";

        return $this->exeByCmd($sql, array('prosale'=>$prosale,'productid' => $productid));
    }
}
