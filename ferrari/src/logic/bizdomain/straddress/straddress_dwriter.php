<?php
/**
 * @brief 商品仓库发货信息
 *
 * @param
 *
 * @return bool
 **/
class StrAddressWriter extends DWriter
{
    //删除仓库发货地址
    public function delShipAddress($storeid) {
        $sql ="delete from straddress where storeid=?";
        return $this->exeByCmd($sql,array('storeid'=>$storeid));
    }
}
