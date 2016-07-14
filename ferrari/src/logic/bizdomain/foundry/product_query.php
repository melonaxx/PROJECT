<?php
class processfactoryQuery extends Query
{
	//所有代工户		
	public function all_oem($where,$start,$stop)
	{
	    $sql = "select * from processfactory $where limit $start,$stop";
	    return $this->listByCmd($sql);
	}
	//一个代工户	
	public function one_oem($oemid)
	{
	    $sql = "select * from processfactory where id = ?";
	    return $this->getByCmd($sql,array($oemid));
	}
	//条数
	public function count($where)
	{
	    $sql = "select count(*) as num from processfactory $where";
	    return $this->getByCmd($sql);
	}
	/**
	 * 生产单 代工户关联表 取一个订单的所有代工户 processmanufactory
	 */
	public function pro_alloem($where)
	{
		$sql = "select * from processmanufactory $where";
		return $this->listByCmd($sql);
	}
}
//添加生产单
class addproductQuery extends Query
{
	//所有商品
	public function goods_list($where)
	{
	    $sql = "select * from product $where";
	    return $this->listByCmd($sql);
	}
	//所有商品的单位id
	public function get_flats($productid)
	{
	    $sql = "select id,unitid from proinfo where productid = $productid";
	    return $this->getByCmd($sql,array($productid));
	}
	//查出单位的名称
	public function get_flats_name($unitid)
	{
	    $sql = "select id,name from prounit where id = $unitid";
	    return $this->getByCmd($sql,array($unitid));
	}
}
// 查看生产单
class showproductQuery extends Query
{
	//所有待审核待修改生产单
	public function all_pro_order($where,$start,$stop)
	{
	    $sql = "select *,manufactory.id as orderid from manufactory $where limit ?,?";
	    return $this->listByCmd($sql,array($start,$stop));

	}
	//所有生产单商品进出库汇总
	public function all_goods_count($where)
	{
	    $sql = "select *,sum(totalfinish)as finish,sum(totalrefund)as refund from manufactory,product $where group by manufactory.productid";
	    return $this->listByCmd($sql);

	}
	//代工户商品汇总
	public function oem_goods($where)
	{
	    $sql = "select manufactory.productid,processmanufactory.profactoryid,sum(processmanufactory.totalfinish)as finish,sum(processmanufactory.totalrefund)as refund from processmanufactory,manufactory $where group by processmanufactory.profactoryid,manufactory.productid";
	    return $this->listByCmd($sql);
	}
	//总共多少条
	public function count($where){
		$sql = "select count(*)as num from manufactory $where";
		 return $this->getByCmd($sql);
	}
	//入库仓库
	public function one_storehouse($sid)
	{
	    $sql = "select name,storetype from storeinfo where id = ?";
	    return $this->getByCmd($sql,array($sid));
	}
	//操作人
	public function act_people($uid)
	{
	    $sql = "select name from user where id = ?";
	    return $this->getByCmd($sql,array($uid));
	}
	//商品名称 编码
	public function goodsname($sid)
	{
	    $sql = "select name,number from product where productid = ?";
	    return $this->getByCmd($sql,array($sid));
	}
	//一个生产单的信息
	public function one_pro_info($sid)
	{
	    $sql = "select * from manufactory where id = ?";
	    return $this->getByCmd($sql,array($sid));
	}
	//查看仓库有没有此商品
	public function strpro($productid)
	{
	    $sql = "select id from strproduct where productid = ?";
	    return $this->getByCmd($sql,array($productid));
	}
	//所有生产单的编码
	public function pro_orderlist($where)
	{
	    $sql = "select number from manufactory $where";
	    return $this->listByCmd($sql);

	}
}


// processmanufactory生产单 代工户明细表

class ProcessmanufactoryQuery extends Query
{
	//所有totalfinish数量
	public function count_totalfinish($id)
	{
		$sql = "select totalfinish,total,profactoryid,totalrefund from processmanufactory where id = ?";

		return $this->getByCmd($sql,array($id));
	}
	public function count($where)
	{
		$sql = "select count(*)as num from processmanufactory $where";

		return $this->getByCmd($sql);
	}
	public function all_pro_order($where)
	{
		$sql = "select manufactory.actiondate,manufactory.number,processmanufactory.totalfinish as totalfinish,processmanufactory.totalrefund as totalrefund,manufactory.productid,processmanufactory.profactoryid from manufactory,processmanufactory $where";

		return $this->listByCmd($sql);
	}
}

