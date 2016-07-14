<?php
/**
 * @brief 通过订单ID查询订单编辑中的图片
 *
 * @param 订单ID
 *
 * @return 订单图片列表
 **/
class ListOrderMsgImgQuery extends Query
{
    //通过订单ID查询订单的图片信息
    public function getOrderImg($orderid)
    {
        $sql = "SELECT * FROM ordermsgimg WHERE orderid=? AND isdelete='N'";
        return $this->listByCmd($sql,array('orderid'=>$orderid));
    }
}


