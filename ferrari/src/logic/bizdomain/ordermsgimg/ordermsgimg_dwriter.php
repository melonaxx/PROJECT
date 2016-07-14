<?php
/**
 * 对订单编辑中的订单图片进行删除
 */
class OrderMsgImgWriter extends DWriter
{
    /*删除订单中的图片*/
    public function delOrderImg($ordermsgid)
    {
    	$sql = "UPDATE ordermsgimg SET isdelete='Y' WHERE id=?";
    	return $this->exeByCmd($sql,array('id'=>$ordermsgid));
    }
}