<?php
/**
 *改变生产单 代工户关联表的库存
 *
 *
 */
class OemproWriter extends DWriter
{
   public function updatestore($to_storenum,$pre_oemid)
	{
		 $sql = "update processmanufactory set totalfinish=totalfinish+$to_storenum,totalway=totalway-$to_storenum where id = $pre_oemid";
        return $this->exeByCmd($sql);
	} 
	//出库
    public function changestore_num($to_storenum,$pre_oemid)
	{
		 $sql = "update processmanufactory set totalfinish=totalfinish-$to_storenum,totalrefund=totalrefund+$to_storenum where id = $pre_oemid";
        return $this->exeByCmd($sql);
	}
}


// 代工库实时原料明细数量
class FstoresyncWriter extends DWriter
{
	//改变数量
    public function changestore_num($num,$pre_oemid,$productid)
	{
		 $sql = "update fstoresync set total=total+$num where profactoryid = $pre_oemid and productid = $productid";
        return $this->exeByCmd($sql);
	}
	//减库
    public function updatestore_num($num,$pre_oemid,$productid)
	{
		 $sql = "update fstoresync set total=total-$num where profactoryid = $pre_oemid and productid = $productid";
        return $this->exeByCmd($sql);
	}
}

//生产单数量修改
class ManufactoryWriter extends DWriter
{
	//改变在途数量
    public function change_way($pro_orderid)
	{
		 $sql = "update manufactory set totalway=total where id = $pro_orderid";
        return $this->exeByCmd($sql);
	}
	//改变入库数量  
    public function update_way($count,$pro_orderid)
	{
		 $sql = "update manufactory set totalway=totalway-$count,totalfinish = totalfinish+$count where id = $pro_orderid";
        return $this->exeByCmd($sql);
	}
	//改变返工数量
    public function update_rework($count,$pro_orderid)
	{
		 $sql = "update manufactory set totalfinish=totalfinish-$count,totalrefund=totalrefund+$count where id = $pro_orderid";
        return $this->exeByCmd($sql);
	}
}