class FprobillQuery extends Query
{
	//出入库的数据
	public function all_data($where,$start,$stop)
	{
		$sql = "select * from fprobill $where order by actiontime desc limit $start,$stop";

		return $this->listByCmd($sql);
	}
	public function count($where){

		$sql = "select count(*)as num from fprobill $where";

		return $this->getByCmd($sql);
	}
	//生产 - 代工户商品出入库明细
	public function one_info($infoid){
		$sql = "select * from fprochange where infoid = $infoid";
		return $this->listByCmd($sql);
	}
}

// 生产 - 手动调拨原料
class AllocaterawQuery extends Query
{
	public function all_raw($where,$start,$stop)
	{
		$sql = "select * from allocateraw $where limit $start,$stop";

		return $this->listByCmd($sql);
	}
	public function count_raw($where)
	{
		$sql = "select count(*)as num from allocateraw $where";

		return $this->getByCmd($sql);
	}
}

//代工库实时原料明细数量
class FstoresyncQuery extends Query
{
	public function one_raw($profactoryid,$goodsid)
	{
		$sql = "select * from fstoresync where profactoryid = $profactoryid and productid = $goodsid";

		return $this->getByCmd($sql);
	}
	public function count_raw($where)
	{
		$sql = "select count(*)as num from fstoresync $where";

		return $this->getByCmd($sql);
	}
	public function all_raw($where,$start,$stop)
	{
		$sql = "select * from fstoresync $where limit $start,$stop";

		return $this->listByCmd($sql);
	}
}

//代工库减库记录
class FstoredescQuery extends Query
{
	public function all_row($where,$start,$stop)
	{
		$sql = "select * from fstoredes $where limit $start,$stop";

		return $this->listByCmd($sql);
	}
	public function count($where)
	{
		$sql = "select count(*)as num from fstoredes $where";

		return $this->getByCmd($sql);
	}
}

// 生产单结算记录
class FprosettleQuery extends Query
{
	public function all_settle_log($where)
	{
		$sql = "select * from fprosettle $where";

		return $this->listByCmd($sql);
	}

	// 生产公用关联财务科目表
	public function all_subject($infoid,$type)
	{
		$sql = "select * from fprofinance where infoid = $infoid and type = '{$type}'";

		return $this->listByCmd($sql);
	}

	// 日常开票记录
	public function all_invoice($where)
	{
		$sql = "select * from makinvoicelog $where";

		return $this->listByCmd($sql);
	}
}

/**
  *仓库信息
  *
  *
  */
class StoreinfoQuery extends Query
{
	public function showstoreinfo($store_id)
	{
		$array = array('Sales'=>'销售仓','Defective'=>'次品仓','Customer'=>'售后仓','Purchase'=>'采购仓');

		$storeinfolist = XDao::query('showproductQuery')->one_storehouse($store_id);

		$storetype	   = $array["{$storeinfolist['storetype']}"];

		$storeinfo     = $storeinfolist['name'].'('.$storetype.')';

		return $storeinfo;
	}
}

/**
  *入库状态
  *
  *
  */
class RecstatusQuery extends Query
{
	public function showRecstatus($statusreceipt)
	{
		$array 		   = array('N'=>'未入库','P'=>'部分入库','Y'=>'全部入库');

		$storeRec	   = $array["{$statusreceipt}"];

		return $storeRec;
	}
}
 /**
  *返工状态
  *statusrefund
  *
  */
 class RefstatusQuery extends Query
{
	public function showRefstatus($statusreceipt)
	{
		$array 		   = array('N'=>'未返工','P'=>'部分返工','Y'=>'全部返工');

		$storeRef	   = $array["{$statusreceipt}"];

		return $storeRef;
	}
}

 /**
  *入库单据还是出库
  *statusrefund
  *
  */
class StoretypeQuery extends Query
{
	public function showstatus($storetype)
	{
		$array 		   = array('I'=>'入库单据','O'=>'出库单据');

		$type	   = $array["{$storetype}"];

		return $type;
	}
}